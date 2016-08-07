<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_framework"))                  return ShowError("module silta_framework required!");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

PROPERTY_OBJECT          - объект свойства элемента (SDBElementProperty)
FIELD_TYPE               - тип поля read/write
FIELD_PARAMS             - массив параметров поля
ROW_PARAMS               - массив параметров строки

FIELD_COMPONENT_NAME     - имя компонента "поле свойства". По умолчанию - silta_framework:form_elements.property_field
FIELD_COMPONENT_TEMPLATE - имя шаблона компонента. По умолчанию - .default
FIELD_COMPONENT_PARAMS   - массив настроек для компонента
*/
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
// объект свойства
$propertyObject = $arParams["PROPERTY_OBJECT"];
if($propertyObject && !is_subclass_of($propertyObject, 'SDBElementProperty')) return;
// параметры компонента ячейки свойства
if(!$arParams["FIELD_COMPONENT_NAME"])     $arParams["FIELD_COMPONENT_NAME"]     = 'silta_framework:form_elements.property_field';
if(!$arParams["FIELD_COMPONENT_TEMPLATE"]) $arParams["FIELD_COMPONENT_TEMPLATE"] = '.default';
if(!$arParams["FIELD_COMPONENT_PARAMS"])   $arParams["FIELD_COMPONENT_PARAMS"]   =
	[
	"FIELD_TYPE"      => $arParams["FIELD_TYPE"],
	"PROPERTY_OBJECT" => $arParams["PROPERTY_OBJECT"],
	"FIELD_PARAMS"    => $arParams["FIELD_PARAMS"]
	];
// свойство участвует в сохранении
$formSaving = $arParams["ROW_PARAMS"]["FORM_SAVING"];
if(!in_array($formSaving, ["on", "off"])) $formSaving = 'on';
if($arParams["FIELD_TYPE"] == 'read') unset($formSaving);
// свойство скрыто
$propHidden = false;
if($arParams["ROW_PARAMS"]["HIDDEN"] == 'Y') $propHidden = true;
// свойство обязательно к заполнению
$propRequired = $arParams["ROW_PARAMS"]["REQUIRED"];
if(!$propRequired && $propertyObject) $propRequired = $propertyObject->GetAttributes()["required"];
if(!in_array($propRequired, ["on", "off"]) || $arParams["FIELD_TYPE"] == 'read') unset($propRequired);
if($propRequired == 'on' && $propHidden) $propRequired = 'off';
// пустая строка сверху/снизу
$rowSpace = $arParams["ROW_PARAMS"]["SPACE"];
if(!in_array($rowSpace, ["top", "bottom"])) unset($rowSpace);
// титул строки
$rowTitle = $arParams["ROW_PARAMS"]["TITLE"];
if(!$rowTitle && $propertyObject) $rowTitle = $propertyObject->GetAttributes()["title"];
// имя строки
$rowName = $arParams["ROW_PARAMS"]["NAME"];
if(!$rowName && $propertyObject) $rowName = $propertyObject->GetName();
if(!$rowName)                    $rowName = 'property_row'.rand();
/* -------------------------------------------------------------------- */
/* ----------------------- параметры для шаблона ---------------------- */
/* -------------------------------------------------------------------- */
$arResult =
	[
	"field_component_name"     => $arParams["FIELD_COMPONENT_NAME"],
	"field_component_template" => $arParams["FIELD_COMPONENT_TEMPLATE"],
	"field_component_params"   => $arParams["FIELD_COMPONENT_PARAMS"],

	"row_title"                => $rowTitle,
	"prop_name"                => $rowName,
	"form_saving"              => $formSaving,
	"required"                 => $propRequired,
	"attr"                     => $arParams["ROW_PARAMS"]["ATTR"],
	"space"                    => $rowSpace,
	"hidden"                   => $propHidden
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>