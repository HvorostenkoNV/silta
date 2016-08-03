<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_procedures"))                 return ShowError("modules required: silta_procedures");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

ELEMENT_OBJECT    - объект элемента
STAGE_DESCRIPTION - об.текст стадий
*/
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$procedureElement = $arParams["ELEMENT_OBJECT"];
if(!$procedureElement->GetProperty("stage")->GetValue())
	$procedureElement->GetProperty("stage")->SetValue("start");
/* -------------------------------------------------------------------- */
/* -------------------- готовый массив для шаблона -------------------- */
/* -------------------------------------------------------------------- */
$statusArray = [];
foreach($procedureElement->GetProperty("stage")->GetAttributes()["list"] as $type => $listInfo)
	{
	$infoArray =
		[
		"title" => $listInfo["title"],
		"type"  => $type
		];
	if($type == $procedureElement->GetProperty("stage")->GetValue()) $infoArray["checked"] = true;
	if($type == 'responsible')                                       $infoArray["responsibles"] = $procedureElement->GetResponsibles();
	if($type == 'agreement')
		foreach($procedureElement->GetBosses() as $userId)
			{
			$value = false;
			if(in_array($userId, $procedureElement->GetProperty("user_signed")->GetValue())) $value = 'signed';
			if($userId == $procedureElement->GetCurrentAgreementUser())                      $value = 'active';
			$infoArray["sign_users"][$userId] = $value;
			}
	$statusArray[] = $infoArray;
	}
// об.текст стадий
$stageDescription = false;
if($arParams["STAGE_DESCRIPTION"] == 'Y') $stageDescription = true;
// процедура закрыта
$procedureClosed = false;
if($procedureElement->GetProperty("active")->GetValue() == 'N') $procedureClosed = true;
// готовый массив
$arResult =
	[
	"status_array"      => $statusArray,
	"stage_description" => $stageDescription,
	"procedure_closed"  => $procedureClosed
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>