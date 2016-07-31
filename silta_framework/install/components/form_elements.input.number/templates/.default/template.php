<?
// Обязательное наличие аттрибута silta-form-element="input-number" !!!
?>

<input
	<?if($arResult["value"]):?>      value="<?=$arResult["value"]?>"            <?endif?>
	<?if($arResult["input_name"]):?> name="<?=$arResult["input_name"]?>"        <?endif?>
	<?if($arResult["placeholder"]):?>placeholder="<?=$arResult["placeholder"]?>"<?endif?>

	type="text"
	size="<?=$arResult["size"]?>"
	silta-form-element="input-number"
	class="silta-form-input-number"
	<?=$arResult["attr"]?>
>