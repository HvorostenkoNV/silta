<?
$APPLICATION->IncludeComponent('silta_framework:form_elements.alert_window');
$APPLICATION->AddHeadScript('/bitrix/js/silta_framework/table_fixed_elements.js');
/* ============================================================================================= */
/* ========================================= ПЕРЕМЕННЫЕ ======================================== */
/* ============================================================================================= */
// ширина таблицы (кол-во ячеек)
$tableWidth = count($arResult["table_hat"]);
if(count($arResult["multiply_changing_types"])) $tableWidth += 1;
// список множ.изменений
foreach($arResult["multiply_changing_types"] as $type)
	$multiplyChangingsList[$type] = GetMessage('SDM_TL_MS_LIST_'.ToUpper($type));
// строка оповещения
if($arResult["success_changings"]["elements_count"])
	{
	$alertText = GetMessage("SDM_TL_MS_OPERATION_RESULT");
	$alertText = str_replace('#ELEMENTS_COUNT#', $arResult["success_changings"]["elements_count"],                         $alertText);
	$alertText = str_replace('#SUCCESS_COUNT#',  $arResult["success_changings"]["success_count"],                          $alertText);
	$alertText = str_replace('#OPERATION_TYPE#', $multiplyChangingsList[$arResult["success_changings"]["operation_type"]], $alertText);
	}
/* ============================================================================================= */
/* ===================================== ВЫВОД ОПОВЕЩЕНИЯ ====================================== */
/* ============================================================================================= */
?>
<?if($alertText):?>
<script>
	CallAlertWindow
		({
		"text"           : '<?=$alertText?>',
		"closeButtonText": '<?=GetMessage("SDM_TL_MS_OPERATION_RESULT_CLOSE_BUTTON")?>'
		})
