<?
final class SexchangeIEcontragents extends SexchangeImportElement
	{
	/* -------------------------------------------------------------------- */
	/* ------------------ преобразовать полученные данные ----------------- */
	/* -------------------------------------------------------------------- */
	protected function ConvertValue(array $params = [], array $valueArray = [])
		{
		return $valueArray;
		}
	/* -------------------------------------------------------------------- */
	/* ------------------- работа с полученными данными ------------------- */
	/* -------------------------------------------------------------------- */
	protected function ExchangeOperation(array $params = [], array $valueArray = [])
		{
		$contragentsTable = SCompanyTables::GetInstance()->GetTable("contragents");
		if(!$contragentsTable) return $this->SetError(str_replace('#TABLE_OBJECT#', SCompanyTables::GetInstance()->GetTablesInfo()["contragents"]["title"], GetMessage("SF_TABLE_NOT_EXIST")));
		// объект магазина
		$contragentId = $contragentsTable->GetQuery([], ["code_1c" => $valueArray["code_1c"]])[0];
		if(!$contragentId) $contragentId = 'new';
		$contragenObject = $contragentsTable->GetElement($contragentId);
		if(!$contragenObject) return $this->SetError(GetMessage("SEM_CE_ELEMENT_OBJECT_NOT_EXIST"));
		// свойства
		$saveProps = [];
		foreach($valueArray as $property => $value)
			if($contragenObject->GetProperty($property))
				{
				$contragenObject->GetProperty($property)->SetValue($value);
				$saveProps[] = $property;
				}
		// сохранение
		if(!count($saveProps)) return;
		if(!$contragenObject->SaveElement($saveProps))
			foreach($contragenObject->GetErrors() as $error)
				$this->SetError($error);
		}
	}
?>