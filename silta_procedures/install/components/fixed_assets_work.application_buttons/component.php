<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_procedures"))                 return ShowError("modules required: silta_procedures");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

ELEMENT_OBJECT  - объект элемента
DELETE_REDIRECT - редирект при удалении (путь)
*/
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$procedureElement = $arParams["ELEMENT_OBJECT"];                            // объект элемента
if(!$procedureElement) return ShowError(GetMessage("SF_ELEMENT_NOT_EXIST"));
$deleteButtonName = 'sp-faw-app-delete-'.$procedureElement->GetElementId(); // именя кнопки "удалить"
/* -------------------------------------------------------------------- */
/* ---------------------------- обработчик ---------------------------- */
/* -------------------------------------------------------------------- */
// удаление
if(is_set($_POST[$deleteButtonName]))
	{
	$success = $procedureElement->DeleteElement();
	if(!$success || !$arParams["DELETE_REDIRECT"]) LocalRedirect($APPLICATION->GetCurPage());
	else                                           LocalRedirect($arParams["DELETE_REDIRECT"]);
	}
/* -------------------------------------------------------------------- */
/* -------------------- готовый массив для шаблона -------------------- */
/* -------------------------------------------------------------------- */
// доступ на удаление
$deleteAccess = false;
if($procedureElement->GetAccess("delete")) $deleteAccess = true;
// готовый массив
$arResult =
	[
	"delete_access"      => $deleteAccess,
	"home_link"          => $arParams["DELETE_REDIRECT"],
	"delete_button_name" => $deleteButtonName
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>