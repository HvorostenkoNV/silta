<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_procedures"))                 return ShowError("modules required: silta_procedures");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

FIELD_TYPE       - тип поля read/write
PROPERTY_START   - свойство "начало периода"
PROPERTY_END     - свойство "окончание периода"
INPUT_NAME_START - имя поля "начало"
INPUT_NAME_END   - имя поля "окончание"
*/
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$startPropertyObject = $arParams["PROPERTY_START"];
$endPropertyObject   = $arParams["PROPERTY_END"];
if(!is_subclass_of($startPropertyObject, 'SDBElementProperty') || !is_subclass_of($endPropertyObject, 'SDBElementProperty')) return;
// значения
$valueArray =
	[
	"start" => SgetClearArray($startPropertyObject->GetValue()),
	"end"   => SgetClearArray($endPropertyObject  ->GetValue())
	];
$intervalsValue = [];
foreach($valueArray["start"] as $index => $date)
	if($valueArray["end"][$index])
		$intervalsValue[] =
			[
			"start" => $date,
			"end"   => $valueArray["end"][$index]
			];
/* -------------------------------------------------------------------- */
/* ------------------------------ чтение ------------------------------ */
/* -------------------------------------------------------------------- */
if($arParams["FIELD_TYPE"] == 'read')
	{
	$arResult = ["type" => 'read'];
	foreach($intervalsValue as $dates)
		{
		$count = (strtotime($dates["end"]) - strtotime($dates["start"]))/86400;
		if(!$count || $startPropertyObject->GetName() != 'hotel_start_date') $count++;
		$arResult["value"][] =
			[
			"start" => $dates["start"],
			"end"   => $dates["end"],
			"count" => $count
			];
		}
	}
/* -------------------------------------------------------------------- */
/* ------------------------------ запись ------------------------------ */
/* -------------------------------------------------------------------- */
if($arParams["FIELD_TYPE"] == 'write')
	{
	// значения
	$value = $intervalsValue;
	if(!count($value)) $value = [["start" => '', "end" => '']];
	// готовый массив
	$arResult =
		[
		"type"             => 'write',
		"value"            => $value,
		"input_name_start" => $arParams["INPUT_NAME_START"].'[]',
		"input_name_end"   => $arParams["INPUT_NAME_END"].'[]'
		];
	}
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>