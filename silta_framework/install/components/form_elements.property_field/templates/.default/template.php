<?
/* -------------------------------------------------------------------- */
/* ------------------------ СВОЙСТВА НА ЧТЕНИЕ ------------------------ */
/* -------------------------------------------------------------------- */
?>

<? // строковые значения?>
<?foreach($arResult["value_read"] as $value):?>
	<div silta-form-value-read><?=$value?></div>
<?endforeach?>

<? // ссылки на файлы инфоблоков?>
<?foreach($arResult["iblock_file_links"] as $fileId):?>
	<div silta-form-value-read>
		<?$APPLICATION->IncludeComponent('silta_framework:form_elements.link.iblock_file', '', ["FILE_ID" => $fileId])?>
	</div>
<?endforeach?>

<? // элементы инфоблоков?>
<?foreach($arResult["iblock_elements"] as $elementId):?>
	<div silta-form-value-read>
		<?
		$APPLICATION->IncludeComponent
			(
			'silta_framework:form_elements.element_selector.element', '',
				[
				"TABLE"      => $arResult["table"],
				"PROPS"      => $arResult["props"],
				"ELEMENT_ID" => $elementId
				]
			)
		?>
	</div>
<?endforeach?>

<? // ссылки на юзеров?>
<?foreach($arResult["user_links"] as $userId):?>
	<div silta-form-value-read>
		<?$APPLICATION->IncludeComponent('bitrix:main.user.link', '', ["ID" => $userId, "USE_THUMBNAIL_LIST" => "N"])?>
	</div>
<?endforeach?>

<? // ссылки на отделы?>
<?foreach($arResult["department_links"] as $departmentId):?>
	<div silta-form-value-read>
		<?$APPLICATION->IncludeComponent('silta_framework:form_elements.link.department', '', ["DEPARTMENT_ID" => $departmentId])?>
	</div>
<?endforeach?>

<? // телефоны?>
<?foreach($arResult["phones"] as $arrayInfo):?>
	<div silta-form-value-read>
		<?=$arrayInfo["number"]?> - <i><?=$arrayInfo["type"]?></i>
	</div>
<?endforeach?>
<?
/* -------------------------------------------------------------------- */
/* -------------------------- INPUT - STRING -------------------------- */
/* -------------------------------------------------------------------- */
?>
<?if($arResult["type"] == 'input_string'):?>
	<?foreach($arResult["value"] as $index => $value):?>
		<div>
			<?
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.input.string", '',
					[
					"VALUE"       => $value,
					"INPUT_NAME"  => $arResult["field_params"]["input_name"],
					"PLACEHOLDER" => $arResult["field_params"]["placeholder"],
					"SIZE"        => $arResult["field_params"]["size"],
					"MASK"        => $arResult["field_params"]["mask"],
					"ATTR"        => $arResult["field_params"]["attr"]
					]
				);
			if($arResult["multiply"] == 'Y')
				{
				$buttonType = 'remove';
				if(!$arResult["value"][$index+1]) $buttonType = 'add';
				$APPLICATION->IncludeComponent("silta_framework:form_elements.manage_row_buttons", '', ["TYPE" => $buttonType]);
				}
			?>
		</div>
	<?endforeach?>
<?endif?>
<?
/* -------------------------------------------------------------------- */
/* -------------------------- INPUT - NUMBER -------------------------- */
/* -------------------------------------------------------------------- */
?>
<?if($arResult["type"] == 'input_number'):?>
	<?foreach($arResult["value"] as $index => $value):?>
		<div>
			<?
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.input.number", '',
					[
					"VALUE"       => $value,
					"INPUT_NAME"  => $arResult["field_params"]["input_name"],
					"PLACEHOLDER" => $arResult["field_params"]["placeholder"],
					"SIZE"        => $arResult["field_params"]["size"],
					"ATTR"        => $arResult["field_params"]["attr"]
					]
				);
			if($arResult["multiply"] == 'Y')
				{
				$buttonType = 'remove';
				if(!$arResult["value"][$index+1]) $buttonType = 'add';
				$APPLICATION->IncludeComponent("silta_framework:form_elements.manage_row_buttons", '', ["TYPE" => $buttonType]);
				}
			?>
		</div>
	<?endforeach?>
