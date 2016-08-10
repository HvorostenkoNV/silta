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
/* -------------------------------------------------------------------- */
/* -------------------- готовый массив для шаблона -------------------- */
/* -------------------------------------------------------------------- */
$statusArray = [];
foreach($procedureElement->GetStageList() as $value => $title)
	{
	$infoArray =
		[
		"title" => $title,
		"type"  => $value
		];

	if($value == $procedureElement->GetStage()) $infoArray["checked"]    = true;
	if($value == 'boss_agreement')              $infoArray["boss_id"]    = $procedureElement->GetSignBoss();
	if($value == 'assist_user_work')            $infoArray["manager_id"] = $procedureElement->GetAssistUser();

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