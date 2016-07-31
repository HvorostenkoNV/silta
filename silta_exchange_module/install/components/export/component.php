<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_exchange_module"))            return ShowError("modules required: silta_exchange_module");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

PROCEDURES - массив процедур
*/
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$workProcedures = [];
foreach(SexchangeExport::GetInstance()->GetProcedures() as $procedure => $procedureObject)
	if(in_array($procedure, $arParams["PROCEDURES"]) || in_array("all", $arParams["PROCEDURES"]))
		$workProcedures[] = $procedure;
if(!count($workProcedures)) return ShowError(GetMessage("SEM_E_PROCEDURES_NOT_EXIST"));
/* -------------------------------------------------------------------- */
/* ------------------------------ обмен ------------------------------- */
/* -------------------------------------------------------------------- */
$xmlFilePath = SexchangeExport::GetInstance()->GetXmlExportFile($workProcedures);
echo file_get_contents($_SERVER["DOCUMENT_ROOT"].$xmlFilePath);
unlink($_SERVER["DOCUMENT_ROOT"].$xmlFilePath);
?>