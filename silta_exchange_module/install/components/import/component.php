<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_exchange_module"))            return ShowError("modules required: silta_exchange_module");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

PROCEDURES - массив процедур
*/
if(!$arParams["FILE_VAR_NAME"]) return ShowError(GetMessage("SEM_I_FILE_VAR_NAME_NOT_EXIST"));
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$exchangeObject = SexchangeImport::GetInstance();
$workProcedures = [];
$filePath       = ["geted_file" => '', "answer" => ''];
foreach($exchangeObject->GetProcedures() as $procedure => $procedureObject)
	if(in_array($procedure, $arParams["PROCEDURES"]) || in_array("all", $arParams["PROCEDURES"]))
		$workProcedures[] = $procedure;
if(!count($workProcedures)) return ShowError(GetMessage("SEM_I_PROCEDURES_NOT_EXIST"));
/* -------------------------------------------------------------------- */
/* ------------------------------ обмен ------------------------------- */
/* -------------------------------------------------------------------- */
if(!$_FILES[$arParams["FILE_VAR_NAME"]]["tmp_name"]) return;
$filePath["geted_file"] = $exchangeObject->SaveXmlFile(base64_decode(file_get_contents($_FILES[$arParams["FILE_VAR_NAME"]]["tmp_name"])));

set_time_limit(300);
$exchangeObject->SetXmlFile($filePath["geted_file"]);
foreach($workProcedures as $procedure)
	foreach($exchangeObject->GetProcedures()[$procedure]->GetElements() as $elementObject)
		$elementObject->RunExchange();
$filePath["answer"] = $exchangeObject->GetXmlAnswer();

echo file_get_contents($_SERVER["DOCUMENT_ROOT"].$filePath["answer"]);
foreach($filePath as $path) unlink($_SERVER["DOCUMENT_ROOT"].$path);
?>