<?
define('STOP_STATISTICS', true);
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';
$APPLICATION->RestartBuffer();

if(!CModule::IncludeModule("silta_framework")) exit();
include '../lang/'.LANGUAGE_ID.'/template.php';
/* -------------------------------------------------------------------- */
/* --------------------- функция постройки дерева --------------------- */
/* -------------------------------------------------------------------- */
function SUserSelectorBuildTree(SCompanyDepartment $departmentObject)
	{
	// заголовок раздела
	$RESULT .= '<h3>'.$departmentObject->GetName().'</h3>';
	$RESULT .= '<ul>';
	// отдел
	if($_POST["departments"] == 'Y')
		$RESULT .= '
			<li item-value="department|'.$departmentObject->GetId().'" type="department">
				Отдел "'.$departmentObject->GetName().'"
			</li>';
	// юзеры
	if($_POST["users"] == 'Y')
		{
		$userList = CUser::GetList
			(
			$by = 'LAST_NAME', $order = 'asc',
			["UF_DEPARTMENT" => $departmentObject->GetId(), "ACTIVE" => "Y"],
			["FIELDS" => ["ID", "NAME", "LAST_NAME"]]
			);
		while($user = $userList->GetNext())
			{
			$rowAttr = '';
			if($departmentObject->GetBoss() == $user["ID"]) $rowAttr = 'boss';
			$RESULT .= '
				<li item-value="user|'.$user["ID"].'" type="user" '.$rowAttr.'>
					'.$user["LAST_NAME"].' '.$user["NAME"].'
				</li>';
			}
		}
	// рекурсивный вызов
	foreach($departmentObject->GetChildren() as $childDepartmentObject)
		$RESULT .= SUserSelectorBuildTree($childDepartmentObject);
	// возврат
	$RESULT .= '</ul>';
	return $RESULT;
	}
/* -------------------------------------------------------------------- */
/* -------------------------- инфа для вывода ------------------------- */
/* -------------------------------------------------------------------- */
$arResult =
	[
	"selector_params" => $_POST,                                              // параметры селектора
	"search_field"    => false,                                               // вывод строки поиска элементов
	"start_roots"     => SgetClearArray(explode('|', $_POST["start_roots"])), // стартовые отделы селектора
	"title"           => ''                                                   // заголовок селектора
	];
// стартовые отделы
if(!$arResult["start_roots"][0])
	foreach(SCompanyDepartment::GetRootChildren() as $departmentObject)
		$arResult["start_roots"][] = $departmentObject->GetId();
// титул
    if($_POST["users"] == 'Y' && $_POST["departments"] == 'N') $arResult["title"] = GetMessage("SF_US_FORM_TITLE_USERS");
elseif($_POST["users"] == 'N' && $_POST["departments"] == 'Y') $arResult["title"] = GetMessage("SF_US_FORM_TITLE_DEPS");
else                                                           $arResult["title"] = GetMessage("SF_US_FORM_TITLE_USERS_DEPS");
// поисковая строка
if($_POST["users"] == 'Y') $arResult["search_field"] = true;
/* -------------------------------------------------------------------- */
/* ---------------------------- вывод формы --------------------------- */
/* -------------------------------------------------------------------- */
?>
<form id="silta-form-user-selector-form">
	<span selector-params style="display: none">
		<?foreach($arResult["selector_params"] as $index => $value):?>
		<input name="<?=$index?>" value="<?=$value?>">
		<?endforeach?>
	</span>

	<table selector-hat>
		<tr><th><?=$arResult["title"]?></th></tr>
	</table>

	<div selector-content>
		<?
		foreach($arResult["start_roots"] as $departmentId)
			echo SUserSelectorBuildTree(new SCompanyDepartment(["id" => $departmentId]));
		?>
	</div>

	<div selector-foot colspan="2">
		<?if($arResult["search_field"]):?>
		<input type="text" placeholder="<?=GetMessage("SF_US_FORM_SEARCH_TITLE")?>">
		<?endif?>
		<span company-logo></span>
	</div>
</form>