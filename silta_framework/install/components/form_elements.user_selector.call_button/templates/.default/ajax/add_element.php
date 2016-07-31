<?
define('STOP_STATISTICS', true);
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';
$APPLICATION->RestartBuffer();

if(!CModule::IncludeModule("silta_framework") || !$_POST["value"]) exit();
$explodeArray = explode('|', $_POST["value"]);
$type  = $explodeArray[0];
$value = $explodeArray[1];
?>

<?if($type == 'user'):?>
<table silta-form-user-selector-checked-element>
	<tr>
		<td>
			<input name="<?=$_POST["input_name"]?>" value="user|<?=$value?>" style="display: none">
			<?$APPLICATION->IncludeComponent('bitrix:main.user.link', '', ["ID" => $value, "USE_THUMBNAIL_LIST" => "N"])?>
		</td>
		<td delete-element></td>
	</tr>
</table>
<?endif?>

<?if($type == 'department'):?>
<table silta-form-user-selector-checked-element>
	<tr>
		<td>
			<input name="<?=$_POST["input_name"]?>" value="department|<?=$value?>" style="display: none">
			<?$APPLICATION->IncludeComponent('silta_framework:form_elements.link.department', '', ["DEPARTMENT_ID" => $value])?>
		</td>
		<td delete-element></td>
	</tr>
</table>
<?endif?>