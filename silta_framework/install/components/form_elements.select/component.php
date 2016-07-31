<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_framework"))                  return ShowError("module silta_framework required!");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

VALUE       - значение
INPUT_NAME  - имя поля
LIST        - массив вариантов (код - титул)

EMPTY_VALUE - селект имеет пустое значение Y/N
WIDTH       - размер поля
ATTR        - аттрибуты
*/
/* -------------------------------------------------------------------- */
/* -------------------------- корректировки --------------------------- */
/* -------------------------------------------------------------------- */
$emptyValue = true;
if($arParams["EMPTY_VALUE"] == 'N') $emptyValue = false;

if(!$arParams["WIDTH"]) $arParams["WIDTH"] = '250';
foreach($arParams["LIST"] as $index => $value)
	{
	$listInfo = ["value" => $index, "title" => htmlspecialchars_decode($value)];
	if($index == $arParams["VALUE"]) $listInfo["checked"] = true;
	$listArray[] = $listInfo;
	}
/* -------------------------------------------------------------------- */
/* ----------------------- параметры для шаблона ---------------------- */
/* -------------------------------------------------------------------- */
$arResult =
	[
	"input_name"  => $arParams["INPUT_NAME"],
	"list"        => $listArray,

	"empty_value" => $emptyValue,
	"width"       => $arParams["WIDTH"],
	"attr"        => $arParams["ATTR"]
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate(); 
?>