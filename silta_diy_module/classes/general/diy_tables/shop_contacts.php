<?
class SDiyModuleTableShopContacts extends SIBlockTable
	{
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject(array $params = [])
		{
		parent::ConstructObject($params);
		$DiyModule = SDiyModule::GetInstance();
		$this->SetElementsClassName("SDiyModuleElementShopContacts");
		foreach($this->GetAvailableProps() as $property)
			if(!in_array($property, ["created_by", "changed_by", "created_date", "changed_date", "active_from", "active_to", "active"]))
				$this->SetProperty($property);
		/* ----------------------------------------- */
		/* --- проверка инфоблока на корректность -- */
		/* ----------------------------------------- */
		if(!$DiyModule->GetTable("shops"))
			return $this->SetError(str_replace('#TABLE_NAME#', $DiyModule->GetTablesInfo()["shops"]["title"], GetMessage("SF_TABLE_NOT_EXIST")));
		if(!$this->CheckTableValidation(
			[
			"props_existence"    => ["user", "phone", "diy_shops"],
			"props_types"        => ["user" => 'string', "phone" => 'string', "diy_shops" => 'list_element'],
			"props_required"     => ["user", "diy_shops"],
			"props_multiply"     => ["user", "phone"],
			"props_not_multiply" => ["diy_shops"],
			"props_list_element" => ["diy_shops" => $DiyModule->GetTablesInfo()["shops"]["id"]],
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

		$this->GetProperty("phone")->ChangeType("phone");
		$this->GetProperty("diy_shops")->SetAttributes(["available_value" => $DiyModule->GetTable("shops")->GetQuery()]);
		/* ----------------------------------------- */
		/* ---------------- доступ ----------------- */
		/* ----------------------------------------- */
		if(CUser::IsAdmin()) return;
		// виды настроек доступа
		$accessTypes =
			[
			"shop_contacts_access_by_user" => false,
			"shop_contacts_access_full"    => false,
			"shop_contacts_access_write"   => false,
			"shop_contacts_access_create"  => false,
			"shop_contacts_access_delete"  => false
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
		if($accessTypes["shop_contacts_access_by_user"]) $tableQueryAccess = 'by_user';
		if($accessTypes["shop_contacts_access_full"])    $tableQueryAccess = 'full';
		if(!$tableQueryAccess) return $this->SetError(str_replace('#TABLE_NAME#', $DiyModule->GetTablesInfo()["shop_contacts"]["title"], GetMessage("SF_TABLE_NO_ACCESS")));
		// фильтр доступа
		if($tableQueryAccess == 'by_user')
			{
			$tableFilter = [];
			if(!$DiyModule->GetAccess("diy_boss"))
				$tableFilter = ["user" => CUser::GetID()];
			else
				foreach($DiyModule->GetUserDepartments() as $departmentId)
					if(in_array($departmentId, $DiyModule->GetDiyDepartments()))
						$tableFilter["user"][] = 'department|'.$departmentId;

			if(!count($tableFilter)) return $this->SetError(str_replace('#TABLE_NAME#', $DiyModule->GetTablesInfo()["shop_contacts"]["title"], GetMessage("SF_TABLE_NO_ACCESS")));
			$this->SetQueryAccess(array_merge($tableFilter, ["diy_shops" => $DiyModule->GetTable("shops")->GetQuery()]));
			}
		if($tableQueryAccess == 'full')
			$this->SetQueryAccess(["diy_shops" => $DiyModule->GetTable("shops")->GetQuery()]);
		// доступ на виды операций
		if(!$accessTypes["shop_contacts_access_write"])  $this->SetAccess("edit_element",   false);
		if(!$accessTypes["shop_contacts_access_create"]) $this->SetAccess("create_element", false);
		if(!$accessTypes["shop_contacts_access_delete"]) $this->SetAccess("delete_element", false);
		}
	}
?>