<?
$APPLICATION->AddHeadScript('/bitrix/js/silta_framework/draggable.js');
/* -------------------------------------------------------------------- */
/* ---------------------------- титул формы --------------------------- */
/* -------------------------------------------------------------------- */
$tableTitle = GetMessage('SDM_ECF_ELEMENT_'.$arResult["table"]);
if(!$tableTitle)               $tableTitle               = GetMessage("SDM_ECF_ELEMENT_DEFAULT");
if(!$arResult["element_name"]) $arResult["element_name"] = GetMessage("SDM_ECF_ELEMENT_NAME");

$formTitle = GetMessage("SDM_ECF_FORM_TITLE");
$formTitle = str_replace('#TABLE_TITLE#',  $tableTitle,               $formTitle);
$formTitle = str_replace('#ELEMENT_NAME#', $arResult["element_name"], $formTitle);
/* -------------------------------------------------------------------- */
/* --------------------------- кнопка вызова -------------------------- */
/* -------------------------------------------------------------------- */
$APPLICATION->IncludeComponent
	(
	"silta_framework:form_elements.button", '',
		[
		"TITLE" => GetMessage("SDM_ECF_OPEN_BUTTON"),
		"IMG"   => $templateFolder.'/images/apply.png',
		"NAME"  => 'silta-diy-module-element-comment-form-open'
		]
	);
/* -------------------------------------------------------------------- */
/* ------------------------------ форма ------------------------------- */
/* -------------------------------------------------------------------- */
?>
<form method="post" enctype="multipart/form-data" id="silta-diy-module-element-comment-form"><table>
	<tr>
		<th form-title><?=$formTitle?></th>
	</tr>
	<?foreach($arResult["props"] as $infoArray):?>
	<tr>
		<td>
			<?
			$fieldParams = ["INPUT_NAME" => $infoArray["input_name"]];
			if($infoArray["main_text_field"])
				{
				$fieldParams["PLACEHOLDER"] = GetMessage("SDM_ECF_TEXTAREA_PLACEHOLDER");
				$fieldParams["SIZE"]        = '100-5';
				}

			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.property_field", '',
					[
					"FIELD_TYPE"      => 'write',
					"PROPERTY_OBJECT" => $infoArray["object"],
					"FIELD_PARAMS"    => $fieldParams
					]
				)
			?>
		</td>
	</tr>
	<?endforeach?>
	<tr>
		<td buttons-cell>
			<?
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"TITLE" => GetMessage("SDM_ECF_APPLY_BUTTON"),
					"IMG"   => $templateFolder.'/images/apply.png',
					"NAME"  => $arResult["input_name"]["submit_button"],
					"TAG"   => 'button'
					]
				);
			$APPLICATION->IncludeComponent
				(
				"silta_framework:form_elements.button", '',
					[
					"TITLE" => GetMessage("SDM_ECF_CANCEL_BUTTON"),
					"IMG"   => $templateFolder.'/images/cancel.png',
					"NAME"  => 'silta-diy-module-element-comment-form-close'
					]
				);
			?>
		</td>
	</tr>
</table></form>