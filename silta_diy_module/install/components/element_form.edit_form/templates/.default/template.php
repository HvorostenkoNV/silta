<form method="post" enctype="multipart/form-data" class="silta-diy-module-form-edit">
	<?
	/* ------------------------------------------ */
	/* ------------- форма на чтение ------------ */
	/* ------------------------------------------ */
	?>
	<?if(count($arResult["form_body"]["read_form"])):?>
	<table form-type="read">
		<col width="30%"><col width="70%">
		<tbody>
			<?
			foreach($arResult["form_body"]["props_info"] as $property => $infoArray)
				if(in_array($property, $arResult["form_body"]["read_form"]))
					{
					$rowParams = [];
					if($infoArray["features"]["space"]) $rowParams["SPACE"] = $infoArray["features"]["space"];

					$APPLICATION->IncludeComponent
						(
						"silta_diy_module:property_row", '',
							[
							"FIELD_TYPE"      => 'read',
							"PROPERTY_OBJECT" => $infoArray["object"],
							"WORK_TABLE"      => $arResult["work_table"],
							"LINKS"           => $arResult["links"],
							"ROW_PARAMS"      => $rowParams
							]
						);
					}
			?>
		</tbody>
	</table>
	<?endif?>
	<?
	/* ------------------------------------------ */
	/* ------------- форма на запись ------------ */
	/* ------------------------------------------ */
	?>
	<?if(count($arResult["form_body"]["write_form"])):?>
	<table form-type="write" <?if(count($arResult["form_body"]["read_form"])):?>style="display: none"<?endif?>>
		<col width="30%"><col width="70%">
		<tbody>
			<?
			foreach($arResult["form_body"]["props_info"] as $property => $infoArray)
				{
				$fieldType = 'write';
				if(!in_array($property, $arResult["form_body"]["write_form"])) $fieldType = 'read';
				$rowParams = [];
				if($infoArray["features"]["space"]) $rowParams["SPACE"] = $infoArray["features"]["space"];

				$APPLICATION->IncludeComponent
					(
					"silta_diy_module:property_row", '',
						[
						"FIELD_TYPE"      => $fieldType,
						"PROPERTY_OBJECT" => $infoArray["object"],
						"WORK_TABLE"      => $arResult["work_table"],
						"LINKS"           => $arResult["links"],
						"ROW_PARAMS"      => $rowParams,
						"INPUT_NAME"      => $arResult["input_name"]["form_props"]
						]
					);
				}
			?>
		</tbody>
	</table>
	<?endif?>
	<?
	/* ------------------------------------------ */
	/* ----------------- кнопки ----------------- */
	/* ------------------------------------------ */
	$tableUpper = ToUpper($arResult["work_table"]);
	// кнопки "изменить элемент"
	if(count($arResult["form_body"]["write_form"]) && !$arResult["access"]["create"])
		{
		$editButtonTitle = GetMessage('SDM_EFE_EDIT_BUTTON_' .$tableUpper);
		if(!$editButtonTitle) $editButtonTitle  = GetMessage("SDM_EFE_EDIT_BUTTON_DEFAULT");

		$APPLICATION->IncludeComponent
			(
			"silta_framework:form_elements.button", '',
				[
				"TITLE"  => GetMessage("SDM_EFE_CANCEL_BUTTON"),
				"IMG"    => $templateFolder.'/images/cancel.png',
				"ATTR"   => 'cancel-button',
				"HIDDEN" => 'Y'
				]
			);
		$APPLICATION->IncludeComponent
			(
			"silta_framework:form_elements.button", '',
				[
				"TITLE" => $editButtonTitle,
				"IMG"   => $templateFolder.'/images/edit.png',
				"ATTR"  => 'edit-button'
				]
			);
		$APPLICATION->IncludeComponent
			(
			"silta_framework:form_elements.button", '',
				[
				"TITLE"               => GetMessage("SDM_EFE_APPLY_BUTTON"),
				"IMG"                 => $templateFolder.'/images/apply.png',
				"NAME"                => $arResult["input_name"]["submit_button"],
				"VALIDATE_FORM_ALERT" => GetMessage("SDM_EFE_FORM_ALERT"),
				"ATTR"                => 'submit-button',
				"HIDDEN"              => 'Y'
				]
			);
		}
	// кнопка "удалить элемент"
	if($arResult["access"]["delete"])
		{
		$deleteButtonTitle = GetMessage('SDM_EFE_DELETE_BUTTON_'         .$tableUpper);
		$askText           = GetMessage('SDM_EFE_DELETE_BUTTON_ASK_TEXT_'.$tableUpper);
		if(!$deleteButtonTitle) $deleteButtonTitle = GetMessage("SDM_EFE_DELETE_BUTTON_DEFAULT");
		if(!$askText)           $askText           = GetMessage("SDM_EFE_DELETE_BUTTON_ASK_TEXT_DEFAULT");
		$APPLICATION->IncludeComponent
			(
			"silta_framework:form_elements.button", '',
				[
				"TITLE"        => $deleteButtonTitle,
				"IMG"          => $templateFolder.'/images/delete.png',
				"NAME"         => $arResult["input_name"]["delete_button"],
				"CONFIRM_TEXT" => $askText
				]
			);
		}
	// кнопка "создать элемент"
	if($arResult["access"]["create"])
		{
		$createButtonTitle = GetMessage('SDM_EFE_CREATE_BUTTON_'.$tableUpper);
		if(!$createButtonTitle) $createButtonTitle = GetMessage("SDM_EFE_CREATE_BUTTON_DEFAULT");
		$APPLICATION->IncludeComponent
			(
			"silta_framework:form_elements.button", '',
				[
				"TITLE"               => $createButtonTitle,
				"IMG"                 => $templateFolder.'/images/create.png',
				"NAME"                => $arResult["input_name"]["submit_button"],
				"VALIDATE_FORM_ALERT" => GetMessage("SDM_EFE_FORM_ALERT")
				]
			);
		}
	?>
</form>