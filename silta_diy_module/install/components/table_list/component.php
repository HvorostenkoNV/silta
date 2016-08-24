<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_diy_module"))                 return ShowError("modules required: silta_diy_module");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

WORK_TABLE     - рабочая таблица
TABLE_PROPS    - свойства таблицы
ELEMENTS_COUNT - кол-во записей на странице
MULTIPLY_EDIT  - множ.изменения Y/N
LINKS          - ссылки
DEFAULT_SORTER - сортировка по умолчанию
*/
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$tableObject = SDiyModule::GetInstance()->GetTable($arParams["WORK_TABLE"]); // объект таблицы
if(!$tableObject) return;
// свойства таблицы
foreach($arParams["TABLE_PROPS"] as $index => $value)
	if(!$tableObject->GetProperty($value))
		unset($arParams["TABLE_PROPS"][$index]);
$arParams["TABLE_PROPS"] = SgetClearArray($arParams["TABLE_PROPS"]);
if(!$arParams["TABLE_PROPS"][0]) return;
// множ.изменения
if($arParams["MULTIPLY_EDIT"] != 'N')
	$availableMultiChangings =
		[
		"activate",
		"diactivate",
		"delete"
		];
// имена переменных сессии
$sorterVarName     = 'sdm_sorter_'.$arParams["WORK_TABLE"];     // сортировка
$navigationVarName = 'sdm_navigation_'.$arParams["WORK_TABLE"]; // навигация
// имена полей формы
$inputNameSorter    = 'diy-module-table-list-sorter';      // кнопка сортировки
$inputNameNavigaion = 'diy-module-table-list-navigation';  // кнопка навигации
$inputNameElements  = 'diy-module-table-list-elements';    // поля элемента таблицы
$inputNameChanger   = 'diy-module-table-list-change-type'; // селект "Тип изменения"
$inputNameSubmit    = 'diy-module-table-list-submit';      // кнопка Submit
/* -------------------------------------------------------------------- */
/* ---------------------------- обработчик ---------------------------- */
/* -------------------------------------------------------------------- */
// сортировка
if(isset($_POST[$inputNameSorter]))
	{
	$_SESSION[$sorterVarName][$USER->GetId()] = $_POST[$inputNameSorter];
	LocalRedirect($APPLICATION->GetCurPage().SgetUrlVarsString());
	}
// навигация
if(isset($_POST[$inputNameNavigaion]))
	{
	$_SESSION[$navigationVarName][$USER->GetId()] = $_POST[$inputNameNavigaion];
	LocalRedirect($APPLICATION->GetCurPage().SgetUrlVarsString());
	}
// множ.изменения
if(isset($_POST[$inputNameSubmit]))
	{
	$changeType       = $_POST[$inputNameChanger];
	$selectedElements = $_POST[$inputNameElements];
	$changedElements  = 0;
	if(!in_array($changeType, $availableMultiChangings))
		LocalRedirect($APPLICATION->GetCurPage().SgetUrlVarsString());

	foreach($selectedElements as $elementId)
		{
		$elementObject = $tableObject->GetElement($elementId);
		if(!$elementObject) continue;

		$success = false;
		if($changeType == 'activate')   $success = $elementObject->SaveDiyElement(["active" => 'Y']);
		if($changeType == 'diactivate') $success = $elementObject->SaveDiyElement(["active" => 'N']);
		if($changeType == 'delete')     $success = $elementObject->DeleteElement();
		if($success) $changedElements++;
		}

	$_SESSION["SDM_TABLE_LIST_ALERT"][$USER->GetId()] = count($selectedElements).'|'.$changedElements.'|'.$changeType;
	LocalRedirect($APPLICATION->GetCurPage().SgetUrlVarsString());
	}
