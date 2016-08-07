<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_procedures"))                 return ShowError("modules required: silta_procedures");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

ELEMENT_OBJECT  - объект элемента
SAVE_REDIRECT   - редирект при сохранении (путь)
DELETE_REDIRECT - редирект при удалении (путь)
*/
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$procedureElement = $arParams["ELEMENT_OBJECT"];
if(!$procedureElement) return ShowError(GetMessage("SF_ELEMENT_NOT_EXIST"));
// имена элементов форм
$inputesName =
	[
	"main_form_prefix"         => 'sp-btr-main-info',
	"boss_confirm_form_prefix" => 'sp-btr-boss-confirm',
	"assist_form_prefix"       => 'sp-btr-assist-info',
	"main_form_submit"         => 'sp-btr-main-info-submit-'.$procedureElement->GetElementId(),
	"boss_confirm_form_submit" => 'sp-btr-boss-confirm-info-submit-'.$procedureElement->GetElementId(),
	"assist_form_submit"       => 'sp-btr-assist-info-submit-'.$procedureElement->GetElementId(),
	"assist_form_send"         => 'sp-btr-assist-send-'.$procedureElement->GetElementId()
	];
/* -------------------------------------------------------------------- */
/* -------------------- обработчик формы элемента --------------------- */
/* -------------------------------------------------------------------- */
if(is_set($_POST[$inputesName["main_form_submit"]]))
	{
	$propsSave       = [];
	$formValue       = $_POST[$inputesName["main_form_prefix"]];
	$applicationLink = '';
	// переданные параметры
	foreach($_FILES[$inputesName["main_form_prefix"]]["name"] as $property => $infoArray)
		foreach($infoArray["new"] as $index => $name)
			$formValue[$property]["new"][] =
				[
				"name"     => $name,
				"tmp_name" => $_FILES[$inputesName["main_form_prefix"]]["tmp_name"][$property]["new"][$index],
				];
	// утсановка переданных параметров
	foreach($formValue as $property => $value)
		{
		$propertyObject = $procedureElement->GetProperty($property);
		if(!$propertyObject) continue;
		$propertyObject->SetValue($value, "form");
		$propsSave[] = $property;
		}
	// создание элемента
	if($procedureElement->GetElementId() == 'new')
		{
		$procedureElement->GetProperty("name") ->SetValue($procedureElement->GetProperty("user_department")->GetValue("title").' - '.date('d.m.Y'));
		$procedureElement->GetProperty("stage")->SetValue("creating");
		foreach(["name", "stage"] as $property) $propsSave[] = $property;
		}
	// корректировки
	if($procedureElement->GetProperty("hotel_need")->GetValue() == 'N')
		foreach(["hotel_start_date", "hotel_end_date"] as $property)
			{
			$procedureElement->GetProperty($property)->UnsetValue();
			if(!in_array($property, $propsSave)) $propsSave[] = $property;
			}

	foreach([["hotel_start_date", "hotel_end_date"], ["trip_start_date", "trip_end_date"]] as $propertyArray)
		{
		$startDates = SgetClearArray($procedureElement->GetProperty($propertyArray[0])->GetValue());
		$endDates   = SgetClearArray($procedureElement->GetProperty($propertyArray[1])->GetValue());
		if(!count($startDates) || !count($endDates)) continue;

		foreach($startDates as $index => $value)
			if(!$endDates[$index])
				unset($startDates[$index]);
		$procedureElement->GetProperty($propertyArray[0])->SetValue($startDates);
		$procedureElement->GetProperty($propertyArray[1])->SetValue($endDates);
		}
	// сохранение
	$savingResult = $procedureElement->SaveElement($propsSave);
	if($arParams["SAVE_REDIRECT"]) $applicationLink = str_replace('#ELEMENT_ID#', $procedureElement->GetElementId(), $arParams["SAVE_REDIRECT"]);
	else                           $applicationLink = $APPLICATION->GetCurPage();

	if($savingResult && $procedureElement->GetProperty("created_by")->GetValue() == $USER->GetID() && $procedureElement->GetProperty("stage")->GetValue() == 'creating')
		$procedureElement->ChangeStage("agreement", $applicationLink);

	LocalRedirect($applicationLink);
	}
/* -------------------------------------------------------------------- */
/* -------------------- готовый массив для шаблона -------------------- */
/* -------------------------------------------------------------------- */
// новый элемент
$elementNew = false;
if($procedureElement->GetElementId() == 'new') $elementNew = true;
// процедура закрыта
$procedureClosed = false;
if($procedureElement->GetProperty("active")->GetValue() == 'N') $procedureClosed = true;
// основная форма
$mainFormProps = ["read" => [], "write" => []];
if($procedureElement->GetElementId() != 'new') $mainFormProps["read"]["created_by"] = $procedureElement->GetProperty("created_by");
foreach(["user_department", "trip_start_date", "trip_end_date", "trip_description", "path_description", "wishes_description", "hotel_need", "hotel_start_date", "hotel_end_date"] as $property)
	{
	$propertyObject = $procedureElement->GetProperty($property);
	if($procedureElement->GetElementId() != 'new')                                   $mainFormProps["read"] [$property] = $propertyObject;
	if($procedureElement->GetAccess("write") && $propertyObject->GetAccess("write")) $mainFormProps["write"][$property] = $propertyObject;
	}
// готовый массив
$arResult =
	[
	"new_element"      => $elementNew,
	"procedure_closed" => $procedureClosed,
	"main_form_props"  =>
		[
		"read"  => $mainFormProps["read"],
		"write" => $mainFormProps["write"]
		],
	"input_name"       =>
		[
		"main_form"         => $inputesName["main_form_prefix"],
		"boss_confirm_form" => $inputesName["boss_confirm_form_prefix"],
		"assist_form"       => $inputesName["assist_form_prefix"]
		],
	"button_names"     =>
		[
		"main_form_submit"         => $inputesName["main_form_submit"],
		"boss_confirm_form_submit" => $inputesName["boss_confirm_form_submit"],
		"assist_form_submit"       => $inputesName["assist_form_submit"],
		"assist_form_send"         => $inputesName["assist_form_send"]
		]
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>