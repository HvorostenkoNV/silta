<?
/* -------------------------------------------------------------------- */
/* ----------------------- ИМПОРТ - ЭМУЛЯЦИЯ XML ---------------------- */
/* -------------------------------------------------------------------- */
?>
<?if($arResult["page_type"] == 'import_build_xml'):?>
	<?$APPLICATION->SetTitle(str_replace('#PROCEDURE_NAME#', $arResult["procedure_name"], GetMessage("SEM_TP_IMPORT_XML_CREATING_TITLE")))?>
	<?=ShowError(GetMessage("SEM_TP_IMPORT_XML_CREATING_EXPLAIN_TEXT"))?>
	<form method="post" enctype="multipart/form-data" class="sem-test-page-import-from">
		<table>
			<col width="25%"><col width="70%"><col width="5%">
			<?
			/* ------------------------------------------- */
			/* -------------- параметры XML -------------- */
			/* ------------------------------------------- */
			?>
			<tbody>
			<?foreach
				(
					[
						[
						"title"      => 'SEM_TP_IMPORT_XML_CREATING_XML_VERSION',
						"input_name" => $arResult["input_name"]["form_prefix"].'['.$arResult["input_name"]["xml_version"].']',
						"value"      => $arResult["xml_default_params"]["version"]
						],
						[
						"title"      => 'SEM_TP_IMPORT_XML_CREATING_XML_ENCODING',
						"input_name" => $arResult["input_name"]["form_prefix"].'['.$arResult["input_name"]["xml_encoding"].']',
						"value"      => $arResult["xml_default_params"]["encoding"]
						]
					]
				as $arrayInfo
				):
			?>
				<tr form-row>
					<th><?=GetMessage($arrayInfo["title"])?>:</th>
					<td><?$APPLICATION->IncludeComponent("silta_framework:form_elements.input.string", '', ["INPUT_NAME" => $arrayInfo["input_name"], "VALUE" => $arrayInfo["value"]])?></td>
					<td></td>
				</tr>
			<?endforeach?>
			</tbody>
			<?
			/* ------------------------------------------- */
			/* ----------- параметры процедуры ----------- */
			/* ------------------------------------------- */
			?>
			<?if(count($arResult["params"])):?>
			<tbody>
				<tr><th colspan="3">
					<div table-title><?=GetMessage("SEM_TP_IMPORT_XML_CREATING_PARAMS_TITLE")?></div>
				</th></tr>
				<?foreach($arResult["params"] as $param):?>
				<tr form-row>
					<th><?=$param?>:</th>
					<td>
						<div>
							<?
							$inputName = $arResult["input_name"]["form_prefix"].'['.$arResult["input_name"]["params_prefix"].']['.$param.']';
							$APPLICATION->IncludeComponent("silta_framework:form_elements.input.string",       '', ["INPUT_NAME" => $inputName.'[]']);
							$APPLICATION->IncludeComponent("silta_framework:form_elements.manage_row_buttons", '', ["TYPE" => "add"]);
							?>
						</div>
					</td>
					<td></td>
				</tr>
				<?endforeach?>
			</tbody>
			<?endif?>
			<?
			/* ------------------------------------------- */
			/* ----------- элементы процедуры ------------ */
			/* ------------------------------------------- */
			?>
			<?if(count($arResult["props"])):?>
			<tbody>
				<tr><th colspan="3">
					<div table-title><?=GetMessage("SEM_TP_IMPORT_XML_CREATING_PROPS_TITLE")?></div>
				</th></tr>
			</tbody>
			<tbody>
				<tr><th colspan="3">
					<div element-title><?=GetMessage("SEM_TP_IMPORT_XML_CREATING_ELEMENT_TITLE")?></div>
				</th></tr>
				<?foreach($arResult["props"] as $index => $prop):?>
				<tr form-row>
					<th><?=$prop?>:</th>
					<td>
						<div>
							<?
							$inputName = $arResult["input_name"]["form_prefix"].'['.$arResult["input_name"]["elements_prefix"].']['.$arResult["input_name"]["element_prefix"].']['.$prop.']';
							$APPLICATION->IncludeComponent("silta_framework:form_elements.input.string",       '', ["INPUT_NAME" => $inputName.'[]']);
							$APPLICATION->IncludeComponent("silta_framework:form_elements.manage_row_buttons", '', ["TYPE" => "add"]);
							?>
						</div>
					</td>
					<?if($index == '0'):?>
					<td rowspan="<?=count($arResult["props"])?>">
						<?
						$APPLICATION->IncludeComponent
							(
							"silta_framework:form_elements.manage_row_buttons", '',
								[
								"TYPE"          => "add",
								"PARENT"        => 'tbody',
								"RENAME_INPUTS" => $arResult["input_name"]["element_prefix"]
								]
							)
						?>
					</td>
					<?endif?>
				</tr>
				<?endforeach?>
			</tbody>
			<?endif?>
		</table>
		<?
		$APPLICATION->IncludeComponent
			(
			"silta_framework:form_elements.button", '',
				[
				"NAME"  => $arResult["input_name"]["build_xml"],
				"TITLE" => GetMessage("SEM_TP_IMPORT_XML_CREATING_SUBMIT_BUTTON"),
				"TAG"   => 'button'
				]
			);
		?>
	</form>
