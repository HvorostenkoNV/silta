<?
if(!$USER->IsAdmin()) return;
$moduleId = GetModuleID(__FILE__);
CModule::IncludeModule($moduleId);
IncludeModuleLangFile(__FILE__);
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$submitButtonName = $moduleId.'_settings_submit';
/* -------------------------------------------------------------------- */
/* ---------------------------- табы формы ---------------------------- */
/* -------------------------------------------------------------------- */
$tabControl = new CAdminTabControl
	(
	$moduleId,
		[
		["DIV" => "tab1", "TAB" => GetMessage("SF_SETTINGS_TAB_TITLE_COMPANY_TABLES"), "TITLE" => GetMessage("SF_SETTINGS_TAB_TEXT_COMPANY_TABLES")],
		]
	);
/* -------------------------------------------------------------------- */
/* ---------------------------- обработчик ---------------------------- */
/* -------------------------------------------------------------------- */
if(isset($_POST[$submitButtonName]))
	{
	COption::RemoveOption($moduleId);
	foreach(SCompanyTables::GetInstance()->GetTablesInfo() as $arrayInfo)
		COption::SetOptionString($moduleId, $arrayInfo["module_option"], $_POST[$arrayInfo["module_option"]]);

	$currentTabInfo = explode('=', $tabControl->ActiveTabParam());
	LocalRedirect($APPLICATION->GetCurPage().SgetUrlVarsString([$currentTabInfo[0] => $currentTabInfo[1]]));
	}
/* -------------------------------------------------------------------- */
/* ------------------------------ вывод ------------------------------- */
/* -------------------------------------------------------------------- */
?>

<?$tabControl->Begin()?>
<form method="post">
	<?
	/* ------------------------------------------ */
	/* ------------ таблицы компании ------------ */
	/* ------------------------------------------ */
	$tabControl->BeginNextTab();
	?>
		<col width="30%"><col>
		<?foreach(SCompanyTables::GetInstance()->GetTablesInfo() as $arrayInfo):?>
		<tr>
			<td><?=$arrayInfo["title"]?>:                                                                        </td>
			<td><?=GetIBlockDropDownList($arrayInfo["id"], "infoblockes-selector", $arrayInfo["module_option"])?></td>
		</tr>
		<?endforeach?>
	<?
	/* ------------------------------------------ */
	/* ----------------- кнопки ----------------- */
	/* ------------------------------------------ */
	$tabControl->Buttons();
	?>
		<input type="submit" name="<?=$submitButtonName?>" value="<?=GetMessage("MAIN_SAVE")?>" class="adm-btn-save">
	<?$tabControl->End();?>
</form>