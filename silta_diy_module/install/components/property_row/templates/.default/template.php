<?
$APPLICATION->IncludeComponent
	(
	"silta_framework:form_elements.property_row", '',
		[
		"FIELD_TYPE"             => $arResult["field_type"],
		"PROPERTY_OBJECT"        => $arResult["property_object"],
		"ROW_PARAMS"             => $arResult["row_params"],
		"FIELD_COMPONENT_NAME"   => 'silta_diy_module:property_field',
		"FIELD_COMPONENT_PARAMS" => 
			[
			"FIELD_TYPE"      => $arResult["field_type"],
			"WORK_TABLE"      => $arResult["work_table"],
			"LINKS"           => $arResult["links"],
			"INPUT_NAME"      => $arResult["input_name"],
			"PROPERTY_OBJECT" => $arResult["property_object"],
			"FIELD_PARAMS"    => $arResult["field_params"]
			]
		]
	);
?>