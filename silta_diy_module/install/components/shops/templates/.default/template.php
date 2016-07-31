<?
$APPLICATION->IncludeComponent("silta_diy_module:module_menu", '', $arResult["menu_links"]);
/* ============================================================================================= */
/* ====================================== ТАБЛИЦА - СПИСОК ===================================== */
/* ============================================================================================= */
if($arResult["page_type"] == 'table_list')
	{
	// фильтр
	$APPLICATION->IncludeComponent
		(
		"silta_diy_module:table_filter", '',
			[
			"WORK_TABLE"   => 'shops',
			"FILTER_PROPS" => $arResult["filter_props"]
			]
		);
	// кнопка "Добавить элемент"
	$APPLICATION->IncludeComponent
		(
		"silta_diy_module:add_element_button", '',
			[
			"WORK_TABLE" => 'shops',
			"LINK"       => $arResult["add_element_link"]
			]
		);
	// таблица
	$APPLICATION->IncludeComponent
		(
		"silta_diy_module:table_list", '',
			[
			"WORK_TABLE"     => 'shops',
			"TABLE_PROPS"    => $arResult["table_props"],
			"ELEMENTS_COUNT" => $arResult["elements_count"],
			"LINKS"          => $arResult["links"]
			]
		);
	}
/* ============================================================================================= */
/* ======================================= ФОРМА ЭЛЕМЕНТА ====================================== */
/* ============================================================================================= */
?>
<?if($arResult["page_type"] == 'element_form'):?>
	<?
	/* -------------------------------------------------------------------- */
	/* ------------------------------ шапка ------------------------------- */
	/* -------------------------------------------------------------------- */
	$APPLICATION->IncludeComponent
		(
		"silta_diy_module:element_form.hat", '',
			[
			"ELEMENT_OBJECT" => $arResult["element_object"],
			"HOME_LINK"      => $arResult["list_link"],
			"FORM_TABS"      => $arResult["form_tabs"]
			]
		);
	/* -------------------------------------------------------------------- */
	/* ------------------------------- тело ------------------------------- */
	/* -------------------------------------------------------------------- */
	?>
	<div class="silta-diy-module-shop-element-body">
		<?
		// основная инфа
		if($arResult["current_form_tab"] == 'main_info')
			$APPLICATION->IncludeComponent
				(
				"silta_diy_module:element_form.edit_form", '',
					[
					"ELEMENT_OBJECT"    => $arResult["element_object"],
					"FORM_PROPS"        => $arResult["form_props"],
					"FORM_PROPS_BUFFER" => $arResult["form_props_buffer"],
					"LINKS"             => $arResult["links"],
					"SAVE_REDIRECT"     => $arResult["save_redirect"],
					"DELETE_REDIRECT"   => $arResult["delete_redirect"]
					]
				);
		// история
		if($arResult["current_form_tab"] == 'history')
			{
			$APPLICATION->IncludeComponent
				(
				"silta_diy_module:table_filter", '',
					[
					"WORK_TABLE"   => 'element_history',
					"FILTER_PROPS" => ["created_date", "created_by", "operation_type"]
					]
				);
			$APPLICATION->IncludeComponent
				(
				"silta_diy_module:element_comment_form", '',
					[
					"ELEMENT_OBJECT" => $arResult["element_object"]
					]
				);
			$APPLICATION->IncludeComponent
				(
				"silta_diy_module:table_list", '',
					[
					"WORK_TABLE"     => 'element_history',
					"DEFAULT_SORTER" => 'created_date|desc',
					"TABLE_PROPS"    => ["created_date", "created_by", "operation_type", "changing"],
					"ELEMENTS_COUNT" => 25,
					"MULTIPLY_EDIT"  => 'N'
					]
				);
			}
		// история
		if($arResult["current_form_tab"] == 'contacts')
			$APPLICATION->IncludeComponent
				(
				"silta_diy_module:element_form.subtable", '',
					[
					"SUBTABLE"          => 'shop_contacts',
					"ELEMENT_OBJECT"    => $arResult["element_object"],
					"FORM_PROPS"        => $arResult["form_props"],
					"FORM_PROPS_BUFFER" => $arResult["form_props_buffer"],
					"LINKS"             => $arResult["links"]
					]
				);
		// история
		if($arResult["current_form_tab"] == 'sales')
			{
			$APPLICATION->IncludeComponent
				(
				"silta_diy_module:table_filter", '',
					[
					"WORK_TABLE"   => 'sales',
					"FILTER_PROPS" => $arResult["sales_filter_props"]
					]
				);
			$APPLICATION->IncludeComponent
				(
				"silta_diy_module:add_element_button", '',
					[
					"WORK_TABLE" => 'sales',
					"LINK"       => $arResult["add_sale_link"]
					]
				);
			$APPLICATION->IncludeComponent
				(
				"silta_diy_module:table_list", '',
					[
					"WORK_TABLE"     => 'sales',
					"TABLE_PROPS"    => $arResult["sales_table_props"],
					"ELEMENTS_COUNT" => 25,
					"LINKS"          => $arResult["links"]
					]
				);
			}
		?>
	</div>
<?endif?>