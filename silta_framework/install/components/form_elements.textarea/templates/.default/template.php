<?
// Обязательное наличие аттрибута silta-form-element="textarea" !!!
?>

<textarea
	<?if($arResult["input_name"]):?> name="<?=$arResult["input_name"]?>"        <?endif?>
	<?if($arResult["placeholder"]):?>placeholder="<?=$arResult["placeholder"]?>"<?endif?>

	cols="<?=$arResult["cols"]?>"
	rows="<?=$arResult["rows"]?>"

	silta-form-element="textarea"
	class="silta-form-textarea"
	<?=$arResult["attr"]?>
>
<?=$arResult["value"]?>
</textarea>