<?if($arResult["title"] && $arResult["link"]):?>
	<a href="<?=$arResult["link"]?>" <?if($arResult["new_window"]):?>target="_blank"<?endif?>>
		<?=$arResult["title"]?>
	</a>
<?endif?>