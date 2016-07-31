<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_diy_module"))                 return ShowError("modules required: silta_diy_module");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

ELEMENT_OBJECT - объект элемента
LINK           - базовая ссылка
NEW_WINDOW     - открывать ссылку в новом окне Y/N
*/
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$elementObject = $arParams["ELEMENT_OBJECT"];
if(!is_subclass_of($elementObject, 'SDBElement')) return;
// открывать ссылку в новом окне
$openInNewWindow = false;
if($arParams["NEW_WINDOW"] == 'Y') $openInNewWindow = true;
/* -------------------------------------------------------------------- */
/* ----------------------- параметры для шаблона ---------------------- */
/* -------------------------------------------------------------------- */
$arResult =
	[
	"new_window" => $openInNewWindow,                                // открывать ссылку в новом окне
	"title"      => $elementObject->GetProperty("name")->GetValue(), // титул ссылки
	"link"       =>                                                  // URL ссылка
		SgetClearUrl($arParams["LINK"]).
		SgetUrlVarsString([SDM_URL_ELEMENT_ID_VAR => $elementObject->GetElementId()], [SDM_URL_ELEMENT_TAB_VAR])
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>