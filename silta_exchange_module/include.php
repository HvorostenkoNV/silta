<?
if(!CModule::IncludeModule("silta_framework")) return ShowError('silta_framework not instaled');
IncludeModuleLangFile(__FILE__);
include 'include_procedures.php';

$includeProcedures["Sexchange"]       = 'classes/general/exchange.php';
$includeProcedures["SexchangeImport"] = 'classes/general/exchange_import.php';
$includeProcedures["SexchangeExport"] = 'classes/general/exchange_export.php';

$includeProcedures["SexchangeProcedure"]       = 'classes/general/procedure.php';
$includeProcedures["SexchangeImportProcedure"] = 'classes/general/procedure_import.php';
$includeProcedures["SexchangeExportProcedure"] = 'classes/general/procedure_export.php';

$includeProcedures["SexchangeElement"]       = 'classes/general/element.php';
$includeProcedures["SexchangeImportElement"] = 'classes/general/element_import.php';
$includeProcedures["SexchangeExportElement"] = 'classes/general/element_export.php';
CModule::AddAutoloadClasses("silta_exchange_module", $includeProcedures);
?>