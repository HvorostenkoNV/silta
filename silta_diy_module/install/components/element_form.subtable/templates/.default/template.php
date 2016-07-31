<?foreach($arResult["elements"] as $title => $elementObject):?>
<div class="silta-diy-module-subtable-element">
	<div head><?=$title?></div>
	<div body>
		<?
		$APPLICATION->IncludeComponent
			(
			"silta_diy_module:element_form.edit_form", '',
				[
				"ELEMENT_OBJECT"    => $elementObject,
				"FORM_PROPS"        => $arResult["form_props"],
				"FORM_PROPS_BUFFER" => $arResult["form_props_buffer"],
				"LINKS"             => $arResult["links"]
				]
			);
		?>
	</div>
</div>
<?endforeach?>

<?if($arResult["new_element"]):?>
<div class="silta-diy-module-subtable-element" new-element>
	<div head>
		<?
		$newElementTitle = GetMessage('SDM_EFS_NEW_ELEMENT_'.$arResult["work_table"]);
		if(!$newElementTitle) $newElementTitle = GetMessage("SDM_EFS_NEW_ELEMENT_DEFAULT");
		echo $newElementTitle;
		?>
		<span hide-form></span>
	</div>
	<div body>
		<?
		$APPLICATION->IncludeComponent
			(
			"silta_diy_module:element_form.edit_form", '',
				[
				"ELEMENT_OBJECT"    => $arResult["new_element"],
				"FORM_PROPS"        => $arResult["form_props"],
				"FORM_PROPS_BUFFER" => $arResult["form_props_buffer"],
				"LINKS"             => $arResult["links"]
				]
			);
		?>
	</div>
</div>

<div class="silta-diy-module-subtable-buttons">
	<?
	$addButtonTitle = GetMessage('SDM_EFS_ADD_ELEMENT_BUTTON_'.$arResult["work_table"]);
	if(!$addButtonTitle) $addButtonTitle = GetMessage("SDM_EFS_ADD_ELEMENT_BUTTON_DEFAULT");
	$APPLICATION->IncludeComponent
		(
		"silta_framework:form_elements.button", '',
			[
			"TITLE" => $addButtonTitle,
			"IMG"   => $templateFolder.'/images/add_element.png',
			"NAME"  => 'silta-diy-module-subtable-add-button'
			]
		);
	?>
</div>
<?endif?>