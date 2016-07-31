<?
$APPLICATION->IncludeComponent('silta_framework:form_elements.waiting_screen');
$APPLICATION->IncludeComponent('silta_framework:form_elements.alert_window');
?>

<?if($arResult["title"]):?>
	<<?=$arResult["tag"]?>
		<?if($arResult["name"]):?>                            name="<?=$arResult["name"]?>"  <?endif?>
		<?if($arResult["value"]):?>                           value="<?=$arResult["value"]?>"<?endif?>
		<?if($arResult["tag"] == 'button'):?>                 silta-form-submit-button       <?endif?>
		<?if($arResult["link"] && $arResult["tag"] == 'a'):?> href="<?=$arResult["link"]?>"  <?endif?>
		<?if($arResult["hidden"]):?>                          style="display: none"          <?endif?>

		<?if($arResult["validate_form_alert"]):?>
		form-validation-check="<?=$arResult["validate_form_alert"]?>|<?=GetMessage("SF_FE_BUTTONS_VFCB")?>"
		<?endif?>

		<?if($arResult["confirm_text"]):?>
		reask-action="<?=$arResult["confirm_text"]?>|<?=GetMessage("SF_FE_BUTTONS_CTAB")?>|<?=GetMessage("SF_FE_BUTTONS_CTCB")?>"
		<?endif?>

		class="silta-form-button"
		<?=$arResult["attr"]?>
	>
		<?if($arResult["img_position"] == 'right'):?><?=$arResult["title"]?>          <?endif?>
		<?if($arResult["img"]):?>                    <img src="<?=$arResult["img"]?>"><?endif?>
		<?if($arResult["img_position"] == 'left'):?> <?=$arResult["title"]?>          <?endif?>
	</<?=$arResult["tag"]?>>
<?endif?>