<?
// Обязательное наличие аттрибута silta-form-property-row = имя свойства
/* ------------------------------------------------------------------- */
/* -------------------- буфферная строка - сверху -------------------- */
/* ------------------------------------------------------------------- */
?>
<?if($arResult["space"] == 'top'):?>
<tr
	space-row="<?=$arResult["prop_name"]?>"
	<?=$arResult["attr"]?>
	<?if($arResult["hidden"]):?>style="display: none"<?endif?>
>
	<td colspan="2">&nbsp;</td>
</tr>
<?endif?>
<?
/* ------------------------------------------------------------------- */
/* --------------------------- строка формы -------------------------- */
/* ------------------------------------------------------------------- */
?>
<tr
	silta-form-property-row="<?=$arResult["prop_name"]?>"
	class="silta-form-property-row"
	<?=$arResult["attr"]?>
	<?if($arResult["form_saving"]):?>form-saving="<?=$arResult["form_saving"]?>"<?endif?>
	<?if($arResult["hidden"]):?>     style="display: none"                      <?endif?>
>
	<th>
		<?=$arResult["row_title"]?><?if($arResult["required"]):?><span form-required-trigger="<?=$arResult["required"]?>"></span><?endif?>:
	</th>
	<td>
		<?
		$APPLICATION->IncludeComponent
			(
			$arResult["field_component_name"],
			$arResult["field_component_template"],
			$arResult["field_component_params"]
			);
		?>
	</td>
</tr>
<?
/* ------------------------------------------------------------------- */
/* -------------------- буфферная строка - снизу --------------------- */
/* ------------------------------------------------------------------- */
?>
<?if($arResult["space"] == 'bottom'):?>
<tr
	space-row="<?=$arResult["prop_name"]?>"
	<?=$arResult["attr"]?>
	<?if($arResult["hidden"]):?>style="display: none"<?endif?>
>
	<td colspan="2">&nbsp;</td>
</tr>
<?endif?>