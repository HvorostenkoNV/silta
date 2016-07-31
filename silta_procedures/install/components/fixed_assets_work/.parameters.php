<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_procedures"))                 return;
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */

/* -------------------------------------------------------------------- */
/* ----------------------------- разделы ------------------------------ */
/* -------------------------------------------------------------------- */
$arComponentParameters["GROUPS"] =
	[
	"MAIN"                     => ["NAME" => GetMessage("SP_FAW_GROUPS_MAIN")],
	"PROVISION_APPLICATION"    => ["NAME" => GetMessage("SP_FAW_GROUPS_PROVISION_APPLICATION")],
	"PURCHASE_APPLICATION"     => ["NAME" => GetMessage("SP_FAW_GROUPS_PURCHASE_APPLICATION")],
	"DISPLACEMENT_APPLICATION" => ["NAME" => GetMessage("SP_FAW_GROUPS_DISPLACEMENT_APPLICATION")],
	"WRITE_OFF_APPLICATION"    => ["NAME" => GetMessage("SP_FAW_GROUPS_WRITE_OFF_APPLICATION")]
	];
/* -------------------------------------------------------------------- */
/* ---------------------------- параметры ----------------------------- */
/* -------------------------------------------------------------------- */
$arComponentParameters["PARAMETERS"] =
	[
	"SEF_FOLDER" =>
		[
		"PARENT" => "MAIN",
		"NAME"   => GetMessage("SP_FAW_SEF_FOLDER"),
		"TYPE"   => "STRING"
		],
	"SEF_MODE" => []
	];
?>