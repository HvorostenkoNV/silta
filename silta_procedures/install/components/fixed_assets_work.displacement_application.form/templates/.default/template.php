<div
	class="sp-faw-displacement-application-form"
	<?if($arResult["procedure_closed"]):?>procedure-closed<?endif?>
	<?if($arResult["new_element"]):?>     new-element     <?endif?>
>
	<?
	/* ------------------------------------------------------------------- */
	/* ------------------------- форма осн.инфы -------------------------- */
	/* ------------------------------------------------------------------- */
	?>
	<h3><?=GetMessage("SP_FAW_DISPL_APPLC_FORM_TITLE")?></h3>
	<form method="post" enctype="multipart/form-data">
		<?
		/* ------------------------------------------ */
		/* ----------------- форма ------------------ */
		/* ------------------------------------------ */
		?>
		<?foreach(["read", "write"] as $fieldType):?>
			<?if(count($arResult["form_props"][$fieldType])):?>
				<table form-type="<?=$fieldType?>">
					<col width="30%"><col width="70%">
					<tbody>
						<?if($arResult["links"]["provision_application"]):?>
						<tr>
							<td></td>
							<td><a href="<?=$arResult["links"]["provision_application"]?>" target="_blank"><?=GetMessage("SP_FAW_DISPL_APPLC_PROV_APPLIC_LINK")?></a></td>
						</tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<?endif?>

						<?
						foreach($arResult["form_props"][$fieldType] as $propertyObject)
							{
							$fieldParams = ["INPUT_NAME" => $arResult["input_name"]["main_form"].'['.$propertyObject->GetName().']'];
							if($propertyObject->GetName() == 'fixed_asset') $fieldParams["PROPS"] = ["name", "group", "user"];

							$APPLICATION->IncludeComponent
								(
								"silta_framework:form_elements.property_row", '',
									[
									"FIELD_TYPE"      => $fieldType,
									"PROPERTY_OBJECT" => $propertyObject,
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
		if(count($arResult["form_props"]["write"]) && !$arResult["new_element"])
			{
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"TITLE"        => GetMessage("SP_FAW_DISPL_APPLC_FORM_EDIT_BUTTON"),
					"IMG"          => $templateFolder.'/images/edit.png',
					"IMG_POSITION" => 'left',
					"ATTR"         => 'edit-button'
					]
				);
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"TITLE"  => GetMessage("SP_FAW_DISPL_APPLC_FORM_CANCEL_BUTTON"),
					"ATTR"   => 'cancel-button',
					"HIDDEN" => 'Y'
					]
				);
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"TITLE"               => GetMessage("SP_FAW_DISPL_APPLC_FORM_APPLY_BUTTON"),
					"VALIDATE_FORM_ALERT" => GetMessage("SP_FAW_DISPL_APPLC_FORM_SUBMIT_ALERT"),
					"IMG"                 => $templateFolder.'/images/apply.png',
					"IMG_POSITION"        => 'left',
					"NAME"                => $arResult["button_names"]["main_form_submit"],
					"ATTR"                => 'submit-button',
					"HIDDEN"              => 'Y'
					]
				);
			}
		// кнопка "создать элемент"
		if(count($arResult["form_props"]["write"]) && $arResult["new_element"])
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"TITLE"               => GetMessage("SP_FAW_DISPL_APPLC_FORM_CREATE_BUTTON"),
					"VALIDATE_FORM_ALERT" => GetMessage("SP_FAW_DISPL_APPLC_FORM_SUBMIT_ALERT"),
					"IMG"                 => $templateFolder.'/images/create.png',
					"IMG_POSITION"        => 'left',
					"NAME"                => $arResult["button_names"]["main_form_submit"]
					]
				);
		?>
	</form>
</div>