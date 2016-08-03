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
	/*
	$propsSave = [];
	$formValue = $_POST[$inputesName["main_form_prefix"]];
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
		$procedureElement->GetProperty("name") ->SetValue($procedureElement->GetProperty("department")->GetValue("title").' - '.date('d.m.Y'));
		$procedureElement->GetProperty("stage")->SetValue("start");
		foreach(["name", "stage"] as $property) $propsSave[] = $property;
		}
	// сохранение
	$savingResult = $procedureElement->SaveElement($propsSave);
	if($savingResult && $procedureElement->GetProperty("created_by")->GetValue() == $USER->GetID() && $procedureElement->GetProperty("stage")->GetValue() == 'start')
		$procedureElement->ChangeStage("agreement");

	if($arParams["SAVE_REDIRECT"]) LocalRedirect(str_replace('#ELEMENT_ID#', $procedureElement->GetElementId(), $arParams["SAVE_REDIRECT"]));
	else                           LocalRedirect($APPLICATION->GetCurPage());
	*/
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
foreach(["trip_start_date", "trip_end_date", "trip_description", "path_description", "wishes_description", "hotel_need", "hotel_start_date", "hotel_end_date"] as $property)
	{
	$propertyObject = $procedureElement->GetProperty($property);
	if($procedureElement->GetElementId() != 'new')                                   $mainFormProps["read"][]  = $propertyObject;
	if($procedureElement->GetAccess("write") && $propertyObject->GetAccess("write")) $mainFormProps["write"][] = $propertyObject;
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