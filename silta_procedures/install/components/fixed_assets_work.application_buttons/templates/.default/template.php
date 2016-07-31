<form method="post">
	<?
	$APPLICATION->IncludeComponent
		(
		"silta_framework:form_elements.button", '',
			[
			"TITLE"        => GetMessage("SP_FAW_APP_BUTTONS_HOME"),
			"IMG"          => $templateFolder.'/images/home.png',
			"IMG_POSITION" => 'left',
			"LINK"         => $arResult["home_link"]
			]
		);
	if($arResult["delete_access"])
		$APPLICATION->IncludeComponent
			(
			"silta_framework:form_elements.button", '',
				[
				"TITLE"        => GetMessage("SP_FAW_APP_BUTTONS_DELETE"),
				"IMG"          => $templateFolder.'/images/delete.png',
				"IMG_POSITION" => 'left',
				"NAME"         => $arResult["delete_button_name"],
				"TAG"          => 'button',
				"CONFIRM_TEXT" => GetMessage("SP_FAW_APP_BUTTONS_DELETE_ASK")
				]
			);
	?>
</form>