</script>
<?endif?>
<?
/* ============================================================================================= */
/* =========================================== ФОРМА =========================================== */
/* ============================================================================================= */
?>
<form method="post"><table class="silta-diy-module-table-list">
	<?
	/* ------------------------------------------------------------------- */
	/* -------------------------- шапка таблицы -------------------------- */
	/* ------------------------------------------------------------------- */
	?>
	<thead><tr>
		<?if(count($arResult["multiply_changing_types"])):?>
		<th>
			<?$APPLICATION->IncludeComponent("silta_framework:form_elements.checkbox", '', ["ATTR" => 'multi-checker'])?>
		</th>
		<?endif?>

		<?foreach($arResult["table_hat"] as $infoArray):?>
		<th sort-type="<?=$infoArray["checked"]?>">
			<button name="<?=$arResult["input_name"]["sorter"]?>" value="<?=$infoArray["value"]?>">
				<?=$infoArray["title"]?>
			</button>
		</th>
		<?endforeach?>
	</tr></thead>
	<?
	/* ------------------------------------------------------------------- */
	/* -------------------------- футер таблицы -------------------------- */
	/* ------------------------------------------------------------------- */
	?>
	<tfoot><tr><td colspan="<?=$tableWidth?>"><table><tr>
		<?
		/* ----------------------------------------- */
		/* ---------- групповое изменение ---------- */
		/* ----------------------------------------- */
		?>
		<?if(count($arResult["multiply_changing_types"])):?>
		<td><table multi-editor>
			<tr>
				<th><?=GetMessage("SDM_TL_MS_CHANGER")?>:</th>
				<td>
					<?
					$APPLICATION->IncludeComponent
						(
						"silta_framework:form_elements.select", '',
							[
							"INPUT_NAME" => $arResult["input_name"]["changer"],
							"LIST"       => $multiplyChangingsList,
							"ATTR"       => 'multi-changer'
							]
						)
					?>
				</td>
			</tr>
			<tr>
				<th><?=GetMessage("SDM_TL_MS_ELEMENTS_SELECTED")?>:</th>
				<td elements-count></td>
			</tr>
			<tr>
				<td colspan="2">
					<?
					$APPLICATION->IncludeComponent
						(
						"silta_framework:form_elements.button", '',
							[
							"TITLE"        => GetMessage("SDM_TL_MS_APPLY_BUTTON"),
							"NAME"         => $arResult["input_name"]["submit"],
							"IMG"          => $templateFolder.'/images/multi_change_apply.png',
							"CONFIRM_TEXT" => GetMessage("SDM_TL_MS_APPLY_BUTTON_ASK"),
							"ATTR"         => 'multi-edition-submit-button',
							"HIDDEN"       => 'Y'
							]
						);
					$APPLICATION->IncludeComponent
						(
						"silta_framework:form_elements.button", '',
							[
							"TITLE" => GetMessage("SDM_TL_MS_CANCEL_BUTTON"),
							"NAME"  => 'silta-diy-module-table-list-multi-change-cancel',
							"IMG"   => $templateFolder.'/images/multi_change_cancel.png'
							]
						);
					?>
				</td>
			</tr>
		</table></td>
		<?endif?>
		<?
		/* ----------------------------------------- */
		/* --------------- навигация --------------- */
		/* ----------------------------------------- */
		?>
		<td><table navigator>
			<tr>
				<td><?=GetMessage("SDM_TL_NAV_ELEMENT_COUNT")?>:</td>
				<th><?=$arResult["table_foot"]["element_count"]?></th>
			</tr>
			<tr>
				<td><?=GetMessage("SDM_TL_NAV_ELEMENTS_ON_PAGE")?>:</td>
				<th><?=$arResult["table_foot"]["elements_on_page"]?></th>
			</tr>
			<tr>
				<td><?=GetMessage("SDM_TL_NAV_PAGES")?>:</td>
				<th>
					<?foreach($arResult["table_foot"]["navigation_pages"] as $infoArray):?>
						<?if($infoArray["value"]):?>
						<button
							name="<?=$arResult["input_name"]["navigation"]?>"
							value="<?=$infoArray["value"]?>"
							<?if($infoArray["checked"]):?>checked<?endif?>
						>
							<?=$infoArray["value"]?>
						</button>
						<?endif?>

						<?if($infoArray["space"]):?>
						<span>...</span>
						<?endif?>
					<?endforeach?>
				</th>
			</tr>
		</table></td>
	</tr></table></td></tr></tfoot>
	<?
	/* ------------------------------------------------------------------- */
	/* -------------------------- тело таблицы --------------------------- */
	/* ------------------------------------------------------------------- */
	?>
	<tbody>
		<?
		/* ----------------------------------------- */
		/* ----------- проход по строкам ----------- */
		/* ----------------------------------------- */
		?>
		<?foreach($arResult["table_body"] as $elementId => $propsArray):?>
		<tr>
			<?if(count($arResult["multiply_changing_types"])):?>
			<td>
				<?
				$APPLICATION->IncludeComponent
					(
					"silta_framework:form_elements.checkbox", '',
						[
						"ATTR"       => 'element-checker',
						"INPUT_NAME" => $arResult["input_name"]["elements"].'[]',
						"VALUE"      => $elementId
						]
					)
				?>
			</td>
			<?endif?>

			<?foreach($propsArray as $propertyObject):?>
			<td>
				<?
				$APPLICATION->IncludeComponent
					(
					"silta_diy_module:property_field", '',
						[
						"WORK_TABLE"      => $arResult["work_table"],
						"LINKS"           => $arResult["links"],
						"FIELD_TYPE"      => 'read',
						"PROPERTY_OBJECT" => $propertyObject
						]
					)
				?>
			</td>
			<?endforeach?>
		</tr>
		<?endforeach?>
		<?
		/* ----------------------------------------- */
		/* ------------- пустая строка ------------- */
		/* ----------------------------------------- */
		?>
		<?if(!$arResult["table_body"]):?>
		<tr>
			<?if(count($arResult["multiply_changing_types"])):?>
			<td></td>
			<?endif?>
			<td colspan="<?=$tableWidth?>">
				<?=GetMessage("SDM_TL_TABLE_NO_ELEMENTS")?>
			</td>
		</tr>
		<?endif?>
	</tbody>
</table></form>