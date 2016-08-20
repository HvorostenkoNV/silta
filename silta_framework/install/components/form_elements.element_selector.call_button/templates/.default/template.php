<?
$APPLICATION->AddHeadString('<script>SElementSelectorFolder = "http://'.$_SERVER['SERVER_NAME'].$templateFolder.'";</script>');
$APPLICATION->AddHeadScript('/bitrix/js/silta_framework/draggable.js');
$APPLICATION->IncludeComponent('silta_framework:form_elements.waiting_screen');
$APPLICATION->IncludeComponent('silta_framework:form_elements.element_selector.element');
// Обязательное наличие аттрибута silta-form-element="element-selector" !!!

// фильтр-строка
foreach($arResult["filter"] as $index => $value)
	{
	if(is_array($value)) $value = implode('/', $value);
	$filter[] = $index.':'.$value;
	}
$filter = implode(';', $filter);
?>

<span
	<?if($arResult["input_name"]):?>  input-name="<?=$arResult["input_name"]?>"    <?endif?>
	<?if($arResult["table"]):?>       table="<?=$arResult["table"]?>"              <?endif?>
	<?if(count($arResult["props"])):?>props="<?=implode('|', $arResult["props"])?>"<?endif?>
	<?if($filter):?>                  filter="<?=$filter?>"                        <?endif?>

	multiply="<?=$arResult["multiply"]?>"

	silta-form-element="element-selector"
	class="silta-form-element-selector-call-button"
	id="element-selector-call-button-<?=rand()?>"
	<?=$arResult["attr"]?>
>
	<?=GetMessage("SF_ES_BUTTON_TITLE")?>
	<span icon></span>
</span>

<?foreach($arResult["value"] as $elementId):?>
<div silta-form-element-selector-checked-element>
	<input name="<?=$arResult["input_name"]?>" value="<?=$elementId?>" style="display: none">
	<?
	$APPLICATION->IncludeComponent
		(
		'silta_framework:form_elements.element_selector.element', '',
			[
			"TABLE"      => $arResult["table"],
			"PROPS"      => $arResult["props"],
			"ELEMENT_ID" => $elementId
			]
		)
	?>
	<div delete-element></div>
</div>
<?endforeach?>