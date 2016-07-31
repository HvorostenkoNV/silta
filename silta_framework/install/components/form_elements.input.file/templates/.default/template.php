<?
// Обязательное наличие аттрибута silta-form-element="input-file" !!!
?>

<?foreach($arResult["value"] as $fileId):?>
<div silta-form-input-file-element>
	<input name="<?=$arResult["input_name_uploaded"]?>" value="<?=$fileId?>" style="display: none">
	<?$APPLICATION->IncludeComponent('silta_framework:form_elements.link.iblock_file',   '', ["FILE_ID" => $fileId])?>
	<?$APPLICATION->IncludeComponent('silta_framework:form_elements.manage_row_buttons', '', ["TYPE" => 'remove'])?>
</div>
<?endforeach?>

<div>
	<input
		<?if($arResult["input_name"]):?>         name="<?=$arResult["input_name"]?>"                        <?endif?>
		<?if($arResult["input_name_uploaded"]):?>input-name-uploaded="<?=$arResult["input_name_uploaded"]?>"<?endif?>

		type="file"
		silta-form-element="input-file"
		class="silta-form-input-file"
		<?=$arResult["attr"]?>
	>
	<?if($arResult["multiply"] == 'Y'):?>
	<?$APPLICATION->IncludeComponent("silta_framework:form_elements.manage_row_buttons", '', ["TYPE" => 'add', "CLEAR_FORM" => 'N'])?>
	<?endif?>
</div>