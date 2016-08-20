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

foreach(["write", "delete"] as $type) echo $procedureElement->GetAccess($type).'#<br>';
echo '<br>------- <b>write</b> --------<br>';
foreach($procedureElement->GetPropertyList() as $obj)
	if($obj->GetAccess("write"))
		echo $obj->GetName().'<br>';
echo '<br>------- <b>NOT</b> --------<br>';
foreach($procedureElement->GetPropertyList() as $obj)
	if(!$obj->GetAccess("write"))
		echo $obj->GetName().'<br>';
// новый элемент
if($procedureElement->GetElementId() == 'new')
	{
	$departmentsList = [];
	foreach(SProceduresBusinessTrip::GetInstance()->GetUserDepartments() as $departmentId)
		$departmentsList[] =
			[
			"value" => $departmentId,
			"code"  => $departmentId,
			"title" => (new SCompanyDepartment(["id" => $departmentId]))->GetName()
			];
	$procedureElement->GetProperty("user_department")->ChangeType("list")->SetAttributes(["list" => $departmentsList]);
	$procedureElement->GetProperty("hotel_need")->SetValue("Y");
	}
// имена элементов форм
$inputesName =
	[
	"main_form_prefix"      => 'sp-btr-main-info',
	"boss_sign_form_prefix" => 'sp-btr-boss-sign',
	"assist_form_prefix"    => 'sp-btr-assist-info',

	"main_form_submit"   => 'sp-btr-main-info-submit-'.$procedureElement->GetElementId(),
	"boss_sign_confirm"  => 'sp-btr-boss-confirm-submit-'.$procedureElement->GetElementId(),
	"boss_sign_reject"   => 'sp-btr-boss-reject-submit-'.$procedureElement->GetElementId(),
	"boss_sign_return"   => 'sp-btr-boss-return-submit-'.$procedureElement->GetElementId(),
	"assist_form_submit" => 'sp-btr-assist-info-submit-'.$procedureElement->GetElementId(),
	"assist_app_send"    => 'sp-btr-assist-send-'.$procedureElement->GetElementId()
	];
/* -------------------------------------------------------------------- */
/* ------------------ обработчик формы основной инфы ------------------ */
/* -------------------------------------------------------------------- */
if(is_set($_POST[$inputesName["main_form_submit"]]))
	{
	$propsSave       = [];                                       // массив свойств, которые необходимо записать
	$formValue       = $_POST[$inputesName["main_form_prefix"]]; // переданные данные формы
	$applicationLink = '';                                       // ссылка на страницу после записи
	$savingResult    = false;                                    // результат записи
	// переданные файлы
	foreach($_FILES[$inputesName["main_form_prefix"]]["name"] as $property => $infoArray)
		foreach($infoArray["new"] as $index => $name)
			$formValue[$property]["new"][] =
				[
				"name"     => $name,
				"tmp_name" => $_FILES[$inputesName["main_form_prefix"]]["tmp_name"][$property]["new"][$index],
				];
	// утсановка переданных данных формы
	foreach($formValue as $property => $value)
		{
		$propertyObject = $procedureElement->GetProperty($property);
		if(!$propertyObject) continue;
		$propertyObject->SetValue($value, "form");
		$propsSave[] = $property;
		}
	// при создании нового элемента
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
			$propsSave[] = $property;
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
	// запись
	if(count($propsSave)) $savingResult = $procedureElement->SaveElement($propsSave);
	if(!$savingResult) LocalRedirect($APPLICATION->GetCurPage());

	if($arParams["SAVE_REDIRECT"]) $applicationLink = str_replace('#ELEMENT_ID#', $procedureElement->GetElementId(), $arParams["SAVE_REDIRECT"]);
	else                           $applicationLink = $APPLICATION->GetCurPage();

	if($procedureElement->GetProperty("created_by")->GetValue() == $USER->GetID() && $procedureElement->GetStage() == 'start')
		$procedureElement->ChangeStage("boss_agreement", $applicationLink);

	LocalRedirect($applicationLink);
	}
