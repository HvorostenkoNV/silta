<?
class ScompanyTableFixedAssetsGroups extends SIBlockTable
	{
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject(array $params = [])
		{
		parent::ConstructObject($params);
		foreach($this->GetAvailableProps() as $property) $this->SetProperty($property);
		// уровень доступа
		if(CUser::IsAdmin()) return;
		$departmentsChainArray = [];
		$userList = CUser::GetList($by = "ID", $order = "asc" , ["ID" => CUser::GetID()], ["FIELDS" => ["ID"], "SELECT" => ["UF_DEPARTMENT"]]);
		while($user = $userList->GetNext())
			foreach($user["UF_DEPARTMENT"] as $departmentId)
				{
				$nav = CIBlockSection::GetNavChain(false, $departmentId);
				while($arSectionPath = $nav->GetNext()) $departmentsChainArray[] = $arSectionPath["ID"];
				}
		if(count($departmentsChainArray)) $this->SetQueryAccess(["departments" => $departmentsChainArray]);
		}
	}
?>