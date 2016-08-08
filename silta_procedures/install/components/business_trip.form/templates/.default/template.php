<div
	class="sp-btr-form"
	<?if($arResult["procedure_closed"]):?>procedure-closed<?endif?>
	<?if($arResult["new_element"]):?>     new-element     <?endif?>
>
	<?
	/* ------------------------------------------------------------------- */
	/* ------------------------- форма осн.инфы -------------------------- */
	/* ------------------------------------------------------------------- */
	?>
	<h3><?=GetMessage("SP_BTR_FORM_TITLE")?></h3>
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
								$propertyObject = $arResult["main_form_props"][$fieldType][$property];
								// интервалы дат
								if($property == 'trip_start_date' || $property == 'hotel_start_date')
									{
									$rowParams               = [];
									$startDatePropertyObject = $propertyObject;
									if($property == 'trip_start_date')  $endDatePropertyObject = $arResult["main_form_props"][$fieldType]["trip_end_date"];
									if($property == 'hotel_start_date') $endDatePropertyObject = $arResult["main_form_props"][$fieldType]["hotel_end_date"];

									if($property == 'trip_start_date')  $rowParams["TITLE"] = GetMessage("SP_BTR_TRIP_INTERVAL_TITLE");
									if($property == 'hotel_start_date') $rowParams["TITLE"] = GetMessage("SP_BTR_HOTEL_INTERVAL_TITLE");

									if($property == 'trip_start_date')  $rowParams["NAME"] = 'trip_interval';
									if($property == 'hotel_start_date') $rowParams["NAME"] = 'hotel_interval';

									if
										(
										$startDatePropertyObject->GetAttributes()["required"] == 'on'
										&&
										$endDatePropertyObject->GetAttributes()["required"] == 'on'
										)
										$rowParams["REQUIRED"] = 'on';

									if
										(
										$property == 'hotel_start_date'
										&&
										$arResult["main_form_props"][$fieldType]["hotel_need"]
										&&
										$arResult["main_form_props"][$fieldType]["hotel_need"]->GetValue() == 'N'
										)
										$rowParams["HIDDEN"] = 'Y';

									if($startDatePropertyObject && $endDatePropertyObject)
										$APPLICATION->IncludeComponent
											(
											"silta_framework:form_elements.property_row", '',
												[
												"FIELD_TYPE"             => $fieldType,
												"ROW_PARAMS"             => $rowParams,
												"FIELD_COMPONENT_NAME"   => 'silta_procedures:business_trip.date_interval_field',
												"FIELD_COMPONENT_PARAMS" => 
													[
													"FIELD_TYPE"       => $fieldType,
													"PROPERTY_START"   => $startDatePropertyObject,
													"PROPERTY_END"     => $endDatePropertyObject,
													"INPUT_NAME_START" => $arResult["input_name"]["main_form"].'['.$startDatePropertyObject->GetName().']',
													"INPUT_NAME_END"   => $arResult["input_name"]["main_form"].'['.$endDatePropertyObject  ->GetName().']',
													"ELEMENT_OBJECT"   => $startDatePropertyObject->GetElementObject()
													]
												]
											);
									}
								// остальное
								else
									{
									$rowParams   = [];
									$fieldParams = ["INPUT_NAME" => $arResult["input_name"]["main_form"].'['.$property.']'];
									if($property == 'user_department') $rowParams["SPACE"] = 'bottom';
									if($property == 'hotel_need')      $fieldParams["ATTR"] = 'hotel-need-triger';

									$APPLICATION->IncludeComponent
										(
										"silta_framework:form_elements.property_row", '',
											[
											"FIELD_TYPE"      => $fieldType,
											"PROPERTY_OBJECT" => $propertyObject,
											"ROW_PARAMS"      => $rowParams,
											"FIELD_PARAMS"    => $fieldParams
											]
										);
									}
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
</div>