<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_framework"))                  return ShowError("module silta_framework required!");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

PROPERTY_OBJECT - объект свойства элемента (SDBElementProperty)
FIELD_TYPE      - тип поля read/write
FIELD_PARAMS    - массив параметров поля
*/
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$propertyObject = $arParams["PROPERTY_OBJECT"];
if(!is_subclass_of($propertyObject, 'SDBElementProperty')) return;
$elementObject = $propertyObject->GetElementObject();
/* -------------------------------------------------------------------- */
/* --------------------------- интервалы дат -------------------------- */
/* -------------------------------------------------------------------- */
if(in_array($propertyObject->GetName(), ["trip_start_date", "trip_end_date", "hotel_start_date", "hotel_end_date"]))
	{
	$intervalType = '';
	if(in_array($propertyObject->GetName(), ["trip_start_date",  "trip_end_date"]))  $intervalType = 'trip';
	if(in_array($propertyObject->GetName(), ["hotel_start_date", "hotel_end_date"])) $intervalType = 'hotel';
	// чтение
	if($arParams["FIELD_TYPE"] == 'read')
		$arResult =
			[
			"cell_type"  => 'date_interval',
			"field_type" => 'read',
			"value"      => $elementObject->GetDatesInterval($intervalType)
			];
	// запись
	if($arParams["FIELD_TYPE"] == 'write')
		{
		$value = $elementObject->GetDatesInterval($intervalType);
		if(!count($value)) $value = [["start" => '', "end" => '']];
		$arResult =
			[
			"cell_type"        => 'date_interval',
			"field_type"       => 'write',
			"value"            => $value,
			"input_name_start" => $arParams["FIELD_PARAMS"]["INPUT_NAME_START"].'[]',
			"input_name_end"   => $arParams["FIELD_PARAMS"]["INPUT_NAME_END"].'[]'
			];
		}
	}
/* -------------------------------------------------------------------- */
/* ------------------------- стандартное поле ------------------------- */
/* -------------------------------------------------------------------- */
if(!count($arResult))
	$arResult =
		[
		"cell_type"       => 'standart',
		"property_object" => $propertyObject,
		"field_type"      => $arParams["FIELD_TYPE"],
		"field_params"    => $arParams["FIELD_PARAMS"]
		];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>