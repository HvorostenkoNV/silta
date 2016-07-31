<?
$title = GetMessage("SDM_AEB_BUTTON_".$arResult["table"]);
if(!$title) $title = GetMessage("SDM_AEB_BUTTON_DEFAULT");

if($arResult["link"] && $title)
	$APPLICATION->IncludeComponent
		(
		"silta_framework:form_elements.button", '',
			[
			"IMG"   => $templateFolder.'/images/add_element.png',
			"TITLE" => $title,
			"LINK"  => $arResult["link"]
			]
		)
?>