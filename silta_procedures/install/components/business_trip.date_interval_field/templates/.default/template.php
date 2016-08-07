<?
/* -------------------------------------------------------------------- */
/* ------------------------------ чтение ------------------------------ */
/* -------------------------------------------------------------------- */
?>
<?if($arResult["type"] == 'read'):?>
	<?foreach($arResult["value"] as $value):?>
	<?
	$daysTitle = GetMessage("SP_BTR_DAYS_TITLE3");
	if($value["count"] == 1) $daysTitle = GetMessage("SP_BTR_DAYS_TITLE1");
	if(in_array($value["count"], [2, 3, 4])) $daysTitle = GetMessage("SP_BTR_DAYS_TITLE2");
	?>
	<div><?=$value["start"]?> - <?=$value["end"]?> <b><?=$value["count"]?> <?=$daysTitle?></b></div>
	<?endforeach?>
<?endif?>
<?
/* -------------------------------------------------------------------- */
/* ------------------------------ запись ------------------------------ */
/* -------------------------------------------------------------------- */
?>
<?if($arResult["type"] == 'write'):?>
	<?foreach($arResult["value"] as $index => $value):?>
	<div>
		<?
		$APPLICATION->IncludeComponent
			(
			"silta_framework:form_elements.input.date", '',
				[
				"VALUE"      => $value["start"],
				"INPUT_NAME" => $arResult["input_name_start"]
				]
			);
		?>
		 - 
		<?
		$APPLICATION->IncludeComponent
			(
			"silta_framework:form_elements.input.date", '',
				[
				"VALUE"      => $value["end"],
				"INPUT_NAME" => $arResult["input_name_end"]
				]
			);
		?>
		<?
		$buttonType = 'remove';
		if(!$arResult["value"][$index+1]) $buttonType = 'add';
		$APPLICATION->IncludeComponent("silta_framework:form_elements.manage_row_buttons", '', ["TYPE" => $buttonType]);
		?>
	</div>
	<?endforeach?>
<?endif?>