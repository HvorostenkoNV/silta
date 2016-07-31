<?
// Обязательное наличие аттрибута silta-form-element="checkbox" !!!
?>

<input
	<?if($arResult["value"]):?>     value="<?=$arResult["value"]?>"    <?endif?>
	<?if($arResult["input_name"]):?>name="<?=$arResult["input_name"]?>"<?endif?>
	<?if($arResult["checked"]):?>   checked                            <?endif?>

	type="checkbox"
	silta-form-element="checkbox"
	<?=$arResult["attr"]?>
	style="display: none"
>
<span
	class="silta-form-checkbox"
	<?if($arResult["checked"]):?>checked<?endif?>
>
</span>
<?=$arResult["title"]?>