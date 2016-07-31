<?
define('STOP_STATISTICS', true);
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';
$APPLICATION->RestartBuffer();

if(!CModule::IncludeModule("silta_framework") || !$_POST["value"] || !$_POST["input_name"]) exit();
$_POST["props"] = explode('|', $_POST["props"]);
?>

<div silta-form-element-selector-checked-element>
	<input name="<?=$_POST["input_name"]?>" value="<?=$_POST["value"]?>" style="display: none">
	<?
	$APPLICATION->IncludeComponent
		(
		'silta_framework:form_elements.element_selector.element', '',
			[
			"TABLE"      => $_POST["table"],
			"PROPS"      => $_POST["props"],
			"ELEMENT_ID" => $_POST["value"]
			]
		)
	?>
	<div delete-element></div>
</div>