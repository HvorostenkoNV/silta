<div
	class="sp-faw-provision-application-form"
	<?if($arResult["procedure_closed"]):?>procedure-closed<?endif?>
	<?if($arResult["new_element"]):?>     new-element     <?endif?>
>
	<?
	/* ------------------------------------------------------------------- */
	/* ------------------------- форма осн.инфы -------------------------- */
	/* ------------------------------------------------------------------- */
	?>
	<h3><?=GetMessage("SP_FAW_PROV_APPLC_FORM_TITLE")?></h3>
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
						<?
						foreach($arResult["form_props"][$fieldType] as $propertyObject)
							$APPLICATION->IncludeComponent
								(
								"silta_framework:form_elements.property_row", '',
									[
									"FIELD_TYPE"      => $fieldType,
									"PROPERTY_OBJECT" => $propertyObject,
									"FIELD_PARAMS"    => 
										[
										"INPUT_NAME" => $arResult["input_name"]["main_form"].'['.$propertyObject->GetName().']'
										]
									]
								);
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
					"TITLE"        => GetMessage("SP_FAW_PROV_APPLC_FORM_EDIT_BUTTON"),
					"IMG"          => $templateFolder.'/images/edit.png',
					"IMG_POSITION" => 'left',
					"ATTR"         => 'edit-button'
					]
				);
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"TITLE"  => GetMessage("SP_FAW_PROV_APPLC_FORM_CANCEL_BUTTON"),
					"ATTR"   => 'cancel-button',
					"HIDDEN" => 'Y'
					]
				);
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"TITLE"               => GetMessage("SP_FAW_PROV_APPLC_FORM_APPLY_BUTTON"),
					"VALIDATE_FORM_ALERT" => GetMessage("SP_FAW_PROV_APPLC_FORM_SUBMIT_ALERT"),
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
					"TITLE"               => GetMessage("SP_FAW_PROV_APPLC_FORM_CREATE_BUTTON"),
					"VALIDATE_FORM_ALERT" => GetMessage("SP_FAW_PROV_APPLC_FORM_SUBMIT_ALERT"),
					"IMG"                 => $templateFolder.'/images/create.png',
					"IMG_POSITION"        => 'left',
					"NAME"                => $arResult["button_names"]["main_form_submit"]
					]
				);
		?>
	</form>
	<?
	/* ------------------------------------------------------------------- */
	/* ----------------- форма визирования/ответственных ----------------- */
	/* ------------------------------------------------------------------- */
	?>
	<?if($arResult["application_condition"] == 'agreement_active' || $arResult["application_condition"] == 'responsible_work_active'):?>
	<h3>
		<?if($arResult["application_condition"] == 'agreement_active'):?>       <?=GetMessage("SP_FAW_PROV_APPLC_SIGN_TITLE")?><?endif?>
		<?if($arResult["application_condition"] == 'responsible_work_active'):?><?=GetMessage("SP_FAW_PROV_APPLC_RESP_TITLE")?><?endif?>
	</h3>
	<form method="post" enctype="multipart/form-data">
		<?
		/* ------------------------------------------ */
		/* ---------- кнопки ответственных ---------- */
		/* ------------------------------------------ */
		?>
		<?if($arResult["application_condition"] == 'responsible_work_active'):?>
			<?
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"TITLE"        => GetMessage("SP_FAW_PROV_APPLC_CREATE_PURCHASE_APPLICATION"),
					"IMG"          => $templateFolder.'/images/create_purchase_application.png',
					"IMG_POSITION" => 'left',
					"LINK"         => $arResult["links"]["create_purchase_application"],
					"ATTR"         => 'target="_blank"'
					]
				)
			?>
			<br>
			<?
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"TITLE"        => GetMessage("SP_FAW_PROV_APPLC_CREATE_DISPLACEMENT_APPLICATION"),
					"IMG"          => $templateFolder.'/images/create_displacement_application.png',
					"IMG_POSITION" => 'left',
					"LINK"         => $arResult["links"]["create_displacement_application"],
					"ATTR"         => 'target="_blank"'
					]
				)
			?>
			<br><br>
		<?endif?>
		<?
		/* ------------------------------------------ */
		/* ----------------- форма ------------------ */
		/* ------------------------------------------ */
		?>
		<table>
			<col width="30%"><col width="70%">
			<tbody>
				<?
				foreach($arResult["vising_props"] as $propertyObject)
					$APPLICATION->IncludeComponent
						(
						"silta_framework:form_elements.property_row", '',
							[
							"FIELD_TYPE"      => 'write',
							"PROPERTY_OBJECT" => $propertyObject,
							"FIELD_PARAMS"    => 
								[
								"INPUT_NAME" => $arResult["input_name"]["sign_form"].'['.$propertyObject->GetName().']'
								]
							]
						);
				?>
			</tbody>
		</table>
		<?
		/* ------------------------------------------ */
		/* ----------------- кнопки ----------------- */
		/* ------------------------------------------ */
		if($arResult["application_condition"] == 'agreement_active')
			{
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"TITLE"        => GetMessage("SP_FAW_PROV_APPLC_SIGN_CONFIRM"),
					"IMG"          => $templateFolder.'/images/sign_confirm.png',
					"IMG_POSITION" => 'left',
					"NAME"         => $arResult["button_names"]["sign_form_confirm"],
					"TAG"          => 'button'
					]
				);
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"TITLE"        => GetMessage("SP_FAW_PROV_APPLC_SIGN_REJECT"),
					"IMG"          => $templateFolder.'/images/sign_reject.png',
					"IMG_POSITION" => 'left',
					"NAME"         => $arResult["button_names"]["sign_form_reject"],
					"TAG"          => 'button'
					]
				);
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"TITLE"        => GetMessage("SP_FAW_PROV_APPLC_SIGN_RETURN"),
					"IMG"          => $templateFolder.'/images/sign_return.png',
					"IMG_POSITION" => 'left',
					"NAME"         => $arResult["button_names"]["sign_form_return"],
					"TAG"          => 'button'
					]
				);
			}
		if($arResult["application_condition"] == 'responsible_work_active')
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"TITLE"        => GetMessage("SP_FAW_PROV_APPLC_RESP_CLOSE"),
					"IMG"          => $templateFolder.'/images/app_close.png',
					"IMG_POSITION" => 'left',
					"NAME"         => $arResult["button_names"]["responsible_close"],
					"TAG"          => 'button'
					]
				);
		?>
	</form>
	<?endif?>
	<?
	/* ------------------------------------------------------------------- */
	/* ------------------------ связанные заявки ------------------------- */
	/* ------------------------------------------------------------------- */
	?>
	<?if(count($arResult["links"]["bind_appplications"])):?>
	<h3><?=GetMessage("SP_FAW_PROV_APPLC_BIND_APPS_TITLE")?></h3>
	<form>
		<table>
			<tbody>
				<?foreach($arResult["links"]["bind_appplications"] as $name => $link):?>
				<tr><td>
					<a href="<?=$link?>" target="_blank"><?=$name?></a>
				</td></tr>
				<?endforeach?>
			</tbody>
		</table>
	</form>
	<?endif?>
	<?
	/* ------------------------------------------------------------------- */
	/* ------------------------ таблица комментов ------------------------ */
	/* ------------------------------------------------------------------- */
	?>
	<?if(count($arResult["comments_table"]["info"])):?>
	<h3><?=GetMessage("SP_FAW_PROV_APPLC_COMMENTS_TITLE")?></h3>
	<form comments-table>
		<table>
			<col width="30%"><col width="70%">
			<tbody>
				<tr title-row>
					<?foreach($arResult["comments_table"]["titles"] as $title):?>
					<th><?=$title?></th>
					<?endforeach?>
				</tr>
				<?foreach($arResult["comments_table"]["info"] as $propsArray):?>
				<tr info-row>
					<?foreach($propsArray as $propertyObject):?>
					<td>
						<?$APPLICATION->IncludeComponent("silta_framework:form_elements.property_field", '', ["FIELD_TYPE" => 'read', "PROPERTY_OBJECT" => $propertyObject])?>
					</td>
					<?endforeach?>
				</tr>
				<?endforeach?>
			</tbody>
		</table>
	</form>
	<?endif?>
</div>