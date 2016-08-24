<?
class SProceduresFAWProvisionApplicationTable extends SIBlockTable
	{
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject(array $params = [])
		{
		parent::ConstructObject($params);
		$this->SetElementsClassName("SProceduresFAWProvisionApplicationElement");
		foreach($this->GetAvailableProps() as $property) $this->SetProperty($property);
		$FixedAssetsWork = SProceduresFixedAssetsWork::GetInstance();
		$CompanyTables   = SCompanyTables::GetInstance();
		/* ----------------------------------------- */
		/* --- проверка инфоблока на корректность -- */
		/* ----------------------------------------- */
		if(!$this->CheckTableValidation(
			[
			"props_existence"    => ["department", "fixed_assets_groups", "text", "user_signed", "stage"],
			"props_types"        => ["department" => 'section', "fixed_assets_groups" => 'list_element', "text" => 'text', "user_signed" => 'user', "stage" => 'list'],
			"props_required"     => ["department", "fixed_assets_groups", "text", "stage"],
			"props_multiply"     => ["user_signed"],
			"props_not_multiply" => ["department", "fixed_assets_groups", "text", "stage"],
			"props_list_element" => ["fixed_assets_groups" => $CompanyTables->GetTablesInfo()["fixed_assets_groups"]["id"]],
			]))
			return;
		/* ----------------------------------------- */
		/* ----------- настройки свойств ----------- */
		/* ----------------------------------------- */
		$this->GetProperty("fixed_assets_groups")->SetAttributes(["available_value" => $CompanyTables->GetTable("fixed_assets_groups")->GetQuery()]);
		if(!CUser::IsAdmin()) $this->GetProperty("department")->SetAttributes(["start_sections" => $FixedAssetsWork->GetUserDepartments()]);
		/* ----------------------------------------- */
		/* ----------- настройки доступа ----------- */
		/* ----------------------------------------- */
		if(CUser::IsAdmin()) return;
		$availableDepartments  = [];
		$availableAssetsGroups = [];
		/* -------------------------- */
		/* ------ руководитель ------ */
		/* -------------------------- */
		foreach($FixedAssetsWork->GetUserDepartments() as $departmentId)
			if( (new SCompanyDepartment(["id" => $departmentId]))->GetBoss() == CUser::GetID())
				{
				$availableDepartments[] = $departmentId;
				$dbSection = CIBlockSection::GetByID($departmentId);
				if($section = $dbSection->GetNext())
					{
					$sectionList = CIBlockSection::GetList
						(
						[],
							[
							'IBLOCK_ID'     => $section['IBLOCK_ID'],
							'>LEFT_MARGIN'  => $section['LEFT_MARGIN'],
							'<RIGHT_MARGIN' => $section['RIGHT_MARGIN'],
							'>DEPTH_LEVEL'  => $section['DEPTH_LEVEL']
							]
						);
					while($sectionResulr = $sectionList->GetNext())
						$availableDepartments[] = $sectionResulr["ID"];
					}
				}
		/* ----------------------------------------- */
		/* ------ ответственный по выполнению ------ */
		/* ----------------------------------------- */
		$FixedAssetsGroupsTable = $CompanyTables->GetTable("fixed_assets_groups");
		if(!$FixedAssetsGroupsTable) return $this->SetError(str_replace("#TABLE_NAME#", $CompanyTables->GetTablesInfo()["fixed_assets_groups"]["title"], GetMessage("SF_TABLE_NOT_EXIST")));
		$availableAssetsGroups = $FixedAssetsGroupsTable->GetQuery([], ["responsibles" => CUser::GetID()]);
		/* ----------------------------------------- */
		/* --------- корректировка доступа --------- */
		/* ----------------------------------------- */
		if(!count($availableDepartments) && !count($availableAssetsGroups))
			return $this->SetError(str_replace('#TABLE_NAME#', $FixedAssetsWork->GetTablesInfo()["provision_application"]["title"], GetMessage("SF_TABLE_NO_ACCESS")));

		if(!count($availableDepartments)) $this->SetAccess("create_element", false);
		$this->SetQueryAccess
			([
			"ID" => array_merge
				(
				$this->GetQuery([], ["department"          => $availableDepartments] ),
				$this->GetQuery([], ["fixed_assets_groups" => $availableAssetsGroups])
				)
			]);
		}
	}
?>