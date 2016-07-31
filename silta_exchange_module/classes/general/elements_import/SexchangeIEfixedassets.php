<?
final class SexchangeIEfixedassets extends SexchangeImportElement
	{
	/* -------------------------------------------------------------------- */
	/* ------------------ преобразовать полученные данные ----------------- */
	/* -------------------------------------------------------------------- */
	protected function ConvertValue(array $params = [], array $valueArray = [])
		{
		$fixedAssetsGroupsTable = SCompanyTables::GetInstance()->GetTable("fixed_assets_groups");
		if($fixedAssetsGroupsTable) $valueArray["group"] = $fixedAssetsGroupsTable->GetQuery([], ["code_1c" => $valueArray["group"]])[0];
		else                        unset($valueArray["group"]);

		return $valueArray;
		}
	/* -------------------------------------------------------------------- */
	/* ------------------- работа с полученными данными ------------------- */
	/* -------------------------------------------------------------------- */
	protected function ExchangeOperation(array $params = [], array $valueArray = [])
		{
		$workTable = SCompanyTables::GetInstance()->GetTable("fixed_assets");
		if(!$workTable) return $this->SetError(str_replace('#TABLE_OBJECT#', SCompanyTables::GetInstance()->GetTablesInfo()["fixed_assets"]["title"], GetMessage("SF_TABLE_NOT_EXIST")));
		// объект элемента
		$elementId = $workTable->GetQuery([], ["code_1c" => $valueArray["code_1c"]])[0];
		if(!$elementId) $elementId = 'new';
		$elementObject = $workTable->GetElement($elementId);
		if(!$elementObject) return $this->SetError(GetMessage("SEM_CE_ELEMENT_OBJECT_NOT_EXIST"));
		// свойства
		$saveProps = [];
		foreach($valueArray as $property => $value)
			if($elementObject->GetProperty($property))
				{
				$elementObject->GetProperty($property)->SetValue($value);
				$saveProps[] = $property;
				}
		// сохранение
		if(!$elementObject->SaveElement($saveProps))
			foreach($elementObject->GetErrors() as $error)
				$this->SetError($error);
		}
	}
?>