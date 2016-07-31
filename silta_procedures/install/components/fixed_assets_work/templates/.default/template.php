<?
/* -------------------------------------------------------------------- */
/* ------------------------- процедура заказа ------------------------- */
/* -------------------------------------------------------------------- */
if($arResult["page"] == 'provision_application'):
?>
<table id="sp-faw-provision-application-page">
	<col width="70%"><col>
	<tbody>
		<tr>
			<td>
				<?
				$APPLICATION->IncludeComponent("silta_procedures:fixed_assets_work.application_buttons",              '', ["ELEMENT_OBJECT" => $arResult["element_object"], "DELETE_REDIRECT"   => $arResult["delete_redirect"]]);
				$APPLICATION->IncludeComponent("silta_procedures:fixed_assets_work.provision_application.status_bar", '', ["ELEMENT_OBJECT" => $arResult["element_object"], "STAGE_DESCRIPTION" => 'Y']);
				$APPLICATION->IncludeComponent
					(
					"silta_procedures:fixed_assets_work.provision_application.form",
					'',
						[
						"ELEMENT_OBJECT"                => $arResult["element_object"],
						"SAVE_REDIRECT"                 => $arResult["save_redirect"],
						"PURCHASE_APPLICATION_LINK"     => $arResult["purchase_application_link"],
						"DISPLACEMENT_APPLICATION_LINK" => $arResult["displacement_application_link"]
						]
					);
				?>
			</td>
			<td background-cell>
				<div></div>
			</td>
		</tr>
	<tbody>
</table>
<?endif?>
<?
/* -------------------------------------------------------------------- */
/* ----------------------- процедура перемещения ---------------------- */
/* -------------------------------------------------------------------- */
if($arResult["page"] == 'displacement_application'):
?>
<table id="sp-faw-displacement-application-page">
	<col width="70%"><col>
	<tbody>
		<tr>
			<td>
				<?
				$APPLICATION->IncludeComponent("silta_procedures:fixed_assets_work.application_buttons",                 '', ["ELEMENT_OBJECT" => $arResult["element_object"]]);
				$APPLICATION->IncludeComponent("silta_procedures:fixed_assets_work.displacement_application.status_bar", '', ["ELEMENT_OBJECT" => $arResult["element_object"], "STAGE_DESCRIPTION" => 'Y']);
				$APPLICATION->IncludeComponent("silta_procedures:fixed_assets_work.displacement_application.form",       '', ["ELEMENT_OBJECT" => $arResult["element_object"]]);
				?>
			</td>
			<td background-cell>
				<div></div>
			</td>
		</tr>
	<tbody>
</table>
<?endif?>