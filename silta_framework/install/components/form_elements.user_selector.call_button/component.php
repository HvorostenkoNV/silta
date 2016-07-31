<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_framework"))                  return ShowError("module silta_framework required!");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

CHECKED_USERS       - выбранные юзеры
CHECKED_DEPARTMENTS - выбранные отделы
INPUT_NAME          - имя поля

USERS               - выбор юзеров Y/N
DEPARTMENTS         - выбор отделов Y/N
START_ROOTS         - массив стартовых отделов (для постройки дерева в селекторе)
MULTIPLY            - множ.выбор элементов Y/N
ATTR                - аттрибуты
*/
/* -------------------------------------------------------------------- */
/* -------------------------- корректировки --------------------------- */
/* -------------------------------------------------------------------- */
$arParams["CHECKED_USERS"]       = SgetClearArray($arParams["CHECKED_USERS"]);
$arParams["CHECKED_DEPARTMENTS"] = SgetClearArray($arParams["CHECKED_DEPARTMENTS"]);
$arParams["START_ROOTS"]         = SgetClearArray($arParams["START_ROOTS"]);

if(!in_array($arParams["USERS"],       ["Y", "N"])) $arParams["USERS"]       = 'Y';
if(!in_array($arParams["DEPARTMENTS"], ["Y", "N"])) $arParams["DEPARTMENTS"] = 'N';
if(!in_array($arParams["MULTIPLY"],    ["Y", "N"])) $arParams["MULTIPLY"]    = 'N';
if($arParams["MULTIPLY"] == 'Y')                    $arParams["INPUT_NAME"] .= '[]';
/* -------------------------------------------------------------------- */
/* ----------------------- параметры для шаблона ---------------------- */
/* -------------------------------------------------------------------- */
$arResult =
	[
	"checked_users"       => $arParams["CHECKED_USERS"],
	"checked_departments" => $arParams["CHECKED_DEPARTMENTS"],
	"input_name"          => $arParams["INPUT_NAME"],

	"users"               => $arParams["USERS"],
	"departments"         => $arParams["DEPARTMENTS"],
	"start_roots"         => $arParams["START_ROOTS"],
	"multiply"            => $arParams["MULTIPLY"],
	"attr"                => $arParams["ATTR"]
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate(); 
?>