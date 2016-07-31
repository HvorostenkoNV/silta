<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_framework"))                  return ShowError("module silta_framework required!");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

VALUE               - значения (массив)
INPUT_NAME          - имя поля
INPUT_NAME_UPLOADED - имя полей загруженных файлов

MULTIPLY            - множ.добавление файлов Y/N
ATTR                - аттрибуты
*/
/* -------------------------------------------------------------------- */
/* -------------------------- корректировки --------------------------- */
/* -------------------------------------------------------------------- */
$arParams["VALUE"] = SgetClearArray($arParams["VALUE"]);
if(!in_array($arParams["MULTIPLY"], ["Y", "N"])) $arParams["MULTIPLY"] = 'N';
/* -------------------------------------------------------------------- */
/* ----------------------- параметры для шаблона ---------------------- */
/* -------------------------------------------------------------------- */
$arResult =
	[
	"value"               => $arParams["VALUE"],
	"input_name"          => $arParams["INPUT_NAME"],
	"input_name_uploaded" => $arParams["INPUT_NAME_UPLOADED"],

	"multiply"            => $arParams["MULTIPLY"],
	"attr"                => $arParams["VALUE"]
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate(); 
?>