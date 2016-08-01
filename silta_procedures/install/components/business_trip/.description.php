<?
if(!CModule::IncludeModule("silta_procedures")) return;
$arComponentDescription =
	[
	"NAME"        => GetMessage("SP_BTR_COMPONENT_NAME"),
	"DESCRIPTION" => GetMessage("SP_BTR_COMPONENT_DESCRIPTION"),
	'PATH'        =>
		[
		'ID'   => 'silta_procedures',
		"NAME" => GetMessage("SILTA_PROCEDURES_NAME")
		]
	];
?>