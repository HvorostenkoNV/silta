<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_framework"))                  return ShowError("module silta_framework required!");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

TYPE          - тип кнопки add/remove
PARENT        - родительский тэг (что копировать/удалять)
CLEAR_FORM    - делать очищенную копию (для типа add) Y/N
RENAME_INPUTS - переименовать поля с заменой подстроки
*/
/* -------------------------------------------------------------------- */
/* -------------------------- корректировки --------------------------- */
/* -------------------------------------------------------------------- */
$clearForm = true;
if($arParams["CLEAR_FORM"] == 'N') $clearForm = false;
if(!in_array($arParams["TYPE"], ["add", "remove"])) $arParams["TYPE"] = 'add';
if($arParams["RENAME_INPUTS"]) $renameInputes = $arParams["RENAME_INPUTS"];
/* -------------------------------------------------------------------- */
/* ----------------------- параметры для шаблона ---------------------- */
/* -------------------------------------------------------------------- */
$arResult =
	[
	"type"           => $arParams["TYPE"],
	"parent"         => $arParams["PARENT"],
	"clear_form"     => $clearForm,
	"rename_inputes" => $renameInputes
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate(); 
?>