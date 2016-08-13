<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_framework"))                  return ShowError("module silta_framework required!");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

PROPERTY_OBJECT - объект свойства элемента (SDBElementProperty)
FIELD_TYPE      - тип поля read/write
FIELD_PARAMS    - массив параметров поля
ROW_PARAMS      - массив параметров строки
*/
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$propertyObject = $arParams["PROPERTY_OBJECT"];
if($propertyObject && !is_subclass_of($propertyObject, 'SDBElementProperty')) return;
// интервалы дат
foreach(["trip_interval" => ["trip_start_date", "trip_end_date"], "hotel_interval" => ["hotel_start_date", "hotel_end_date"]] as $rowName => $propsArray)
	if(in_array($propertyObject->GetName(), $propsArray))
		{
		$arParams["ROW_PARAMS"]["NAME"] = $rowName;
		$startDatePropertyObject = $propertyObject->GetElementObject()->GetProperty($propsArray[0]);
		$endDatePropertyObject   = $propertyObject->GetElementObject()->GetProperty($propsArray[1]);
		if($startDatePropertyObject->GetAttributes()["required"] == 'on' && $endDatePropertyObject->GetAttributes()["required"] == 'on')
			$arParams["ROW_PARAMS"]["REQUIRED"] = 'on';
		}
// скрытая строка дат проживания
if(in_array($propertyObject->GetName(), ["hotel_start_date", "hotel_end_date"]) && $propertyObject->GetElementObject()->GetProperty("hotel_need")->GetValue() == 'N')
	$arParams["ROW_PARAMS"]["HIDDEN"] = 'Y';
/* -------------------------------------------------------------------- */
/* ----------------------- параметры для шаблона ---------------------- */
/* -------------------------------------------------------------------- */
$arResult =
	[
	"property_object" => $arParams["PROPERTY_OBJECT"],
	"field_type"      => $arParams["FIELD_TYPE"],
	"field_params"    => $arParams["FIELD_PARAMS"],
	"row_params"      => $arParams["ROW_PARAMS"]
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>