/* -------------------------------------------------------------------- */
/* --------------------- обработчик согласования ---------------------- */
/* -------------------------------------------------------------------- */
if
	(
	is_set($_POST[$inputesName["boss_sign_confirm"]])
	||
	is_set($_POST[$inputesName["boss_sign_reject"]])
	||
	is_set($_POST[$inputesName["boss_sign_return"]])
	)
	{
	$propsSave = [];                                            // массив свойств, которые необходимо записать
	$formValue = $_POST[$inputesName["boss_sign_form_prefix"]]; // переданные данные формы
	// переданные файлы
	foreach($_FILES[$inputesName["boss_sign_form_prefix"]]["name"] as $property => $infoArray)
		foreach($infoArray["new"] as $index => $name)
			$formValue[$property]["new"][] =
				[
				"name"     => $name,
				"tmp_name" => $_FILES[$inputesName["boss_sign_form_prefix"]]["tmp_name"][$property]["new"][$index],
				];
	// утсановка переданных данных формы
	foreach($formValue as $property => $value)
		{
		$propertyObject = $procedureElement->GetProperty($property);
		if(!$propertyObject) continue;
		$propertyObject->SetValue($value, "form");
		$propsSave[] = $property;
		}
	// запись
	if(count($propsSave)) $savingResult = $procedureElement->SaveElement($propsSave);
	if(is_set($_POST[$inputesName["boss_sign_reject"]]))  $procedureElement->ChangeStage("close",            $APPLICATION->GetCurPage());
	if(is_set($_POST[$inputesName["boss_sign_return"]]))  $procedureElement->ChangeStage("start",            $APPLICATION->GetCurPage());
	if(is_set($_POST[$inputesName["boss_sign_confirm"]])) $procedureElement->ChangeStage("assist_user_work", $APPLICATION->GetCurPage());
	LocalRedirect($APPLICATION->GetCurPage());
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
	if($procedureElement->GetElementId() != 'new') $mainFormProps["read"] [$property] = $propertyObject;
	if($propertyObject->GetAccess("write"))        $mainFormProps["write"][$property] = $propertyObject;
	}
// примечания к доработке
$alertProps = [];
foreach(["returned_text", "returned_files"] as $property)
	{
	$propertyObject = $procedureElement->GetProperty($property);
	$propertyObject->GetValue();
	if($propertyObject->GetValueParams()["value_geted"]) $alertProps[] = $propertyObject;
	}
// форма согласования
$signFormProps = [];
if($procedureElement->GetStage() == 'boss_agreement')
	foreach(["returned_text", "returned_files"] as $property)
		if($procedureElement->GetProperty($property)->GetAccess("write"))
			$signFormProps[$property] = $procedureElement->GetProperty($property);
// форма доп.инфы
$specialInfoProps = ["read" => [], "write" => []];
if(!in_array($procedureElement->GetStage(), ["start", "boss_agreement"]))
	foreach(["trip_day_cost", "hotel_day_cost", "hotel_comments", "trip_files", "ticket_name", "ticket_date", "ticket_cost"] as $property)
		{
		$propertyObject = $procedureElement->GetProperty($property);
		$specialInfoProps["read"][$property] = $propertyObject;
		if($propertyObject->GetAccess("write")) $specialInfoProps["write"][$property] = $propertyObject;
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
	"add_form_props"   =>
		[
		"read"  => $specialInfoProps["read"],
		"write" => $specialInfoProps["write"]
		],
	"alert_props"      => $alertProps,
	"sign_form_props"  => $signFormProps,
	"input_name"       =>
		[
		"main_form"   => $inputesName["main_form_prefix"],
		"sign_form"   => $inputesName["boss_sign_form_prefix"],
		"assist_form" => $inputesName["assist_form_prefix"]
		],
	"button_names"     =>
		[
		"main_form_submit"   => $inputesName["main_form_submit"],
		"boss_sign_confirm"  => $inputesName["boss_sign_confirm"],
		"boss_sign_reject"   => $inputesName["boss_sign_reject"],
		"boss_sign_return"   => $inputesName["boss_sign_return"],
		"assist_form_submit" => $inputesName["assist_form_submit"],
		"assist_app_send"    => $inputesName["assist_app_send"]
		]
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>