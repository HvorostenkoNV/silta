<?
if(!$USER->IsAdmin()) return;
$moduleId = GetModuleID(__FILE__);
CModule::IncludeModule($moduleId);
IncludeModuleLangFile(__FILE__);
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$FixedAssetsWork  = SProceduresFixedAssetsWork::GetInstance();             // процедура ОС
$BusinessTrip     = SProceduresBusinessTrip::GetInstance();                // процедура коммандировок
$departmentsList  = SGetSectionsList(SCompanyDepartment::GetRootId(), []); // список подраздалений
$submitButtonName = $moduleId.'-settings-submit';                          // кнопка "submit"
/* -------------------------------------------------------------------- */
/* ---------------------------- табы формы ---------------------------- */
/* -------------------------------------------------------------------- */
$tabControl = new CAdminTabControl
	(
	$moduleId,
		[
		["DIV" => "fixed_assets",  "TAB" => GetMessage("SP_SETTINGS_TAB_TITLE_FIXED_ASSETS"),  "TITLE" => GetMessage("SP_SETTINGS_TAB_TEXT_FIXED_ASSETS")],
		["DIV" => "business_trip", "TAB" => GetMessage("SP_SETTINGS_TAB_TITLE_BUSINESS_TRIP"), "TITLE" => GetMessage("SP_SETTINGS_TAB_TEXT_BUSINESS_TRIP")]
		]
	);
/* -------------------------------------------------------------------- */
/* ---------------------------- обработчик ---------------------------- */
/* -------------------------------------------------------------------- */
if(isset($_POST[$submitButtonName]))
	{
	// работа с ОС
	$_POST["fixed_assets_work"]["purchase_responsibles"] = SgetClearArray($_POST["fixed_assets_work"]["purchase_responsibles"]);
	COption::SetOptionString($moduleId, "fixed_assets_work", serialize($_POST["fixed_assets_work"]));
	// коммандировка
	$_POST["business_trip"]["full_access"]                = SgetClearArray($_POST["business_trip"]["full_access"]);
	$_POST["business_trip"]["responsibles"]["department"] = SgetClearArray($_POST["business_trip"]["responsibles"]["department"]);
	$_POST["business_trip"]["responsibles"]["user"]       = SgetClearArray($_POST["business_trip"]["responsibles"]["user"]);
	COption::SetOptionString($moduleId, "business_trip", serialize($_POST["business_trip"]));
	// редирект
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
	/* ------------ оновные средства ------------ */
	/* ------------------------------------------ */
	$tabControl->BeginNextTab();
	?>
		<col width="30%"><col>
		<tr class="heading"><td colspan="2"><?=GetMessage("SP_SETTINGS_IBLOCK_GROUPS")?></td></tr>
		<?foreach($FixedAssetsWork->GetTablesInfo() as $table => $tableInfo):?>
		<tr>
			<td valign="top"><?=$tableInfo["title"]?>:</td>
			<td>
				<?=GetIBlockDropDownList($tableInfo["id"], "infoblockes-selector", 'fixed_assets_work[iblock_id]['.$table.']')?>
				<?$FixedAssetsWork->GetTable($table)?>
				<?=ShowError(implode('<br><br>', $FixedAssetsWork->GetTableErrors()[$table]))?>
			</td>
		</tr>
		<?endforeach?>
		<tr class="heading"><td colspan="2"><?=GetMessage("SP_SETTINGS_MAIN_GROUPS")?></td></tr>
		<tr>
			<td valign="top"><?=GetMessage("SP_SETTINGS_FIXED_ASSETS_PURCHASE_RESPONSIBLES")?>:                                                                 </td>
			<td>             <?=SFMSFMultInputes('fixed_assets_work[purchase_responsibles]', $FixedAssetsWork->GetProcedureOptions()["purchase_responsibles"])?></td>
		</tr>
	<?
	/* ------------------------------------------ */
	/* -------------- коммандировка ------------- */
	/* ------------------------------------------ */
	$tabControl->BeginNextTab();
	?>
		<col width="30%"><col>
		<tr class="heading"><td colspan="2"><?=GetMessage("SP_SETTINGS_IBLOCK_GROUPS")?></td></tr>
		<?foreach($BusinessTrip->GetTablesInfo() as $table => $tableInfo):?>
		<tr>
			<td valign="top"><?=$tableInfo["title"]?>:</td>
			<td>
				<?=GetIBlockDropDownList($tableInfo["id"], "infoblockes-selector", 'business_trip[iblock_id]['.$table.']')?>
				<?if(!$BusinessTrip->GetTable($table)) ShowError(implode('<br><br>', $BusinessTrip->GetTableErrors()[$table]))?>
			</td>
		</tr>
		<?endforeach?>
		<tr class="heading"><td colspan="2"><?=GetMessage("SP_SETTINGS_MAIN_GROUPS")?></td></tr>
		<tr>
			<td valign="top"><?=GetMessage("SP_SETTINGS_BUSINESS_TRIP_FULL_ACCESS")?>:                                               </td>
			<td>             <?=SFMSFMultInputes('business_trip[full_access]', $BusinessTrip->GetProcedureOptions()["full_access"])?></td>
		</tr>
		<tr>
			<td valign="top"><?=GetMessage("SP_SETTINGS_BUSINESS_TRIP_RESPONSIBLES")?>:</td>
			<td>
				<?foreach($BusinessTrip->GetResponsibles() as $departmentId => $userId):?>
				<?=SFMSFSelect('business_trip[responsibles][department][]', $departmentsList, $departmentId)?>
				<input type="text" name="business_trip[responsibles][user][]" value="<?=$userId?>" placeholder="<?=GetMessage("SP_SETTINGS_BUSINESS_TRIP_RESPONSIBLE_PLACEHOLDER")?>">
				<br>
				<?endforeach?>
				<?for($i = 1;$i <= 3;$i++):?>
				<?=SFMSFSelect('business_trip[responsibles][department][]', $departmentsList)?>
				<input type="text" name="business_trip[responsibles][user][]" placeholder="<?=GetMessage("SP_SETTINGS_BUSINESS_TRIP_RESPONSIBLE_PLACEHOLDER")?>">
				<br>
				<?endfor?>
			</td>
		</tr>
	<?
	/* ------------------------------------------ */
	/* ----------------- кнопки ----------------- */
	/* ------------------------------------------ */
	$tabControl->Buttons();
	?>
		<input type="submit" name="<?=$submitButtonName?>" value="<?=GetMessage("MAIN_SAVE")?>" class="adm-btn-save">
	<?$tabControl->End()?>
</form>