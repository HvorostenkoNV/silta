<?
if(!CModule::IncludeModule("silta_diy_module")) return;
$arComponentDescription =
	[
	"NAME"        => GetMessage("SDM_MM_COMPONENT_NAME"),
	"DESCRIPTION" => GetMessage("SDM_MM_COMPONENT_DESCRIPTION"),
	'PATH'        =>
		[
		'ID'   => 'diy_module',
		"NAME" => GetMessage("SDM_NAME")
		]
	];
?>