/* -------------------------------------------------------------------- */
/* ------------------------- навигация таблицы ------------------------ */
/* -------------------------------------------------------------------- */
// размер страницы
$navPageSize = $arParams["ELEMENTS_COUNT"];
if(!$navPageSize) $navPageSize = 25;
// общее кол-во элементов
$navElementCount = count($tableObject->GetQuery([], $tableObject->GetQueryOptions()["filter"])); // GetQueryOptions not used
if(!$navElementCount) $navElementCount = '0';
// кол-во страниц
$navPageCount = ceil($navElementCount/$navPageSize);
if(!$navPageCount || $navPageCount < 1) $navPageCount = 1;
// текущая страница
$navCurrentPage = $_SESSION[$navigationVarName][$USER->GetId()];
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
for($page = 1;$page <= $navPageCount;$page++)
	{
	$infoArray =
		[
		"value"   => '',    // номер страницы
		"space"   => false, // буфер между первыми и последними страницами
		"checked" => false  // выбранная страница
		];

	if(in_array($page, $navAvailablePages))       $infoArray["value"]   = $page;
	elseif(in_array($page-1, $navAvailablePages)) $infoArray["space"]   = true;
	if($infoArray["value"] == $navCurrentPage)    $infoArray["checked"] = true;
	$navPagesInfo[] = $infoArray;
	}
/* -------------------------------------------------------------------- */
/* ------------------------ сортировка таблицы ------------------------ */
/* -------------------------------------------------------------------- */
// переданный параметр сортировки
$valueSorter = $_SESSION[$sorterVarName][$USER->GetId()];
if(!$valueSorter) $valueSorter = $arParams["DEFAULT_SORTER"];
if(!$valueSorter) $valueSorter = 'name|asc';
$valueSorter = explode('|', $valueSorter);
$sorterBy   = $valueSorter[0];
$sorterType = $valueSorter[1];
// инфа для вывода
foreach($arParams["TABLE_PROPS"] as $property)
	{
	$sort_value = 'asc';
	$infoArray  =
		[
		"title"   => $tableObject->GetProperty($property)->GetAttributes()["title"], // титул
		"value"   => '',                                                             // значение сортировки
		"checked" => false                                                           // свойство выбранно
		];

	if($property == $sorterBy)
		{
		$infoArray["checked"] = $sorterType;
		if($sorterType == 'asc') $sort_value = 'desc';
		}

	$infoArray["value"] = $property.'|'.$sort_value;
	$tableHatInfo[] = $infoArray;
	}
/* -------------------------------------------------------------------- */
/* --------------------------- тело таблицы --------------------------- */
/* -------------------------------------------------------------------- */
foreach($tableObject->GetQuery([$sorterBy => $sorterType], $tableObject->GetQueryOptions()["filter"], ["page" => $navCurrentPage, "page_size" => $navPageSize]) as $elementId) // GetQueryOptions not used
	foreach($arParams["TABLE_PROPS"] as $property)
		$tableBodyInfo[$elementId][] = $tableObject->GetElement($elementId)->GetProperty($property);
/* -------------------------------------------------------------------- */
/* ----------------- инфа по успешному множ.изменению ----------------- */
/* -------------------------------------------------------------------- */
if($_SESSION["SDM_TABLE_LIST_ALERT"][$USER->GetId()])
	{
	$multChangingsSuccessArray = explode('|', $_SESSION["SDM_TABLE_LIST_ALERT"][$USER->GetId()]);
	unset($_SESSION["SDM_TABLE_LIST_ALERT"][$USER->GetId()]);
	}
/* -------------------------------------------------------------------- */
/* ----------------------- параметры для шаблона ---------------------- */
/* -------------------------------------------------------------------- */
$arResult =
	[
	"links"                   => $arParams["LINKS"],       // ссылки на страницы с другими компонентами
	"work_table"              => $arParams["WORK_TABLE"],  // имя рабочей таблицы
	"multiply_changing_types" => $availableMultiChangings, // возможные типы множ.изменений
	"success_changings"       =>                           // инфа по успешному множ.изменению
		[
		"elements_count" => $multChangingsSuccessArray[0],
		"success_count"  => $multChangingsSuccessArray[1],
		"operation_type" => $multChangingsSuccessArray[2]
		],
	"table_hat"               => $tableHatInfo,            // шапка таблицы
	"table_body"              => $tableBodyInfo,           // тело таблицы
	"table_foot"              =>                           // футер таблицы
		[
		"navigation_pages" => $navPagesInfo,                    // страницы для навигации
		"element_count"    => $navElementCount,                 // общее кол-во элементов
		"elements_on_page" => $navElementsOnPage                // элементов на странице
		],
	"input_name"              =>                           // имена полей формы
		[
		"sorter"     => $inputNameSorter,
		"navigation" => $inputNameNavigaion,
		"elements"   => $inputNameElements,
		"changer"    => $inputNameChanger,
		"submit"     => $inputNameSubmit
		]
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>