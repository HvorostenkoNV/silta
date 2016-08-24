<?
class SProceduresFAWDisplacementApplicationTable extends SIBlockTable
	{
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject(array $params = [])
		{
		parent::ConstructObject($params);
		$this->SetElementsClassName("SProceduresFAWDisplacementApplicationElement");
		foreach($this->GetAvailableProps() as $property) $this->SetProperty($property);
		$FixedAssetsWork = SProceduresFixedAssetsWork::GetInstance();
		$CompanyTables   = SCompanyTables::GetInstance();
		/* ----------------------------------------- */
		/* --- проверка инфоблока на корректность -- */
		/* ----------------------------------------- */
		if(!$this->CheckTableValidation
			([
			"props_existence"    => ["provision_application", "fixed_asset", "new_user", "text", "stage"],
			"props_types"        => ["provision_application" => 'list_element', "fixed_asset" => 'list_element', "new_user" => 'user', "stage" => 'list'],
			"props_required"     => ["fixed_asset", "new_user", "stage"],
			"props_not_multiply" => ["provision_application", "fixed_asset", "new_user", "text", "stage"],
			"props_list_element" =>
				[
				"provision_application" => $FixedAssetsWork->GetTablesInfo()["displacement_application"]["id"],
				"fixed_asset"           => $CompanyTables  ->GetTablesInfo()["fixed_assets"]            ["id"]
				],
			]))
			return;
		/* ----------------------------------------- */
		/* ---------- необходимые таблицы ---------- */
		/* ----------------------------------------- */
		$companyTablesObjects = [];
		foreach(["fixed_assets", "fixed_assets_groups"] as $table)
			{
			$companyTablesObjects[$table] = $CompanyTables->GetTable($table);
			if(!$companyTablesObjects[$table]) return $this->SetError(str_replace("#TABLE_NAME#", $CompanyTables->GetTablesInfo()[$table]["title"], GetMessage("SF_TABLE_NOT_EXIST")));
			}
		/* ----------------------------------------- */
		/* ----------- настройки доступа ----------- */
		/* ----------------------------------------- */
		if(CUser::IsAdmin()) return;
		$availableAssetsGroups = $companyTablesObjects["fixed_assets_groups"]->GetQuery([], ["responsibles" => CUser::GetID()]);
		if(count($availableAssetsGroups)) $availableAssets = $companyTablesObjects["fixed_assets"]->GetQuery([], ["group" => $availableAssetsGroups, "active" => 'Y']);

		if(!count($availableAssets)) return $this->SetError(str_replace('#TABLE_NAME#', $FixedAssetsWork->GetTablesInfo()["displacement_application"]["title"], GetMessage("SF_TABLE_NO_ACCESS")));
		$this->SetQueryAccess(["fixed_asset" => $availableAssets]);
		/* ----------------------------------------- */
		/* ----------- настройки свойств ----------- */
		/* ----------------------------------------- */
		$this->GetProperty("fixed_asset")->SetAttributes(["available_value" => $availableAssets]);
		}
	}
?>