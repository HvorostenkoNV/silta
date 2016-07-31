<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_diy_module"))                 return ShowError("modules required: silta_diy_module");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

ELEMENT_OBJECT - объект элемента
HOME_LINK      - ссылка "домой"
FORM_TABS      - массив таб, где ключ = значение GET переданной, значение - титул
*/
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$elementObject = $arParams["ELEMENT_OBJECT"];
if(!is_subclass_of($elementObject, 'SDBElement')) return;
// табы
if(count($arParams["FORM_TABS"]))
	foreach($arParams["FORM_TABS"] as $value => $title)
		{
		$infoArray =
			[
			"link"  => SgetUrlVarsString([SDM_URL_ELEMENT_TAB_VAR => $value]),
			"title" => $title
			];
		if($value == $_GET[SDM_URL_ELEMENT_TAB_VAR]) $infoArray["checked"] = true;
		$tabsArray[] = $infoArray;
		}
// новый элемент
$newElement = false;
if($elementObject->GetElementId() == 'new') $newElement = true;
// таблица
foreach(SDiyModule::GetInstance()->GetTablesInfo() as $table => $tableInfo)
	if($tableInfo["id"] == $elementObject->GetTableObject()->GetIblockId())
		$workTable = ToUpper($table);
/* -------------------------------------------------------------------- */
/* ----------------------- параметры для шаблона ---------------------- */
/* -------------------------------------------------------------------- */
$arResult =
	[
	"table"        => $workTable,                                      // таблица
	"new_element"  => $newElement,                                     // новый элемент
	"element_name" => $elementObject->GetProperty("name")->GetValue(), // название элемента
	"tabs"         => $tabsArray,                                      // массив табов формы
	"home_link"    => $arParams["HOME_LINK"]                           // ссылка "домой"
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>