<?endif?>
<?
/* -------------------------------------------------------------------- */
/* --------------------------- INPUT - DATE --------------------------- */
/* -------------------------------------------------------------------- */
?>
<?if($arResult["type"] == 'input_date'):?>
	<?foreach($arResult["value"] as $index => $value):?>
		<div>
			<?
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.input.date", '',
					[
					"VALUE"      => $value,
					"INPUT_NAME" => $arResult["field_params"]["input_name"],
					"DATE"       => $arResult["field_params"]["date"],
					"TIME"       => $arResult["field_params"]["time"],
					"START_DATE" => $arResult["field_params"]["start_date"],
					"ATTR"       => $arResult["field_params"]["attr"]
					]
				);
			if($arResult["multiply"] == 'Y')
				{
				$buttonType = 'remove';
				if(!$arResult["value"][$index+1]) $buttonType = 'add';
				$APPLICATION->IncludeComponent("silta_framework:form_elements.manage_row_buttons", '', ["TYPE" => $buttonType]);
				}
			?>
		</div>
	<?endforeach?>
<?endif?>
<?
/* -------------------------------------------------------------------- */
/* -------------------------- DATE - INTERVAL ------------------------- */
/* -------------------------------------------------------------------- */
?>
<?if($arResult["type"] == 'date_interval'):?>
	<div>
		<?
		$APPLICATION->IncludeComponent
			(
			"silta_framework:form_elements.input.date", '',
				[
				"VALUE"      => $arResult["value"][0],
				"INPUT_NAME" => $arResult["field_params"]["input_name"],
				"DATE"       => $arResult["field_params"]["date"],
				"TIME"       => $arResult["field_params"]["time"],
				"START_DATE" => $arResult["field_params"]["start_date"],
				"ATTR"       => $arResult["field_params"]["attr"]
				]
			);
		?>
		 - 
		<?
		$APPLICATION->IncludeComponent
			(
			"silta_framework:form_elements.input.date", '',
				[
				"VALUE"      => $arResult["value"][1],
				"INPUT_NAME" => $arResult["field_params"]["input_name"],
				"DATE"       => $arResult["field_params"]["date"],
				"TIME"       => $arResult["field_params"]["time"],
				"START_DATE" => $arResult["field_params"]["start_date"],
				"ATTR"       => $arResult["field_params"]["attr"]
				]
			);
		?>
	</div>
<?endif?>
<?
/* -------------------------------------------------------------------- */
/* --------------------------- INPUT - FILE --------------------------- */
/* -------------------------------------------------------------------- */
if($arResult["type"] == 'input_file')
	$APPLICATION->IncludeComponent
		(
		"silta_framework:form_elements.input.file", '',
			[
			"VALUE"               => $arResult["field_params"]["value"],
			"INPUT_NAME"          => $arResult["field_params"]["input_name"],
			"INPUT_NAME_UPLOADED" => $arResult["field_params"]["input_name_uploaded"],
			"MULTIPLY"            => $arResult["field_params"]["multiply"],
			"ATTR"                => $arResult["field_params"]["attr"]
			]
		);
?>
<?
/* -------------------------------------------------------------------- */
/* ----------------------------- TEXTAREA ----------------------------- */
/* -------------------------------------------------------------------- */
?>
<?if($arResult["type"] == 'input_textarea'):?>
	<table style="width: auto">
		<col><col style="width:20px">
		<?foreach($arResult["value"] as $index => $value):?>
		<tr>
			<td>
				<?
				$APPLICATION->IncludeComponent
					(
					"silta_framework:form_elements.textarea", '',
						[
						"VALUE"       => $value,
						"INPUT_NAME"  => $arResult["field_params"]["input_name"],
						"PLACEHOLDER" => $arResult["field_params"]["placeholder"],
						"SIZE"        => $arResult["field_params"]["size"],
						"ATTR"        => $arResult["field_params"]["attr"]
						]
					);
				?>
			</td>
			<td style="vertical-align: middle">
				<?
				if($arResult["multiply"] == 'Y')
					{
					$buttonType = 'remove';
					if(!$arResult["value"][$index+1]) $buttonType = 'add';
					$APPLICATION->IncludeComponent("silta_framework:form_elements.manage_row_buttons", '', ["TYPE" => $buttonType, "PARENT" => 'tr']);
					}
				?>
			</td>
		</tr>
		<?endforeach?>
	</table>
