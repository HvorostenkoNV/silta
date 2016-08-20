<?
if(!$USER->IsAdmin()) return;
$moduleId = GetModuleID(__FILE__);
CModule::IncludeModule($moduleId);
IncludeModuleLangFile(__FILE__);
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$DiyModule        = SDiyModule::GetInstance();                             // объект модуля DIY
$submitButtonName = $moduleId.'-settings-submit';                          // имя кнопки "SUBMIT"
$departmentsList  = SGetSectionsList(SCompanyDepartment::GetRootId(), []); // список подраздалений
// таблица "магазины"
if($DiyModule->GetTable("shops"))
	$tableParams["shops"] =
		[
		"access" =>
			[
			"shops_access_by_user" => 'SDM_SETTINGS_INFOBLOCK_ACCESS_BY_USER',
			"shops_access_full"    => 'SDM_SETTINGS_INFOBLOCK_ACCESS_FULL',
			"shops_access_write"   => 'SDM_SETTINGS_INFOBLOCK_ACCESS_WRITE',
			"shops_access_create"  => 'SDM_SETTINGS_INFOBLOCK_ACCESS_CREATE',
			"shops_access_delete"  => 'SDM_SETTINGS_INFOBLOCK_ACCESS_DELETE'
			],
		"props_var_name" => 'shops_props_to_change'
		];
// таблица "контакты магазинов"
if($DiyModule->GetTable("shop_contacts"))
	$tableParams["shop_contacts"] =
		[
		"access" =>
			[
			"shop_contacts_access_by_user" => 'SDM_SETTINGS_INFOBLOCK_ACCESS_BY_USER',
			"shop_contacts_access_full"    => 'SDM_SETTINGS_INFOBLOCK_ACCESS_FULL',
			"shop_contacts_access_write"   => 'SDM_SETTINGS_INFOBLOCK_ACCESS_WRITE',
			"shop_contacts_access_create"  => 'SDM_SETTINGS_INFOBLOCK_ACCESS_CREATE',
			"shop_contacts_access_delete"  => 'SDM_SETTINGS_INFOBLOCK_ACCESS_DELETE'
			],
		"props_var_name" => 'shop_contacts_props_to_change'
		];
// таблица "продажи"
if($DiyModule->GetTable("sales"))
	$tableParams["sales"] =
		[
		"access" =>
			[
			"sales_access_full"   => 'SDM_SETTINGS_INFOBLOCK_ACCESS_FULL',
			"sales_access_write"  => 'SDM_SETTINGS_INFOBLOCK_ACCESS_WRITE',
			"sales_access_create" => 'SDM_SETTINGS_INFOBLOCK_ACCESS_CREATE',
			"sales_access_delete" => 'SDM_SETTINGS_INFOBLOCK_ACCESS_DELETE'
			],
		"props_var_name" => 'sales_props_to_change'
		];
/* -------------------------------------------------------------------- */
/* ---------------------------- табы формы ---------------------------- */
/* -------------------------------------------------------------------- */
$tabControl = new CAdminTabControl
	(
	$moduleId,
		[
		["DIV" => "main",       "TAB" => GetMessage("SDM_SETTINGS_TAB_TITLE_MAIN"),          "TITLE" => GetMessage("SDM_SETTINGS_TAB_TEXT_MAIN")],
		["DIV" => "infoblocks", "TAB" => GetMessage("SDM_SETTINGS_TAB_TITLE_INFOBLOCK"),     "TITLE" => GetMessage("SDM_SETTINGS_TAB_TEXT_INFOBLOCK")],
		["DIV" => "shops",      "TAB" => GetMessage("SDM_SETTINGS_TAB_TITLE_SHOPS"),         "TITLE" => GetMessage("SDM_SETTINGS_TAB_TEXT_SHOPS")],
		["DIV" => "contacts",   "TAB" => GetMessage("SDM_SETTINGS_TAB_TITLE_SHOP_CONTACTS"), "TITLE" => GetMessage("SDM_SETTINGS_TAB_TEXT_SHOP_CONTACTS")],
		["DIV" => "sales",      "TAB" => GetMessage("SDM_SETTINGS_TAB_TITLE_SALES"),         "TITLE" => GetMessage("SDM_SETTINGS_TAB_TEXT_SALES")]
		]
	);
