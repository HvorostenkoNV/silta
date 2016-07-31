<?
define('STOP_STATISTICS', true);
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';
$APPLICATION->RestartBuffer();

if(!CModule::IncludeModule("silta_framework") || !$_POST["table"]) exit();
include '../lang/'.LANGUAGE_ID.'/template.php';
$tableObject = new SIBlockTable(["id" => $_POST["table"]]);
if(!$tableObject) exit();
/* -------------------------------------------------------------------- */
/* -------------------------- инфа для вывода ------------------------- */
/* -------------------------------------------------------------------- */
$arResult =
	[
	"selector_params" => $_POST, // параметры селектора
	"search_field"    => false,  // вывод строки поиска элементов
	"table_hat"       => [],     // массив инфы для шапки селектора
	"table_body"      => [],     // массив инфы для тела селектора
	"table_foot"      =>
		[
		"element_count"    => '',     // всего элементов 
		"elements_on_page" => '',     // элементов на странице
		"navigation_pages" => []      // массив инфы для навигации
		],
	];
/* -------------------------------------------------------------------- */
/* ------------------------- свойства таблицы ------------------------- */
/* -------------------------------------------------------------------- */
$tableProps = SgetClearArray(explode('|', $_POST["props"]));
if(!$tableProps[0]) $tableProps = ["name"];
foreach($tableProps as $property) $tableObject->SetProperty($property);
if($tableObject->GetProperty("name")) $arResult["search_field"] = true;
/* -------------------------------------------------------------------- */
/* ------------------------------ фильтр ------------------------------ */
/* -------------------------------------------------------------------- */
foreach(SgetClearArray(explode(';', $_POST["filter"])) as $string)
	{
	$explodeArray = explode(':', $string);
	$tableFilter[$explodeArray[0]] = explode('/', $explodeArray[1]);
	}
/* -------------------------------------------------------------------- */
/* ---------------------------- навигация ----------------------------- */
/* -------------------------------------------------------------------- */
// размер страницы
$navPageSize = 50;
// общее кол-во элементов
$navElementCount = count($tableObject->getQuery([], $tableFilter));
if(!$navElementCount) $navElementCount = '0';
// кол-во страниц
$navPageCount = ceil($navElementCount/$navPageSize);
if(!$navPageCount || $navPageCount < 1) $navPageCount = 1;
// текущая страница
$navCurrentPage = $_POST["navigator"];
if(!$navCurrentPage || $navCurrentPage < 1 || $navCurrentPage > $navPageCount) $navCurrentPage = 1;
// страницы, доступные для выбора
$navAvailablePages =
	[
	1,
	$navPageCount,
	$navCurrentPage-2,
	$navCurrentPage-1,
	$navCurrentPage,
	$navCurrentPage+1,
	$navCurrentPage+2,
	];
// кол-во элементов на странице
$navElementsOnPage = $navPageSize;
if($navCurrentPage == $navPageCount)
	$navElementsOnPage = $navElementCount - ( ($navPageCount-1) * $navPageSize);
// инфа для вывода
$arResult["table_foot"] =
	[
	"element_count"    => $navElementCount,
	"elements_on_page" => $navElementsOnPage,
	];

for($page = 1;$page <= $navPageCount;$page++)
	{
	$infoArray =
		[
		"value"   => '',
		"space"   => false,
		"checked" => false
		];

	if(in_array($page, $navAvailablePages))       $infoArray["value"]   = $page;
	elseif(in_array($page-1, $navAvailablePages)) $infoArray["space"]   = true;
	if($infoArray["value"] == $navCurrentPage)    $infoArray["checked"] = true;
	$arResult["table_foot"]["navigation_pages"][] = $infoArray;
	}
