<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_framework"))                  return ShowError("module silta_framework required!");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

VALUE       - значение
INPUT_NAME  - имя поля

PLACEHOLDER - подсказка
SIZE        - размер поля
ATTR        - аттрибуты
*/
/* -------------------------------------------------------------------- */
/* -------------------------- корректировки --------------------------- */
/* -------------------------------------------------------------------- */
$sizeValue = explode('-', $arParams["SIZE"]);
$cols = $sizeValue[0];
$rows = $sizeValue[1];

if(!$cols) $cols = '35';
if(!$rows) $rows = '3';
/* -------------------------------------------------------------------- */
/* ----------------------- параметры для шаблона ---------------------- */
/* -------------------------------------------------------------------- */
$arResult =
	[
	"value"       => $arParams["VALUE"],
	"input_name"  => $arParams["INPUT_NAME"],

	"cols"        => $cols,
	"rows"        => $rows,
	"placeholder" => $arParams["PLACEHOLDER"],
	"attr"        => $arParams["ATTR"]
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate(); 
?>