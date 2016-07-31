<?
/* -------------------------------------------------------------------- */
/* ------------------------ примененный фильтр ------------------------ */
/* -------------------------------------------------------------------- */
?>
<?if(count($arResult["read_form_props"])):?>
<table id="silta-diy-module-table-filter-read">
	<col width="30%"><col width="70%">

	<tr><th colspan="2" form-hat>
		<?=GetMessage("SDM_TF_APPLIED_FILTER")?>
	</th></tr>

	<?
	foreach($arResult["read_form_props"] as $propertyObject)
		$APPLICATION->IncludeComponent
			(
			"silta_diy_module:property_row", '',
				[
				"FIELD_TYPE"      => 'read',
				"PROPERTY_OBJECT" => $propertyObject
				]
			);
	?>
</table>
<?endif?>
<?
/* -------------------------------------------------------------------- */
/* --------------------------- форма фильтра -------------------------- */
/* -------------------------------------------------------------------- */
?>
<form id="silta-diy-module-table-filter-write" method="post">
	<table>
		<col style="width:30%"><col>

		<tr><th colspan="2" form-hat>
			<?=GetMessage("SDM_TF_FILTER")?>
		</th></tr>

		<?
		foreach($arResult["write_form_props"] as $propertyObject)
			$APPLICATION->IncludeComponent
				(
				"silta_diy_module:property_row", '',
					[
					"FIELD_TYPE"      => 'write',
					"PROPERTY_OBJECT" => $propertyObject,
					"INPUT_NAME"      => $arResult["input_name"]["form"]
					]
				);
		?>

		<tr><th colspan="2" button-cell>
			<?
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"NAME"  => $arResult["input_name"]["submit_button"],
					"IMG"   => $templateFolder.'/images/filter_apply.png',
					"TITLE" => GetMessage("SDM_TF_APPLY_BUTTON"),
					"TAG"   => 'button'
					]
				);
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"NAME"  => $arResult["input_name"]["cancel_button"],
					"IMG"   => $templateFolder.'/images/filter_cancel.png',
					"TITLE" => GetMessage("SDM_TF_CANCEL_BUTTON"),
					"TAG"   => 'button'
					]
				);
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"NAME"  => 'silta-diy-module-table-filter-close',
					"TITLE" => GetMessage("SDM_TF_CLOSE_BUTTON")
					]
				);
			?>
		</th></tr>
	</table>
</form>
<?
$APPLICATION->IncludeComponent
	(
	"silta_framework:form_elements.button", '',
		[
		"IMG"   => $templateFolder.'/images/filter_apply.png',
		"NAME"  => 'silta-diy-module-table-filter-open',
		"TITLE" => GetMessage("SDM_TF_OPEN_BUTTON")
		]
	);
?>