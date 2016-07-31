<?
class ScompanyTableFixedAssets extends SIBlockTable
	{
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject(array $params = [])
		{
		parent::ConstructObject($params);
		foreach($this->GetAvailableProps() as $property) $this->SetProperty($property);
		// уровень доступа
		$FixedAssetsGroupsTable      = SCompanyTables::GetInstance()->GetTable("fixed_assets_groups");
		$FixedAssetsGroupsTableTitle = SCompanyTables::GetInstance()->GetTablesInfo()["fixed_assets_groups"]["title"];
		if(!$FixedAssetsGroupsTable) return $this->SetError(str_replace("#TABLE_NAME#", $FixedAssetsGroupsTableTitle, GetMessage("SF_TABLE_NOT_EXIST")));
		$this->SetQueryAccess(["group" => $FixedAssetsGroupsTable->GetQuery()]);
		}
	}
?>