/* -------------------------------------------------------------------- */
/* ---------------------------- сортировка ---------------------------- */
/* -------------------------------------------------------------------- */
// переданный параметр сортировки
$valueSorter = explode('|', $_POST["sorter"]);
$sorterBy   = $valueSorter[0];
$sorterType = $valueSorter[1];
if(!$sorterBy)   $sorterBy   = 'name';
if(!$sorterType) $sorterType = 'asc';
// инфа для вывода
foreach($tableObject->GetPropertyList() as $property => $propertyObject)
	{
	$sortValue = 'asc';
	$infoArray =
		[
		"title"  => $propertyObject->GetAttributes()["title"],
		"value"  => '',
		"sorter" => ''
		];

	if($property == $sorterBy)
		{
		$infoArray["sorter"] = $sorterType;
		if($sorterType == 'asc') $sortValue = 'desc';
		}

	$infoArray["value"] = $property.'|'.$sortValue;
	$arResult["table_hat"][] = $infoArray;
	}
/* -------------------------------------------------------------------- */
/* --------------------------- тело таблицы --------------------------- */
/* -------------------------------------------------------------------- */
foreach($tableObject->GetQuery([$sorterBy => $sorterType], $tableFilter, ["page" => $navCurrentPage, "page_size" => $navPageSize]) as $elementId)
	foreach($tableObject->GetElement($elementId)->GetPropertyList() as $propertyObject)
		$arResult["table_body"][$elementId][] = $propertyObject;
/* -------------------------------------------------------------------- */
/* ---------------------------- вывод формы --------------------------- */
/* -------------------------------------------------------------------- */
?>
<div id="silta-form-element-selector-form">
	<span selector-params style="display: none">
		<?foreach($arResult["selector_params"] as $index => $value):?>
		<input name="<?=$index?>" value="<?=$value?>">
		<?endforeach?>
	</span>

	<table selector-hat>
		<tr><th><?=GetMessage("SF_ES_FORM_TITLE")?></th></tr>
	</table>

	<div selector-content>
		<table>
			<tr>
				<?foreach($arResult["table_hat"] as $infoArray):?>
				<td>
					<span
						table-sorter="<?=$infoArray["value"]?>"
						sort-type="<?=$infoArray["sorter"]?>"
					>
						<?=$infoArray["title"]?>
					</span>
				</td>
				<?endforeach?>
			</tr>

			<?foreach($arResult["table_body"] as $elementId => $propsArray):?>
			<tr element-row="<?=$elementId?>">
				<?foreach($propsArray as $propertyObject):?>
				<td>
					<?
					$APPLICATION->IncludeComponent
						(
						"silta_framework:form_elements.property_field", '',
							[
							"FIELD_TYPE"      => 'read',
							"PROPERTY_OBJECT" => $propertyObject
							]
						)
					?>
				</td>
				<?endforeach?>
			</tr>
			<?endforeach?>
		</table>
	</div>

	<table selector-foot>
		<tr>
			<td>
				<?foreach($arResult["table_foot"]["navigation_pages"] as $infoArray):?>
					<?if($infoArray["value"] &&  $infoArray["checked"]):?><span   navigation-page="active"><?=$infoArray["value"]?></span><?endif?>
					<?if($infoArray["value"] && !$infoArray["checked"]):?><span navigation-page="unactive"><?=$infoArray["value"]?></span><?endif?>
					<?if($infoArray["space"]):?>                          <span>...</span>                                                <?endif?>
				<?endforeach?>
			</td>
			<td info>
				<div><?=GetMessage("SF_ES_FORM_ELEMENT_COUNT")?>:    <b><?=$arResult["table_foot"]["element_count"]?>   </b></div>
				<div><?=GetMessage("SF_ES_FORM_ELEMENTS_ON_PAGE")?>: <b><?=$arResult["table_foot"]["elements_on_page"]?></b></div>
			</td>
		</tr>
		<tr>
			<td search-cell colspan="2">
				<?if($arResult["search_field"]):?>
				<input type="text" placeholder="<?=GetMessage("SF_ES_FORM_SEARCH_TITLE")?>">
				<?endif?>
				<span company-logo></span>
			</td>
		</tr>
	 </table>
</div>