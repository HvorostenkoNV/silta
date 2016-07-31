<?
// Обязательное наличие аттрибута silta-form-element="select" !!!
?>

<span
	class="silta-form-select"
	style="width:<?=$arResult["width"]?>px"
>
	<select
		<?if($arResult["input_name"]):?>name="<?=$arResult["input_name"]?>"<?endif?>
		silta-form-element="select"
		style="width:<?=($arResult["width"] + 30)?>px"
		<?=$arResult["attr"]?>
	>
		<?if($arResult["empty_value"]):?>
		<option value="0">
			<?=GetMessage("SF_LIST_EMPTY_VALUE")?>
		</option>
		<?endif?>

		<?foreach($arResult["list"] as $listInfo):?>
		<option value="<?=$listInfo["value"]?>" <?if($listInfo["checked"]):?>selected<?endif?>>
			<?=$listInfo["title"]?>
		</option>
		<?endforeach?>
	</select>
</span>