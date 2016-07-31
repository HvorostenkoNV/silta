<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_procedures"))                 return ShowError("modules required: silta_procedures");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

ELEMENT_OBJECT - объект элемента
*/
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$FixedAssetsWork = SProceduresFixedAssetsWork::GetInstance();
// объект элемента
$procedureElement = $arParams["ELEMENT_OBJECT"];
if(!$procedureElement) return ShowError(GetMessage("SF_ELEMENT_NOT_EXIST"));
// заявка на обеспечение
if($procedureElement->GetElementId() == 'new' && $_GET["provision_application"])
	{
	$provisionApplicationTable = $FixedAssetsWork->GetTable("provision_application");
	if($provisionApplicationTable) $provisionApplicationObject = $provisionApplicationTable->GetElement($_GET["provision_application"]);
	if($provisionApplicationObject && in_array(CUser::GetId(), $provisionApplicationObject->GetResponsibles()))
		$procedureElement->GetProperty("provision_application")->SetValue($provisionApplicationObject->GetElementId());
	}
// имена элементов форм
$inputesName =
	[
	"main_form_prefix" => 'sp-faw-daf',
	"main_form_submit" => 'sp-faw-daf-submit-'.$procedureElement->GetElementId(),
	];
/* -------------------------------------------------------------------- */
/* -------------------- обработчик формы элемента --------------------- */
/* -------------------------------------------------------------------- */
if(is_set($_POST[$inputesName["main_form_submit"]]))
	{
	$propsSave = [];
	$formValue = $_POST[$inputesName["main_form_prefix"]];
	// переданные файлы
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
		$fixedAssetsTable = SCompanyTables::GetInstance()->GetTable("fixed_assets");
		$fixedAssetsValue = $procedureElement->GetProperty("fixed_asset")->GetValue();
		if($fixedAssetsTable) $fixedAssetsElement = $fixedAssetsTable->GetElement($fixedAssetsValue);
		if($fixedAssetsElement) $procedureElement->GetProperty("name")->SetValue($fixedAssetsElement->GetProperty("name")->GetValue().' - '.date('d.m.Y'));
		$procedureElement->GetProperty("stage")->SetValue("start");
		foreach(["name", "stage", "provision_application"] as $property) $propsSave[] = $property;
		}
	// сохранение
	if(count($propsSave)) $savingResult = $procedureElement->SaveElement($propsSave);
	if($savingResult && $procedureElement->GetProperty("stage")->GetValue() == 'start') $procedureElement->ChangeStage("send_to_1c");
	LocalRedirect($FixedAssetsWork->GetComponentUrl().'displacement_application/'.$procedureElement->GetElementId().'/');
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
// ссылка на процедуру заказа
$provisionApplicationLink = false;
if($procedureElement->GetProperty("provision_application")->GetValue())
	$provisionApplicationLink = $FixedAssetsWork->GetComponentUrl().'provision_application/'.$procedureElement->GetProperty("provision_application")->GetValue().'/';
// основная форма
$mainFormProps = ["read" => [], "write" => []];
if($procedureElement->GetElementId() != 'new') $mainFormProps["read"][]  = $procedureElement->GetProperty("created_by");
foreach(["fixed_asset", "new_user", "text"] as $property)
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
	"form_props"       =>
		[
		"read"  => $mainFormProps["read"],
		"write" => $mainFormProps["write"]
		],
	"input_name"       =>
		[
		"main_form" => $inputesName["main_form_prefix"]
		],
	"links"            =>
		[
		"provision_application" => $provisionApplicationLink
		],
	"button_names"     =>
		[
		"main_form_submit" => $inputesName["main_form_submit"]
		]
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>