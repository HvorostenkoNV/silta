<?
final class SexchangeIEfixedassetswork extends SexchangeImportElement
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
		if(!CModule::IncludeModule("silta_procedures")) return $this->SetError("modules required: silta_procedures");
		foreach(["purchase" => 'purchase_application', "displacement" => 'displacement_application', "write_off" => 'write_off_application'] as $type => $table)
			if($type == $valueArray["type"])
				{
				$tableObject = SProceduresFixedAssetsWork::GetInstance()->GetTable($table);
				if($tableObject) $elementObject = $tableObject->GetElement($valueArray["application"]);
				}
		if(!$elementObject) return $this->SetError(GetMessage("SF_ELEMENT_NOT_EXIST"));

		if($valueArray["type"] == 'displacement' && $valueArray["change_stage"] == 'working')  $elementObject->ChangeStage("work_in_1c");
		if($valueArray["type"] == 'displacement' && $valueArray["change_stage"] == 'complite') $elementObject->ChangeStage("end");
		}
	}
?>