<?endif?>
<?
/* -------------------------------------------------------------------- */
/* ------------------------------ SELECT ------------------------------ */
/* -------------------------------------------------------------------- */
if($arResult["type"] == 'input_select')
	$APPLICATION->IncludeComponent
		(
		"silta_framework:form_elements.select", '',
			[
			"VALUE"       => $arResult["field_params"]["value"],
			"INPUT_NAME"  => $arResult["field_params"]["input_name"],
			"LIST"        => $arResult["field_params"]["list"],
			"EMPTY_VALUE" => $arResult["field_params"]["empty_value"],
			"WIDTH"       => $arResult["field_params"]["width"],
			"ATTR"        => $arResult["field_params"]["attr"]
			]
		)
?>
<?
/* -------------------------------------------------------------------- */
/* ---------------------------- CHECKBOXES ---------------------------- */
/* -------------------------------------------------------------------- */
?>
<?if($arResult["type"] == 'input_checkboxes'):?>
	<?foreach($arResult["list"] as $listInfo):?>
		<div>
			<?
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.checkbox", '',
					[
					"VALUE"      => $listInfo["value"],
					"TITLE"      => $listInfo["title"],
					"CHECKED"    => $listInfo["checked"],
					"INPUT_NAME" => $arResult["field_params"]["input_name"],
					"ATTR"       => $arResult["field_params"]["attr"]
					]
				)
			?>
		</div>
	<?endforeach?>
<?endif?>
<?
/* -------------------------------------------------------------------- */
/* ------------------------- ELEMENT SELECTOR ------------------------- */
/* -------------------------------------------------------------------- */
if($arResult["type"] == 'element_selector')
	$APPLICATION->IncludeComponent
		(
		'silta_framework:form_elements.element_selector.call_button', '',
			[
			"VALUE"      => $arResult["value"],
			"INPUT_NAME" => $arResult["field_params"]["input_name"],
			"TABLE"      => $arResult["field_params"]["table"],
			"PROPS"      => $arResult["field_params"]["props"],
			"FILTER"     => $arResult["field_params"]["filter"],
			"MULTIPLY"   => $arResult["field_params"]["multiply"],
			"ATTR"       => $arResult["field_params"]["attr"]
			]
		)
?>
<?
/* -------------------------------------------------------------------- */
/* --------------------------- USER SELECTOR -------------------------- */
/* -------------------------------------------------------------------- */
if($arResult["type"] == 'user_selector')
	$APPLICATION->IncludeComponent
		(
		'silta_framework:form_elements.user_selector.call_button', '',
			[
			"CHECKED_USERS"       => $arResult["value"]["users"],
			"CHECKED_DEPARTMENTS" => $arResult["value"]["departments"],
			"INPUT_NAME"          => $arResult["field_params"]["input_name"],
			"USERS"               => $arResult["field_params"]["users"],
			"DEPARTMENTS"         => $arResult["field_params"]["departments"],
			"START_ROOTS"         => $arResult["field_params"]["start_roots"],
			"MULTIPLY"            => $arResult["field_params"]["multiply"],
			"ATTR"                => $arResult["field_params"]["attr"]
			]
		)
?>
<?
/* -------------------------------------------------------------------- */
/* -------------------------- INPUT - PHONE --------------------------- */
/* -------------------------------------------------------------------- */
?>
<?if($arResult["type"] == 'input_phone'):?>
	<table style="width: auto">
		<col><col><col style="width:20px">
		<?foreach($arResult["value"] as $index => $valueArray):?>
		<tr>
			<td>
				<?
				$APPLICATION->IncludeComponent
					(
					"silta_framework:form_elements.input.string", '',
						[
						"VALUE"       => $valueArray["number"],
						"INPUT_NAME"  => $arResult["field_params"]["input_name_number"],
						"ATTR"        => $arResult["field_params"]["attr"],
						"SIZE"        => '15',
						"MASK"        => '(999) 999-99-99'
						]
					);
				?>
			</td>
			<td>
				<?
				$APPLICATION->IncludeComponent
					(
					"silta_framework:form_elements.select", '',
						[
						"VALUE"       => $valueArray["type"],
						"INPUT_NAME"  => $arResult["field_params"]["input_name_type"],
						"LIST"        => $arResult["field_params"]["list"],
						"EMPTY_VALUE" => 'N',
						"WIDTH"       => '120'
						]
					);
				?>
			</td>
			<td>
				<?
				if($arResult["multiply"] == 'Y')
					{
					$buttonType = 'remove';
					if(!$arResult["value"][$index+1]) $buttonType = 'add';
					$APPLICATION->IncludeComponent("silta_framework:form_elements.manage_row_buttons", '', ["TYPE" => $buttonType, "PARENT" => 'tr']);
					}
				?>
			</td>
		</tr>
		<?endforeach?>
	</table>
<?endif?>