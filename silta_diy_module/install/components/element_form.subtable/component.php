<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_diy_module"))                 return ShowError("modules required: silta_diy_module");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

ELEMENT_OBJECT    - объект элемента
SUBTABLE          - субтаблица

FORM_PROPS        - массив свойств формы
FORM_PROPS_BUFFER - пространнство после свойст формы
LINKS             - ссылки
*/
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$elementObject  = $arParams["ELEMENT_OBJECT"];
if(!is_subclass_of($elementObject, 'SDBElement') || $elementObject->GetElementId() == 'new') return;
$subTableObject = SDiyModule::GetInstance()->GetTable($arParams["SUBTABLE"]);
if(!$subTableObject) return;
// связующее свойство
foreach($subTableObject->GetPropertyList() as $property => $propertyObject)
	if($propertyObject->GetType() == 'list_element' && $propertyObject->GetAttributes()["table"] == $elementObject->GetTableObject()->GetIblockId())
		$connectProperty = $property;
if(!$connectProperty) return;
// выборка
foreach($subTableObject->GetQuery([], [$connectProperty => $elementObject->GetElementId()]) as $elementId)
	{
	$queryElementObject = $subTableObject->GetElement($elementId);
	$workElementObjects[$queryElementObject->GetProperty("name")->GetValue()] = $queryElementObject;
	}
// новый элемент
$newElementObject = false;
if($subTableObject->GetAccess("create_element"))
	{
	$newElementObject = $subTableObject->GetElement("new");
	if($newElementObject) $newElementObject->GetProperty($connectProperty)->SetValue($elementObject->GetElementId());
	}
// свойства форм
$formProps = $arParams["FORM_PROPS"];
if(!in_array($connectProperty, $formProps)) $formProps[] = $connectProperty;
/* -------------------------------------------------------------------- */
/* ----------------------- параметры для шаблона ---------------------- */
/* -------------------------------------------------------------------- */
$arResult =
	[
	"work_table"        => ToUpper($arParams["SUBTABLE"]),
	"elements"          => $workElementObjects,
	"new_element"       => $newElementObject,
	"form_props"        => $formProps,
	"form_props_buffer" => $arParams["FORM_PROPS_BUFFER"],
	"links"             => $arParams["LINKS"],
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>