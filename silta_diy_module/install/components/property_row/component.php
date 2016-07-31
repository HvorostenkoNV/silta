<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_diy_module"))                 return ShowError("modules required: silta_diy_module");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

WORK_TABLE      - имя рабочей таблицы
FIELD_TYPE      - тип поля
LINKS           - ссылки
PROPERTY_OBJECT - объект свойства
FIELD_PARAMS    - настройки поля
ROW_PARAMS      - настройки строки
INPUT_NAME      - базовое имя свойстваств фильтра
*/
/* -------------------------------------------------------------------- */
/* ----------------------- параметры для шаблона ---------------------- */
/* -------------------------------------------------------------------- */
$arResult =
	[
	"work_table"      => $arParams["WORK_TABLE"],      // имя рабочей таблицы
	"field_type"      => $arParams["FIELD_TYPE"],      // тип поля
	"links"           => $arParams["LINKS"],           // ссылки
	"property_object" => $arParams["PROPERTY_OBJECT"], // объект свойства
	"field_params"    => $arParams["FIELD_PARAMS"],    // настройки поля
	"row_params"      => $arParams["ROW_PARAMS"],      // настройки строки
	"input_name"      => $arParams["INPUT_NAME"]       // базовое имя свойстваств фильтра
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>