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
$FixedAssetsWork = SProceduresFixedAssetsWork::GetInstance();
// ЧПУ
$urlVariables   = [];
$availablePages = ["main", "provision_application", "purchase_application", "displacement_application", "write_off_application", "register"];
$urlTemplates   =
	[
	"main"       => "index.php",
	"page_type"  => "#PAGE_TYPE#/",
	"element_id" => "#PAGE_TYPE#/#ELEMENT_ID#/"
	];

$pageCorrect = CComponentEngine::ParseComponentPath($arParams["SEF_FOLDER"], $urlTemplates, $urlVariables);
if(!$pageCorrect) LocalRedirect($arParams["SEF_FOLDER"]);

$componentPage = $urlVariables["PAGE_TYPE"];
$pageElementId = $urlVariables["ELEMENT_ID"];
if(!$componentPage || !in_array($componentPage, $availablePages)) $componentPage = 'main';
/* -------------------------------------------------------------------- */
/* ------------------------- процедура заказа ------------------------- */
/* -------------------------------------------------------------------- */
if($componentPage == 'provision_application' && $pageElementId)
	{
	$APPLICATION->SetTitle(GetMessage("SP_FAW_PAGE_TITLES_PROVISION_APPLICATION"));
	// элемент
	$procedureTable   = $FixedAssetsWork->GetTable("provision_application");
	if(!$procedureTable)   return ShowError(str_replace("#TABLE_NAME#", $FixedAssetsWork->GetTablesInfo()["provision_application"]["title"], GetMessage("SF_TABLE_NOT_EXIST")));
	$procedureElement = $procedureTable->GetElement($pageElementId);
	if(!$procedureElement) return ShowError(GetMessage("SF_ELEMENT_NOT_EXIST"));
	// готовый массив
	$arResult =
		[
		"page"                          => $componentPage,
		"element_object"                => $procedureElement,
		"save_redirect"                 => $arParams["SEF_FOLDER"].CComponentEngine::MakePathFromTemplate($urlTemplates["element_id"], ["PAGE_TYPE" => 'provision_application', "ELEMENT_ID" => '#ELEMENT_ID#']),
		"delete_redirect"               => $arParams["SEF_FOLDER"],
		"purchase_application_link"     => $arParams["SEF_FOLDER"].CComponentEngine::MakePathFromTemplate($urlTemplates["element_id"], ["PAGE_TYPE" => 'purchase_application',     "ELEMENT_ID" => '#ELEMENT_ID#']),
		"displacement_application_link" => $arParams["SEF_FOLDER"].CComponentEngine::MakePathFromTemplate($urlTemplates["element_id"], ["PAGE_TYPE" => 'displacement_application', "ELEMENT_ID" => '#ELEMENT_ID#'])
		];
	}
/* -------------------------------------------------------------------- */
/* ----------------------- процедура перемещения ---------------------- */
/* -------------------------------------------------------------------- */
if($componentPage == 'displacement_application' && $pageElementId)
	{
	$APPLICATION->SetTitle(GetMessage("SP_FAW_PAGE_TITLES_DISPLACEMENT_APPLICATION"));
	// элемент
	$procedureTable   = $FixedAssetsWork->GetTable("displacement_application");
	if(!$procedureTable)   return ShowError(str_replace("#TABLE_NAME#", $FixedAssetsWork->GetTablesInfo()["displacement_application"]["title"], GetMessage("SF_TABLE_NOT_EXIST")));
	$procedureElement = $procedureTable->GetElement($pageElementId);
	if(!$procedureElement) return ShowError(GetMessage("SF_ELEMENT_NOT_EXIST"));
	// готовый массив
	$arResult =
		[
		"page"           => $componentPage,
		"element_object" => $procedureElement
		];
	}
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>