<?endif?>
<?
/* -------------------------------------------------------------------- */
/* -------------------------- ИМПОРТ - ОБМЕН -------------------------- */
/* -------------------------------------------------------------------- */
?>
<?if($arResult["page_type"] == 'import_view_xml'):?>
	<?$APPLICATION->SetTitle(str_replace('#PROCEDURE_NAME#', $arResult["procedure_name"], GetMessage("SEM_TP_IMPORT_XML_CREATED_TITLE")))?>
	<form method="post">
		<?foreach(["SEM_TP_IMPORT_XML_CREATED_XML_LINK" => $arResult["xml_link"], "SEM_TP_IMPORT_XML_CREATED_ANSWER_LINK" => $arResult["answer_link"]] as $title => $link):?>
			<?if($link):?>
			<a href="<?=$link?>" target="_blank"><?=GetMessage($title)?></a><br><br>
			<?endif?>
		<?endforeach?>

		<?
		$APPLICATION->IncludeComponent
			(
			"silta_framework:form_elements.button", '',
				[
				"NAME"  => $arResult["input_name"]["unset_xml"],
				"TITLE" => GetMessage("SEM_TP_IMPORT_XML_CREATED_RESET_BUTTON"),
				"TAG"   => 'button'
				]
			);
		$APPLICATION->IncludeComponent
			(
			"silta_framework:form_elements.button", '',
				[
				"NAME"  => $arResult["input_name"]["run_exchange"],
				"TITLE" => GetMessage("SEM_TP_IMPORT_XML_CREATED_RUN_EXCHANGE_BUTTON"),
				"TAG"   => 'button'
				]
			);
		?>
	</form>
<?endif?>
<?
/* -------------------------------------------------------------------- */
/* ----------------------------- ЭКСПОРТ ------------------------------ */
/* -------------------------------------------------------------------- */
?>
<?if($arResult["page_type"] == 'export'):?>
	<?$APPLICATION->SetTitle(str_replace('#PROCEDURE_NAME#', $arResult["procedure_name"], GetMessage("SEM_TP_EXPORT_TITLE")))?>

	<form method="post">
		<?foreach(["SEM_TP_EXPORT_XML_LINK" => $arResult["xml_link"], "SEM_TP_EXPORT_ERRORS_FILE_LINK" => $arResult["errors_link"]] as $title => $link):?>
			<?if($link):?>
			<a href="<?=$link?>" target="_blank"><?=GetMessage($title)?></a><br><br>
			<?endif?>
		<?endforeach?>

		<?
		$APPLICATION->IncludeComponent
			(
			"silta_framework:form_elements.button", '',
				[
				"NAME"  => $arResult["submit_input_name"],
				"TITLE" => GetMessage("SEM_TP_EXPORT_SUBMIT_BUTTON"),
				"TAG"   => 'button'
				]
			);
		?>
	</form>
<?endif?>