<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_procedures"))                 return ShowError("modules required: silta_procedures");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

SEF_FOLDER - ЧПУ
*/
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$BusinessTrip = SProceduresBusinessTrip::GetInstance();
// ЧПУ
$urlVariables = [];
$urlTemplates =
	[
	"main"         => "index.php",
	"page_type"    => "#PAGE_TYPE#/",
	"element_page" => "element/#ELEMENT_ID#/"
	];

$pageCorrect = CComponentEngine::ParseComponentPath($arParams["SEF_FOLDER"], $urlTemplates, $urlVariables);
if(!$pageCorrect) LocalRedirect($arParams["SEF_FOLDER"]);
/* -------------------------------------------------------------------- */
/* ------------------------- страница элемента ------------------------ */
/* -------------------------------------------------------------------- */
if($urlVariables["ELEMENT_ID"])
	{
	// элемент
	$procedureTable   = $BusinessTrip->GetTable("business_trip");
	if(!$procedureTable)   return ShowError(str_replace("#TABLE_NAME#", $BusinessTrip->GetTablesInfo()["business_trip"]["title"], GetMessage("SF_TABLE_NOT_EXIST")));
	$procedureElement = $procedureTable->GetElement($urlVariables["ELEMENT_ID"]);
	if(!$procedureElement) return ShowError(GetMessage("SF_ELEMENT_NOT_EXIST"));
	// готовый массив
	$arResult =
		[
		"page"            => 'element_page',
		"element_object"  => $procedureElement,
		"save_redirect"   => $arParams["SEF_FOLDER"].CComponentEngine::MakePathFromTemplate($urlTemplates["element_page"], ["ELEMENT_ID" => '#ELEMENT_ID#']),
		"delete_redirect" => $arParams["SEF_FOLDER"]
		];
	}
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>