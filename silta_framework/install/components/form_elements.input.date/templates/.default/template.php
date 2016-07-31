<?
$APPLICATION->AddHeadScript   ($templateFolder.'/calendar.js');
$APPLICATION->SetAdditionalCSS($templateFolder.'/calendar.css');
// Обязательное наличие аттрибута silta-form-element="input-date" !!!
?>

<input
	<?if($arResult["value"]):?>     value="<?=$arResult["value"]?>"    <?endif?>
	<?if($arResult["input_name"]):?>name="<?=$arResult["input_name"]?>"<?endif?>

	date="<?=$arResult["date"]?>"
	time="<?=$arResult["time"]?>"

	<?if($arResult["start_date"]):?>
	start-date="<?=$arResult["start_date"]?>" readonly
	<?endif?>

	type="text"
	size="20"
	set-date-picker
	silta-form-element="input-date"
	class="silta-form-input-date"
	<?=$arResult["attr"]?>
>