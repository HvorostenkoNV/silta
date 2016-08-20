<?
$APPLICATION->AddHeadString('<script>SUserSelectorFolder = "http://'.$_SERVER['SERVER_NAME'].$templateFolder.'";</script>');
$APPLICATION->AddHeadScript('/bitrix/js/silta_framework/draggable.js');
$APPLICATION->IncludeComponent('silta_framework:form_elements.waiting_screen');
// Обязательное наличие аттрибута silta-form-element="user-selector" !!!

// титул кнопки
if($arResult["users"] == 'N' && $arResult["departments"] == 'Y') $title = GetMessage("SF_US_BUTTON_TITLE_DEPS");
else                                                             $title = GetMessage("SF_US_BUTTON_TITLE_USERS");
?>

<span
	<?if($arResult["input_name"]):?>        input-name="<?=$arResult["input_name"]?>"                <?endif?>
	<?if(count($arResult["start_roots"])):?>start-roots="<?=implode('|', $arResult["start_roots"])?>"<?endif?>

	users="<?=$arResult["users"]?>"
	departments="<?=$arResult["departments"]?>"
	multiply="<?=$arResult["multiply"]?>"

	silta-form-element="user-selector"
	class="silta-form-user-selector-call-button"
	id="user-selector-call-button-<?=rand()?>"
	<?=$arResult["attr"]?>
>
	<?=$title?>
	<span icon></span>
</span>

<?foreach($arResult["checked_users"] as $userId):?>
<table silta-form-user-selector-checked-element>
	<tr>
		<td>
			<input name="<?=$arResult["input_name"]?>" value="user|<?=$userId?>" style="display: none">
			<?$APPLICATION->IncludeComponent('bitrix:main.user.link', '', ["ID" => $userId, "USE_THUMBNAIL_LIST" => "N"])?>
		</td>
		<td delete-element></td>
	</tr>
</table>
<?endforeach?>

<?foreach($arResult["checked_departments"] as $departmentId):?>
<table silta-form-user-selector-checked-element>
	<tr>
		<td>
			<input name="<?=$arResult["input_name"]?>" value="department|<?=$departmentId?>" style="display: none">
			<?$APPLICATION->IncludeComponent('silta_framework:form_elements.link.department', '', ["DEPARTMENT_ID" => $departmentId])?>
		</td>
		<td delete-element></td>
	</tr>
</table>
<?endforeach?>