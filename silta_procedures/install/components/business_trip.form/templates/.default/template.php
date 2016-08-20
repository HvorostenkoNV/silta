<div
	class="sp-btr-form"
	<?if($arResult["procedure_closed"]):?>procedure-closed<?endif?>
	<?if($arResult["new_element"]):?>     new-element     <?endif?>
>
	<?
	/* ------------------------------------------------------------------- */
	/* ----------------------- пометки руководителя ---------------------- */
	/* ------------------------------------------------------------------- */
	?>
	<?if(count($arResult["alert_props"])):?>
	<h3><?=GetMessage("SP_BTR_ALERT_TEXT_TITLE")?></h3>
	<form method="post">
		<table>
			<col width="30%"><col width="70%">
			<tbody>
				<?
				foreach($arResult["alert_props"] as $propertyObject)
					$APPLICATION->IncludeComponent
						(
						"silta_framework:form_elements.property_row", '',
							[
							"FIELD_TYPE"      => 'read',
							"PROPERTY_OBJECT" => $propertyObject
							]
						);
				?>
			</tbody>
		</table>
	</form>
	<?endif?>
	<?
	/* ------------------------------------------------------------------- */
	/* ------------------------- форма осн.инфы -------------------------- */
	/* ------------------------------------------------------------------- */
	?>
	<h3><?=GetMessage("SP_BTR_MAIN_FORM_TITLE")?></h3>
	<form method="post" enctype="multipart/form-data">
		<?
		/* ------------------------------------------ */
		/* ----------------- форма ------------------ */
		/* ------------------------------------------ */
		?>
		<?foreach(["read", "write"] as $fieldType):?>
			<?if(count($arResult["main_form_props"][$fieldType])):?>
				<table form-type="<?=$fieldType?>">
					<col width="30%"><col width="70%">
					<tbody>
						<?
						foreach(["created_by", "user_department", "trip_start_date", "trip_description", "path_description", "wishes_description", "hotel_need", "hotel_start_date"] as $property)
							if($arResult["main_form_props"][$fieldType][$property])
								{
								$rowParams   = [];
								$fieldParams = ["INPUT_NAME" => $arResult["input_name"]["main_form"].'['.$property.']'];
								if($property == 'user_department') $rowParams["SPACE"]  = 'bottom';
								if($property == 'hotel_need')      $fieldParams["ATTR"] = 'hotel-need-triger';
								if($property == 'trip_start_date')
									{
									$fieldParams["INPUT_NAME_START"] = $arResult["input_name"]["main_form"].'[trip_start_date]';
									$fieldParams["INPUT_NAME_END"]   = $arResult["input_name"]["main_form"].'[trip_end_date]';
									}
								if($property == 'hotel_start_date')
									{
									$fieldParams["INPUT_NAME_START"] = $arResult["input_name"]["main_form"].'[hotel_start_date]';
									$fieldParams["INPUT_NAME_END"]   = $arResult["input_name"]["main_form"].'[hotel_end_date]';
									}

								$APPLICATION->IncludeComponent
									(
									"silta_procedures:business_trip.property_row", '',
										[
										"FIELD_TYPE"      => $fieldType,
										"PROPERTY_OBJECT" => $arResult["main_form_props"][$fieldType][$property],
										"ROW_PARAMS"      => $rowParams,
										"FIELD_PARAMS"    => $fieldParams
										]
									);
								}
						?>
					</tbody>
				</table>
			<?endif?>
		<?endforeach?>
		<?
		/* ------------------------------------------ */
		/* ----------------- кнопки ----------------- */
		/* ------------------------------------------ */
		// кнопки "изменить элемент"
		if(count($arResult["main_form_props"]["write"]) && !$arResult["new_element"])
			{
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"TITLE"        => GetMessage("SP_BTR_FORM_EDIT_BUTTON"),
					"IMG"          => $templateFolder.'/images/edit.png',
					"IMG_POSITION" => 'left',
					"ATTR"         => 'edit-button'
					]
				);
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"TITLE"  => GetMessage("SP_BTR_FORM_CANCEL_BUTTON"),
					"ATTR"   => 'cancel-button',
					"HIDDEN" => 'Y'
					]
				);
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"TITLE"               => GetMessage("SP_BTR_FORM_APPLY_BUTTON"),
					"VALIDATE_FORM_ALERT" => GetMessage("SP_BTR_FORM_SUBMIT_ALERT"),
					"IMG"                 => $templateFolder.'/images/apply.png',
					"IMG_POSITION"        => 'left',
					"NAME"                => $arResult["button_names"]["main_form_submit"],
					"ATTR"                => 'submit-button',
					"HIDDEN"              => 'Y'
					]
				);
			}
		// кнопка "создать элемент"
		if(count($arResult["main_form_props"]["write"]) && $arResult["new_element"])
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"TITLE"               => GetMessage("SP_BTR_FORM_CREATE_BUTTON"),
					"VALIDATE_FORM_ALERT" => GetMessage("SP_BTR_FORM_SUBMIT_ALERT"),
					"IMG"                 => $templateFolder.'/images/create.png',
					"IMG_POSITION"        => 'left',
					"NAME"                => $arResult["button_names"]["main_form_submit"]
					]
				);
		?>
	</form>
	<?
	/* ------------------------------------------------------------------- */
	/* ------------------------ форма визирования ------------------------ */
	/* ------------------------------------------------------------------- */
	?>
	<?if(count($arResult["sign_form_props"])):?>
	<h3><?=GetMessage("SP_BTR_SIGN_FORM_TITLE")?></h3>
	<form method="post" enctype="multipart/form-data">
		<?
		/* ------------------------------------------ */
		/* ----------------- форма ------------------ */
		/* ------------------------------------------ */
		?>
		<table>
			<col width="30%"><col width="70%">
			<tbody>
				<?
				foreach($arResult["sign_form_props"] as $property => $propertyObject)
					$APPLICATION->IncludeComponent
						(
						"silta_framework:form_elements.property_row", '',
							[
							"FIELD_TYPE"      => 'write',
							"PROPERTY_OBJECT" => $propertyObject,
							"FIELD_PARAMS"    =>  ["INPUT_NAME" => $arResult["input_name"]["sign_form"].'['.$property.']']
							]
						);
				?>
			</tbody>
		</table>
		<?
		/* ------------------------------------------ */
		/* ----------------- кнопки ----------------- */
		/* ------------------------------------------ */
		$APPLICATION->IncludeComponent
			(
			"silta_framework:form_elements.button", '',
				[
				"TITLE"        => GetMessage("SP_BTR_SIGN_CONFIRM"),
				"IMG"          => $templateFolder.'/images/sign_confirm.png',
				"IMG_POSITION" => 'left',
				"NAME"         => $arResult["button_names"]["boss_sign_confirm"],
				"TAG"          => 'button'
				]
			);
		$APPLICATION->IncludeComponent
			(
			"silta_framework:form_elements.button", '',
				[
				"TITLE"        => GetMessage("SP_BTR_SIGN_REJECT"),
				"IMG"          => $templateFolder.'/images/sign_reject.png',
				"IMG_POSITION" => 'left',
				"NAME"         => $arResult["button_names"]["boss_sign_reject"],
				"TAG"          => 'button'
				]
			);
		$APPLICATION->IncludeComponent
			(
			"silta_framework:form_elements.button", '',
				[
				"TITLE"        => GetMessage("SP_BTR_SIGN_RETURN"),
				"IMG"          => $templateFolder.'/images/sign_return.png',
				"IMG_POSITION" => 'left',
				"NAME"         => $arResult["button_names"]["boss_sign_return"],
				"TAG"          => 'button'
				]
			);
		?>
	</form>
	<?endif?>
	<?
	/* ------------------------------------------------------------------- */
	/* ------------------------- форма доп.инфы -------------------------- */
	/* ------------------------------------------------------------------- */
	?>
	<?if(count($arResult["add_form_props"]["read"]) || count($arResult["add_form_props"]["write"])):?>
	<h3><?=GetMessage("SP_BTR_SPACIAL_FORM_TITLE")?></h3>
	<form method="post" enctype="multipart/form-data">
		<?
		/* ------------------------------------------ */
		/* ----------------- форма ------------------ */
		/* ------------------------------------------ */
		?>
		<?foreach(["read", "write"] as $fieldType):?>
			<?if(count($arResult["add_form_props"][$fieldType])):?>
				<table form-type="<?=$fieldType?>">
					<col width="30%"><col width="70%">
					<tbody>
						<?
						foreach(["trip_day_cost", "hotel_day_cost", "hotel_comments", "ticket_name", "ticket_date", "ticket_cost", "trip_files"] as $property)
							if($arResult["add_form_props"][$fieldType][$property])
								{
								$rowParams   = [];
								$fieldParams = ["INPUT_NAME" => $arResult["input_name"]["main_form"].'['.$property.']'];
								if(in_array($property, ["trip_day_cost", "hotel_comments", "ticket_name"])) $rowParams["SPACE"] = 'bottom';

								$APPLICATION->IncludeComponent
									(
									"silta_procedures:business_trip.property_row", '',
										[
										"FIELD_TYPE"      => $fieldType,
										"PROPERTY_OBJECT" => $arResult["add_form_props"][$fieldType][$property],
										"ROW_PARAMS"      => $rowParams,
										"FIELD_PARAMS"    => $fieldParams
										]
									);
								}
						?>
					</tbody>
				</table>
			<?endif?>
		<?endforeach?>
		<?
		/* ------------------------------------------ */
		/* ----------------- кнопки ----------------- */
		/* ------------------------------------------ */
		if(count($arResult["add_form_props"]["write"]))
			{
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"TITLE"        => GetMessage("SP_BTR_FORM_EDIT_BUTTON"),
					"IMG"          => $templateFolder.'/images/edit.png',
					"IMG_POSITION" => 'left',
					"ATTR"         => 'edit-button'
					]
				);
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"TITLE"  => GetMessage("SP_BTR_FORM_CANCEL_BUTTON"),
					"ATTR"   => 'cancel-button',
					"HIDDEN" => 'Y'
					]
				);
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"TITLE"               => GetMessage("SP_BTR_FORM_APPLY_BUTTON"),
					"VALIDATE_FORM_ALERT" => GetMessage("SP_BTR_FORM_SUBMIT_ALERT"),
					"IMG"                 => $templateFolder.'/images/apply.png',
					"IMG_POSITION"        => 'left',
					"NAME"                => $arResult["button_names"]["assist_form_submit"],
					"ATTR"                => 'submit-button',
					"HIDDEN"              => 'Y'
					]
				);
			}
		?>
	</form>
	<?endif?>
</div>