<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_framework"))                  return ShowError("module silta_framework required!");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

VALUE      - значение
INPUT_NAME - имя поля

TABLE      - ИД инфоблока
PROPS      - массив свойств для формы выбора
FILTER     - примененный фильтр
MULTIPLY   - множ.выбор элементов Y/N

ATTR       - аттрибуты
*/
/* -------------------------------------------------------------------- */
/* -------------------------- корректировки --------------------------- */
/* -------------------------------------------------------------------- */
$arParams["PROPS"] = SgetClearArray($arParams["PROPS"]);
$arParams["VALUE"] = SgetClearArray($arParams["VALUE"]);
if(!in_array($arParams["MULTIPLY"], ["Y", "N"])) $arParams["MULTIPLY"]    = 'N';
if($arParams["MULTIPLY"] == 'Y')                 $arParams["INPUT_NAME"] .= '[]';
/* -------------------------------------------------------------------- */
/* ----------------------- параметры для шаблона ---------------------- */
/* -------------------------------------------------------------------- */
$arResult =
	[
	"value"      => $arParams["VALUE"],
	"input_name" => $arParams["INPUT_NAME"],

	"table"      => $arParams["TABLE"],
	"props"      => $arParams["PROPS"],
	"filter"     => $arParams["FILTER"],
	"multiply"   => $arParams["MULTIPLY"],

	"attr"       => $arParams["ATTR"]
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate(); 
?>