<?
/* -------------------------------------------------------------------- */
/* ------------------------- страница элемента ------------------------ */
/* -------------------------------------------------------------------- */
if($arResult["page"] == 'element_page'):
?>
<table id="sp-btr-element-page">
	<col width="70%"><col>
	<tbody>
		<tr>
			<td>
				<?
				$APPLICATION->IncludeComponent("silta_procedures:business_trip.status_bar", '', ["ELEMENT_OBJECT" => $arResult["element_object"], "STAGE_DESCRIPTION" => 'Y']);
				$APPLICATION->IncludeComponent("silta_procedures:business_trip.form",       '', ["ELEMENT_OBJECT" => $arResult["element_object"], "SAVE_REDIRECT" => $arResult["save_redirect"], "DELETE_REDIRECT" => $arResult["delete_redirect"]]);
				?>
			</td>
			<td background-cell>
				<div></div>
			</td>
		</tr>
	<tbody>
</table>
<?endif?>