/* -------------------------------------------------------------------- */
/* ---------------------------- обработчик ---------------------------- */
/* -------------------------------------------------------------------- */
if(isset($_POST[$submitButtonName]))
	{
	COption::RemoveOption($moduleId);
	// основные настройки
	foreach(["diy_structure_root", "marketing_structure_root", "personal_structure_root"] as $optionName)
		COption::SetOptionString($moduleId, $optionName, $_POST[$optionName]);
	COption::SetOptionString($moduleId, "diy_admins", implode('|', SgetClearArray($_POST["diy_admins"])));
	// инфоблоки модуля
	foreach($DiyModule->GetTablesInfo() as $arrayInfo)
		COption::SetOptionString($moduleId, $arrayInfo["module_option"], $_POST[$arrayInfo["module_option"]]);
	// настройки таблиц
	foreach(["shops", "shop_contacts", "sales"] as $table)
		if($DiyModule->GetTable($table))
			{
			foreach($tableParams[$table]["access"] as $optionName => $titleVar)
				{
				foreach($_POST[$optionName] as $index => $value) $_POST[$optionName][$index] = SgetClearArray($value);
				COption::SetOptionString($moduleId, $optionName, serialize($_POST[$optionName]));
				}

			$tableProps = [];
			foreach(SgetClearArray($_POST[$tableParams[$table]["props_var_name"]]) as $property) $tableProps[] = $property;
			COption::SetOptionString($moduleId, $tableParams[$table]["props_var_name"], implode('|', $tableProps));
			}
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
	/* ----------- оновные настройки ------------ */
	/* ------------------------------------------ */
	$tabControl->BeginNextTab();
	?>
		<col style="width: 30%"><col>
		<?foreach(["diy_structure_root" => 'SDM_SETTINGS_DEPARTMENT_DIY', "marketing_structure_root" => 'SDM_SETTINGS_DEPARTMENT_MARKETING', "personal_structure_root"  => 'SDM_SETTINGS_DEPARTMENT_PERSONAL'] as $optionName => $titleVar):?>
		<tr>
			<td><?=GetMessage($titleVar)?>:                                                              </td>
			<td><?=SFMSFSelect($optionName, $departmentsList, $DiyModule->GetModuleOption($optionName))?></td>
		</tr>
		<?endforeach?>
		<tr>
			<td valign="top"><?=GetMessage("SDM_SETTINGS_DIY_ADMINS")?>:                                                                            </td>
			<td><?=SFMSFMultInputes('diy_admins', $DiyModule->GetModuleOption("diy_admins"), GetMessage("SDM_SETTINGS_INPUT_USER_ID_PLACEHOLDER"))?></td>
		</tr>
	<?
	/* ------------------------------------------ */
	/* --------------- инфоблоки ---------------- */
	/* ------------------------------------------ */
	$tabControl->BeginNextTab();
	?>
		<col style="width: 30%"><col>
		<?foreach($DiyModule->GetTablesInfo() as $table => $arrayInfo):?>
		<tr>
			<td><?=$arrayInfo["title"]?>:</td>
			<td>
				<?=GetIBlockDropDownList($arrayInfo["id"], "infoblockes-selector", $arrayInfo["module_option"])?>
				<?if(!$DiyModule->GetTable($table)) ShowError(implode('<br><br>', $DiyModule->GetTableError($table)))?>
			</td>
		</tr>
		<?endforeach?>
	<?
	/* ------------------------------------------ */
	/* ------------ настройки таблиц ------------ */
	/* ------------------------------------------ */
	?>
	<?foreach(["shops", "shop_contacts", "sales"] as $table):?>
	<?$tabControl->BeginNextTab()?>
		<col style="width: 30%"><col>
		<?if($DiyModule->GetTable($table)):?>
			<?if($table == 'shop_contacts'):?>
			<tr><td colspan="2"><?=ShowError(GetMessage("SDM_SETTINGS_ACCESS_SHOP_CONTACTS_MAIN_INFO"))?></td></tr>
			<?endif?>

			<?if($table == 'sales'):?>
			<tr><td colspan="2"><?=ShowError(GetMessage("SDM_SETTINGS_ACCESS_SALES_MAIN_INFO"))?></td></tr>
			<?endif?>

			<?foreach($tableParams[$table]["access"] as $optionName => $titleVar):?>
			<tr>
				<td valign="top"><?=GetMessage($titleVar)?>:</td>
				<td>
					<?
					$listArray = [];
					foreach($DiyModule->GetAccessArray() as $accessType => $infoArray) $listArray[$accessType] = $infoArray["title"];
					?>
					<?=SFMSFMultCheckbox($optionName.'[module_access]', $DiyModule->GetModuleOption($optionName)["module_access"], $listArray)?>
					<?=SFMSFSelect($optionName.'[departments]', $departmentsList, $DiyModule->GetModuleOption($optionName)["departments"], 5)?>                          <br><br>
					<?=SFMSFMultInputes($optionName.'[users]', $DiyModule->GetModuleOption($optionName)["users"], GetMessage("SDM_SETTINGS_INPUT_USER_ID_PLACEHOLDER"))?><br><br>
				</td>
			</tr>
			<?endforeach?>
			<tr>
				<td valign="top"><?=GetMessage("SDM_SETTINGS_INFOBLOCK_ACCESS_PROPS_EDIT")?>:</td>
				<td>
					<?
					$listArray = [];
					foreach($DiyModule->GetTable($table)->GetPropertyList() as $property => $propertyObject) $listArray[$property] = $propertyObject->GetAttributes()["title"];
					?>
					<?=SFMSFMultCheckbox($tableParams[$table]["props_var_name"], $DiyModule->GetModuleOption($tableParams[$table]["props_var_name"]), $listArray)?>
				</td>
			</tr>
		<?endif?>
	<?endforeach?>
	<?
	/* ------------------------------------------ */
	/* ----------------- кнопки ----------------- */
	/* ------------------------------------------ */
	$tabControl->Buttons();
	?>
		<input type="submit" name="<?=$submitButtonName?>" value="<?=GetMessage("MAIN_SAVE")?>" class="adm-btn-save">
	<?$tabControl->End()?>
</form>