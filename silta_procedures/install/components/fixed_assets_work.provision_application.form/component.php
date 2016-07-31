<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_procedures"))                 return ShowError("modules required: silta_procedures");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

ELEMENT_OBJECT                - объект элемента
SAVE_REDIRECT                 - редирект при сохранении (путь)
PURCHASE_APPLICATION_LINK     - ссылка на заявку "закупка"
DISPLACEMENT_APPLICATION_LINK - ссылка на заявку "перемещение"
*/
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$FixedAssetsWork = SProceduresFixedAssetsWork::GetInstance();
// объект элемента
$procedureElement = $arParams["ELEMENT_OBJECT"];
if(!$procedureElement) return ShowError(GetMessage("SF_ELEMENT_NOT_EXIST"));
// таблица комментов
$commentsTable = $FixedAssetsWork->GetTable("comments");
if(!$commentsTable) return ShowError(str_replace("#TABLE_NAME#", $FixedAssetsWork->GetTablesInfo()["comments"]["title"], GetMessage("SF_TABLE_NOT_EXIST")));
// имена элементов форм
$inputesName =
	[
	"main_form_prefix"  => 'sp-faw-paf',
	"sign_form_prefix"  => 'sp-faw-pas',
	"main_form_submit"  => 'sp-faw-paf-submit-'.$procedureElement->GetElementId(),
	"sign_form_confirm" => 'sp-faw-pas-confirm-'.$procedureElement->GetElementId(),
	"sign_form_reject"  => 'sp-faw-pas-reject-'.$procedureElement->GetElementId(),
	"sign_form_return"  => 'sp-faw-pas-return-'.$procedureElement->GetElementId(),
	"responsible_close" => 'sp-faw-par-close-'.$procedureElement->GetElementId()
	];
/* -------------------------------------------------------------------- */
/* -------------------- обработчик формы элемента --------------------- */
/* -------------------------------------------------------------------- */
if(is_set($_POST[$inputesName["main_form_submit"]]))
	{
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
	}
