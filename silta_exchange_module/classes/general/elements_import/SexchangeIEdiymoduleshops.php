<?
final class SexchangeIEdiymoduleshops extends SexchangeImportElement
	{
	/* -------------------------------------------------------------------- */
	/* ------------------ преобразовать полученные данные ----------------- */
	/* -------------------------------------------------------------------- */
	protected function ConvertValue(array $params = [], array $valueArray = [])
		{
		// контрагент
		$contragentsTable = SCompanyTables::GetInstance()->GetTable("contragents");
		if($contragentsTable) $valueArray["contragent"] = $contragentsTable->GetQuery([], ["code_1c" => $valueArray["contragent"]])[0];
		else                  unset($valueArray["contragent"]);
		// активность
		if($valueArray["activity"] == 'y') $valueArray["activity"] = 'Y';
		if($valueArray["activity"] == 'n') $valueArray["activity"] = 'N';
		if(!in_array($valueArray["activity"], ["Y", "N"])) $valueArray["activity"] = 'Y';
		// возврат
		return $valueArray;
		}
	/* -------------------------------------------------------------------- */
	/* ------------------- работа с полученными данными ------------------- */
	/* -------------------------------------------------------------------- */
	protected function ExchangeOperation(array $params = [], array $valueArray = [])
		{
		$diyShopsTable = $this->GetProcedureObject()->GetDiyShopsTable();
		if(!$diyShopsTable) return $this->SetError(str_replace('#TABLE_OBJECT#', 'DIY магазины', GetMessage("SF_TABLE_NOT_EXIST")));
		// объект магазина
		$shopId = $diyShopsTable->GetQuery([], ["code_1c" => $valueArray["code_1c"]])[0];
		if(!$shopId) $shopId = 'new';
		$shopObject = $diyShopsTable->GetElement($shopId);
		if(!$shopObject) return $this->SetError(GetMessage("SEM_CE_ELEMENT_OBJECT_NOT_EXIST"));
		// свойства
		$saveProps = [];
		$valueArray["active"] = $valueArray["activity"];
		foreach($valueArray as $property => $value)
			if($shopObject->GetProperty($property))
				{
				$shopObject->GetProperty($property)->SetValue($value);
				$saveProps[] = $property;
				}
		// сохранение
		if(!count($saveProps)) return;
		if(!$shopObject->SaveElement($saveProps))
			foreach($shopObject->GetErrors() as $error)
				$this->SetError($error);
		}
	}
?>