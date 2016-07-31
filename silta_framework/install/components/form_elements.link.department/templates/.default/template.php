<?if($arResult["id"] && $arResult["title"]):?>
<div>
	<a href="/company/structure.php?set_filter_structure=Y&amp;structure_UF_DEPARTMENT=<?=$arResult["id"]?>" target="_blank"><?=$arResult["title"]?></a>
</div>
<?endif?>