/* -------------------------------------------------------------------- */
/* --------------------- обработчик согласования ---------------------- */
/* -------------------------------------------------------------------- */
if
	(
	is_set($_POST[$inputesName["sign_form_confirm"]])
	||
	is_set($_POST[$inputesName["sign_form_reject"]])
	||
	is_set($_POST[$inputesName["sign_form_return"]])
	||
	is_set($_POST[$inputesName["responsible_close"]])
	)
	{
	/* ------------------------------------------ */
	/* ---------------- комменты ---------------- */
	/* ------------------------------------------ */
	$commentsElement = $commentsTable->GetElement("new");
	if($commentsElement)
		{
		// переданные параметры
		$formValue = $_POST[$inputesName["sign_form_prefix"]];
		foreach($_FILES[$inputesName["sign_form_prefix"]]["name"] as $property => $infoArray)
			foreach($infoArray["new"] as $index => $name)
				$formValue[$property]["new"][] =
					[
					"name"     => $name,
					"tmp_name" => $_FILES[$inputesName["sign_form_prefix"]]["tmp_name"][$property]["new"][$index],
					];
		// название коммента
		if(is_set($_POST[$inputesName["sign_form_confirm"]])) $commentName = GetMessage("SP_FAW_PROV_APPLC_AGREEMENT_TYPE_CONFIRM");
		if(is_set($_POST[$inputesName["sign_form_reject"]]))  $commentName = GetMessage("SP_FAW_PROV_APPLC_AGREEMENT_TYPE_REJECT");
		if(is_set($_POST[$inputesName["sign_form_return"]]))  $commentName = GetMessage("SP_FAW_PROV_APPLC_AGREEMENT_TYPE_RETURN");
		if(is_set($_POST[$inputesName["responsible_close"]])) $commentName = GetMessage("SP_FAW_PROV_APPLC_AGREEMENT_TYPE_CLOSED");
		// установка значений+сохранение
		foreach($formValue as $property => $value)
			if($commentsElement->GetProperty($property))
				$commentsElement->GetProperty($property)->SetValue($value, "form");

		$commentsElement->GetProperty("name")       ->SetValue($commentName);
		$commentsElement->GetProperty("application")->SetValue($procedureElement->GetElementId());
		$commentsElement->SaveElement([]);
		}
	/* ------------------------------------------ */
	/* ---------------- действия ---------------- */
	/* ------------------------------------------ */
	if($USER->GetID() == $procedureElement->GetCurrentAgreementUser())
		{
		if(is_set($_POST[$inputesName["sign_form_reject"]])) $procedureElement->ChangeStage("close");
		if(is_set($_POST[$inputesName["sign_form_return"]])) $procedureElement->ChangeStage("start");
		if(is_set($_POST[$inputesName["sign_form_confirm"]]))
			{
			$procedureElement->GetProperty("user_signed")->SetValue(array_merge(SgetClearArray($procedureElement->GetProperty("user_signed")->GetValue()), [$USER->GetID()]));
			$procedureElement->SaveElement(["user_signed"]);
			$procedureElement->ChangeStage("agreement");
			}
		}
	if(is_set($_POST[$inputesName["responsible_close"]]) && in_array($USER->GetID(), $procedureElement->GetResponsibles()))
		$procedureElement->ChangeStage("end");
	/* ------------------------------------------ */
	/* ---------------- редирект ---------------- */
	/* ------------------------------------------ */
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
// состояние заявки
$applicationCondition = false;
if($USER->GetID() == $procedureElement->GetCurrentAgreementUser()) $applicationCondition = "agreement_active";
if
	(
	in_array($USER->GetID(), $procedureElement->GetResponsibles())
	&&
	$procedureElement->GetProperty("stage")->GetValue() == 'responsible'
	&&
	$procedureElement->GetProperty("active")->GetValue() == 'Y'
	)
	$applicationCondition = "responsible_work_active";
// основная форма
$mainFormProps = ["read" => [], "write" => []];
if($procedureElement->GetElementId() != 'new') $mainFormProps["read"][]  = $procedureElement->GetProperty("created_by");
foreach(["department", "fixed_assets_groups", "text", "files"] as $property)
	{
	$propertyObject = $procedureElement->GetProperty($property);
	if($procedureElement->GetElementId() != 'new')                                   $mainFormProps["read"][]  = $propertyObject;
	if($procedureElement->GetAccess("write") && $propertyObject->GetAccess("write")) $mainFormProps["write"][] = $propertyObject;
	}
// форма комментов
$commentsFormProps = [];
$commentsElement   = $commentsTable->GetElement("new");
if($commentsElement && in_array($applicationCondition, ["agreement_active", "responsible_work_active"]))
	foreach(["text", "files"] as $property)
		if($commentsElement->GetProperty($property)->GetAccess("write"))
			$commentsFormProps[] = $commentsElement->GetProperty($property);
// таблица комментов
$commentsTableInfo =
	[
	"titles" => [],
	"info"   => [],
	"props"  => ["created_date", "name", "created_by", "text", "files"]
	];

foreach($commentsTableInfo["props"] as $property) $commentsTableInfo["titles"][] = $commentsTable->GetProperty($property)->GetAttributes()["title"];
foreach($commentsTable->GetQuery(["created_date" => 'desc'], ["application" => $procedureElement->GetElementId()]) as $elementId)
	{
	$infoArray     = [];
	$elementObject = $commentsTable->GetElement($elementId);
	foreach($commentsTableInfo["props"] as $property) $infoArray[] = $elementObject->GetProperty($property);
	$commentsTableInfo["info"][] = $infoArray;
	}
// связанные заявки
$bindApplications = [];
if($procedureElement->GetElementId() != 'new')
	foreach(["purchase_application" => $arParams["PURCHASE_APPLICATION_LINK"], "displacement_application" => $arParams["DISPLACEMENT_APPLICATION_LINK"]] as $table => $link)
		if($FixedAssetsWork->GetTable($table))
			foreach($FixedAssetsWork->GetTable($table)->GetQuery(["ID" => 'asc'], ["provision_application" => $procedureElement->GetElementId()]) as $elementId)
				$bindApplications[$FixedAssetsWork->GetTable($table)->GetElement($elementId)->GetProperty("name")->GetValue()] = str_replace('#ELEMENT_ID#', $elementId, $link);
// готовый массив
$arResult =
	[
	"new_element"           => $elementNew,
	"procedure_closed"      => $procedureClosed,
	"application_condition" => $applicationCondition,
	"form_props"            =>
		[
		"read"  => $mainFormProps["read"],
		"write" => $mainFormProps["write"]
		],
	"vising_props"          => $commentsFormProps,
	"comments_table"        =>
		[
		"titles" => $commentsTableInfo["titles"],
		"info"   => $commentsTableInfo["info"]
		],
	"input_name"            =>
		[
		"main_form" => $inputesName["main_form_prefix"],
		"sign_form" => $inputesName["sign_form_prefix"]
		],
	"links"                 =>
		[
		"create_purchase_application"     => $FixedAssetsWork->GetComponentUrl().'purchase_application/new/?provision_application='.$procedureElement->GetElementId(),
		"create_displacement_application" => $FixedAssetsWork->GetComponentUrl().'displacement_application/new/?provision_application='.$procedureElement->GetElementId(),
		"bind_appplications"              => $bindApplications
		],
	"button_names"          =>
		[
		"main_form_submit"  => $inputesName["main_form_submit"],
		"sign_form_confirm" => $inputesName["sign_form_confirm"],
		"sign_form_reject"  => $inputesName["sign_form_reject"],
		"sign_form_return"  => $inputesName["sign_form_return"],
		"responsible_close" => $inputesName["responsible_close"]
		]
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>