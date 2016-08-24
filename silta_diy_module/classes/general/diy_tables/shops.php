<?
class SDiyModuleTableShops extends SIBlockTable
	{
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject(array $params = [])
		{
		parent::ConstructObject($params);
		$DiyModule = SDiyModule::GetInstance();
		$this->SetElementsClassName("SDiyModuleElementShops");
		foreach($this->GetAvailableProps() as $property)
			if(!in_array($property, ["created_by", "changed_by", "created_date", "changed_date", "active_from", "active_to", "code_1c"]))
				$this->SetProperty($property);
		/* ----------------------------------------- */
		/* --- проверка инфоблока на корректность -- */
		/* ----------------------------------------- */
		if(!$this->CheckTableValidation(
			[
			"props_existence"    => ["user", "contragent", "crossmarketing"],
			"props_types"        => ["user" => 'string', "contragent" => 'list_element', "crossmarketing" => 'list'],
			"props_required"     => ["user", "contragent", "crossmarketing"],
			"props_multiply"     => ["user"],
			"props_not_multiply" => ["contragent", "crossmarketing"],
			"props_list_element" => ["contragent" => SCompanyTables::GetInstance()->GetTablesInfo()["contragents"]["id"]],
			]))
			return false;
		/* ----------------------------------------- */
		/* ----------- настройка свойств ----------- */
		/* ----------------------------------------- */
		$availableValue = $DiyModule->GetDiyUsers();
		foreach($DiyModule->GetDiyDepartments() as $departmentId) $availableValue[] = 'department|'.$departmentId;
		$this->GetProperty("user")
			->ChangeType("user")
			->SetAttributes(["available_value" => $availableValue]);
		/* ----------------------------------------- */
		/* ---------------- доступ ----------------- */
		/* ----------------------------------------- */
		if(CUser::IsAdmin()) return;
		// виды настроек доступа
		$accessTypes =
			[
			"shops_access_by_user" => false,
			"shops_access_full"    => false,
			"shops_access_write"   => false,
			"shops_access_create"  => false,
			"shops_access_delete"  => false
			];
		// опр-е видов доступа
		foreach($accessTypes as $moduleOption => $value)
			{
			foreach($DiyModule->GetModuleOption($moduleOption)["module_access"] as $accessType)
				if($DiyModule->GetAccess($accessType))
					$accessTypes[$moduleOption] = true;
			foreach($DiyModule->GetUserDepartments() as $departmentId)
				if(in_array($departmentId, $DiyModule->GetModuleOption($moduleOption)["departments"]))
					$accessTypes[$moduleOption] = true;
			if(in_array(CUser::GetID(), $DiyModule->GetModuleOption($moduleOption)["users"]))
				$accessTypes[$moduleOption] = true;
			}
		$tableQueryAccess = false;
		if($accessTypes["shops_access_by_user"]) $tableQueryAccess = 'by_user';
		if($accessTypes["shops_access_full"])    $tableQueryAccess = 'full';
		if(!$tableQueryAccess) return $this->SetError(str_replace('#TABLE_NAME#', $DiyModule->GetTablesInfo()["shops"]["title"], GetMessage("SF_TABLE_NO_ACCESS")));
		// фильтр доступа
		if($tableQueryAccess == 'by_user')
			{
			$tableFilter = [];
			if(!$DiyModule->GetAccess("diy_boss"))
				$tableFilter = ["user" => CUser::GetID(), "active" => 'Y'];
			else
				foreach($DiyModule->GetUserDepartments() as $departmentId)
					if(in_array($departmentId, $DiyModule->GetDiyDepartments()))
						$tableFilter["user"][] = 'department|'.$departmentId;

			if(!count($tableFilter)) return $this->SetError(str_replace('#TABLE_NAME#', $DiyModule->GetTablesInfo()["shops"]["title"], GetMessage("SF_TABLE_NO_ACCESS")));
			$this->SetQueryAccess($tableFilter);
			}
		// доступ на виды операций
		if(!$accessTypes["shops_access_write"])  $this->SetAccess("edit_element",   false);
		if(!$accessTypes["shops_access_create"]) $this->SetAccess("create_element", false);
		if(!$accessTypes["shops_access_delete"]) $this->SetAccess("delete_element", false);
		}
	}
?>