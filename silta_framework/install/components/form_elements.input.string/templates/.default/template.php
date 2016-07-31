<?
if($arResult["mask"]) $APPLICATION->AddHeadScript($templateFolder.'/input_mask.js');
// Обязательное наличие аттрибута silta-form-element="input-string" !!!
?>

<input
	<?if($arResult["value"]):?>      value="<?=$arResult["value"]?>"            <?endif?>
	<?if($arResult["input_name"]):?> name="<?=$arResult["input_name"]?>"        <?endif?>
	<?if($arResult["placeholder"]):?>placeholder="<?=$arResult["placeholder"]?>"<?endif?>
	<?if($arResult["mask"]):?>       set-mask="<?=$arResult["mask"]?>"          <?endif?>

	type="text"
	size="<?=$arResult["size"]?>"
	silta-form-element="input-string"
	class="silta-form-input-string"
	<?=$arResult["attr"]?>
>