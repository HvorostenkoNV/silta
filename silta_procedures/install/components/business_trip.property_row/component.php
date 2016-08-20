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
$elementObject = $propertyObject->GetElementObject();
// интервалы дат
foreach
	(
		[
			[
			"row_name"   => 'trip_interval',
			"row_title"  => GetMessage("SP_BTR_TRIP_INTERVAL_TITLE"),
			"start_prop" => 'trip_start_date',
			"end_prop"   => 'trip_end_date'
			],
			[
			"row_name"   => 'hotel_interval',
			"row_title"  => GetMessage("SP_BTR_HOTEL_INTERVAL_TITLE"),
			"start_prop" => 'hotel_start_date',
			"end_prop"   => 'hotel_end_date'
			]
		]
	as $arrayInfo
	)
	if($propertyObject->GetName() == $arrayInfo["start_prop"] || $propertyObject->GetName() == $arrayInfo["end_prop"])
		{
		$arParams["ROW_PARAMS"]["NAME"]  = $arrayInfo["row_name"];
		$arParams["ROW_PARAMS"]["TITLE"] = $arrayInfo["row_title"];
		if
			(
			$elementObject->GetProperty($arrayInfo["start_prop"])->GetAttributes()["required"] == 'on'
			&&
			$elementObject->GetProperty($arrayInfo["end_prop"])->GetAttributes()["required"] == 'on'
			)
			$arParams["ROW_PARAMS"]["REQUIRED"] = 'on';
		}
// скрытые строки свойств "проживание"
if
	(
	in_array($propertyObject->GetName(), ["hotel_start_date", "hotel_end_date", "hotel_day_cost", "hotel_comments"])
	&&
	$elementObject->GetProperty("hotel_need")->GetValue() == 'N'
	)
	$arParams["ROW_PARAMS"]["HIDDEN"] = 'Y';
// блок "билеты"
if(in_array($propertyObject->GetName(), ["ticket_name", "ticket_date", "ticket_cost"]))
	$arParams["ROW_PARAMS"]["TITLE"] = GetMessage("SP_BTR_TICKETS_ROW_TITLE");
/* -------------------------------------------------------------------- */
/* ----------------------- параметры для шаблона ---------------------- */
/* -------------------------------------------------------------------- */
$arResult =
	[
	"property_object" => $propertyObject,
	"field_type"      => $arParams["FIELD_TYPE"],
	"field_params"    => $arParams["FIELD_PARAMS"],
	"row_params"      => $arParams["ROW_PARAMS"]
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>