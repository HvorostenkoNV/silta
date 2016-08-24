<?
class SDiyModuleTableSales extends SIBlockTable
	{
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject(array $params = [])
		{
		parent::ConstructObject($params);
		$DiyModule = SDiyModule::GetInstance();
		$this->SetElementsClassName("SDiyModuleElementSales");
		foreach($this->GetAvailableProps() as $property)
			if(!in_array($property, ["created_by", "changed_by", "created_date", "changed_date", "active_from", "active_to"]))
				$this->SetProperty($property);
		/* ----------------------------------------- */
		/* --- проверка инфоблока на корректность -- */
		/* ----------------------------------------- */
		if(!$DiyModule->GetTable("shops"))
			return $this->SetError(str_replace('#TABLE_NAME#', $DiyModule->GetTablesInfo()["shops"]["title"], GetMessage("SF_TABLE_NOT_EXIST")));
		if(!$this->CheckTableValidation(
			[
			"props_existence"    => ["user", "diy_shop", "date", "nomenclature_count", "nomenclature_position"],
			"props_types"        => ["user" => 'user', "diy_shop" => 'list_element', "date" => 'date', "nomenclature_count" => 'number', "nomenclature_position" => 'list_element'],
			"props_required"     => ["user", "diy_shop", "date", "nomenclature_count", "nomenclature_position"],
			"props_not_multiply" => ["user", "diy_shop", "date", "nomenclature_count", "nomenclature_position"],
			"props_list_element" =>
				[
				"diy_shop"              => $DiyModule->GetTablesInfo()["shops"]["id"],
				"nomenclature_position" => SCompanyTables::GetInstance()->GetTablesInfo()["nomenclature"]["id"]
				]
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

		$this->GetProperty("diy_shop")->SetAttributes(["available_value" => $DiyModule->GetTable("shops")->GetQuery()]);
		/* ----------------------------------------- */
		/* ---------------- доступ ----------------- */
		/* ----------------------------------------- */
		if(CUser::IsAdmin()) return;
		// виды настроек доступа
		$accessTypes =
			[
			"sales_access_full"   => false,
			"sales_access_write"  => false,
			"sales_access_create" => false,
			"sales_access_delete" => false
			];
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
		if($accessTypes["sales_access_full"]) $tableQueryAccess = 'full';
		if(!$tableQueryAccess) return $this->SetError(str_replace('#TABLE_NAME#', $DiyModule->GetTablesInfo()["sales"]["title"], GetMessage("SF_TABLE_NO_ACCESS")));
		// фильтр доступа
		if($tableQueryAccess == 'full')
			$this->SetQueryAccess(["diy_shop" => $DiyModule->GetTable("shops")->GetQuery()]);
		// доступ на виды операций
		if(!$accessTypes["sales_access_write"])  $this->SetAccess("edit_element",   false);
		if(!$accessTypes["sales_access_create"]) $this->SetAccess("create_element", false);
		if(!$accessTypes["sales_access_delete"]) $this->SetAccess("delete_element", false);
		}
	}
?>