<?if($arResult["building_type"] == 'comment_table'):?>
	<table class="silta-diy-module-element-changings-table">
		<col width="30%"><col>
		<tbody>
			<?
			foreach($arResult["props"] as $propertyObject)
				$APPLICATION->IncludeComponent
					(
					"silta_framework:form_elements.property_row", '',
						[
						"FIELD_TYPE"      => 'read',
						"PROPERTY_OBJECT" => $propertyObject
						]
					);
			?>
		</tbody>
	</table>
<?endif?>

<?if($arResult["building_type"] == 'changings_table' && count($arResult["props_info"])):?>
	<table class="silta-diy-module-element-changings-table">
		<col width="30%"><col width="30%"><col><col width="30%">
		<tbody>
			<?foreach($arResult["props_info"] as $arrayInfo):?>
			<tr>
				<th><?=$arrayInfo["title"]?>:</th>
				<td>
					<?
					$APPLICATION->IncludeComponent
						(
						"silta_framework:form_elements.property_field", '',
							[
							"FIELD_TYPE"      => 'read',
							"PROPERTY_OBJECT" => $arrayInfo["old_property"]
							]
						);
					?>
				</td>
				<td> - </td>
				<td>
					<?
					$APPLICATION->IncludeComponent
						(
						"silta_framework:form_elements.property_field", '',
							[
							"FIELD_TYPE"      => 'read',
							"PROPERTY_OBJECT" => $arrayInfo["new_property"]
							]
						);
					?>
				</td>
			</tr>
			<?endforeach?>
		</tbody>
	</table>
<?endif?>