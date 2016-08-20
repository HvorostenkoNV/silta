<?
$APPLICATION->IncludeComponent
	(
	"silta_framework:form_elements.property_row", '',
		[
		"FIELD_TYPE"             => $arResult["field_type"],
		"PROPERTY_OBJECT"        => $arResult["property_object"],
		"ROW_PARAMS"             => $arResult["row_params"],
		"FIELD_COMPONENT_NAME"   => 'silta_procedures:business_trip.property_field',
		"FIELD_COMPONENT_PARAMS" => 
			[
			"PROPERTY_OBJECT" => $arResult["property_object"],
			"FIELD_TYPE"      => $arResult["field_type"],
			"FIELD_PARAMS"    => $arResult["field_params"]
			]
		]
	);
?>