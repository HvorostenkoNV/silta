<?
/* ------------------------------------------------------------------- */
/* ------------------------ ссылкы на элементы ----------------------- */
/* ------------------------------------------------------------------- */
if($arResult["building_type"] == 'component_links')
	foreach($arResult["links"] as $linkInfo)
		$APPLICATION->IncludeComponent
			(
			"silta_diy_module:element_link", '',
				[
				"LINK"           => $linkInfo["link"],
				"ELEMENT_OBJECT" => $linkInfo["element_object"]
				]
			);
/* ------------------------------------------------------------------- */
/* -------------------- истрия изменений - таблица ------------------- */
/* ------------------------------------------------------------------- */
if($arResult["building_type"] == 'history_changings')
	$APPLICATION->IncludeComponent
		(
		"silta_diy_module:element_history_changings", '',
			[
			"ELEMENT_OBJECT" => $arResult["history_element_object"]
			]
		);
/* ------------------------------------------------------------------- */
/* ---------------------------- свойство ----------------------------- */
/* ------------------------------------------------------------------- */
if($arResult["building_type"] == 'property_field')
	{
	$fieldParams = $arResult["field_params"];
	if($arResult["features"]["ukraine_cities"]) $fieldParams["PROPS"]       = ["name", "region"];
	if($arResult["features"]["start_roots"])    $fieldParams["START_ROOTS"] = $arResult["features"]["start_roots"];
	if($arResult["field_type"] == 'write')      $fieldParams["INPUT_NAME"]  = $arResult["features"]["input_name"];
	if($arResult["features"]["nomenclature"])
		$fieldParams =
			[
			"PROPS"  => ["name", "trade_mark", "nominal", "packing"],
			"FILTER" => ["trade_mark" => $arResult["features"]["nomenclature_filter"]]
			];

	$APPLICATION->IncludeComponent
		(
		"silta_framework:form_elements.property_field", '',
			[
			"FIELD_TYPE"      => $arResult["field_type"],
			"PROPERTY_OBJECT" => $arResult["property_object"],
			"FIELD_PARAMS"    => $fieldParams
			]
		);
	}
?>