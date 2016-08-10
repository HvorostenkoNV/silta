<?
final class SexchangeEEdiymodulesales extends SexchangeExportElement
	{
	/* -------------------------------------------------------------------- */
	/* ------------------ приготовить данные по элементу ------------------ */
	/* -------------------------------------------------------------------- */
	protected function PrepareValue(array $params = [], array $valueArray = [])
		{
		if($valueArray["ID"]) $elementObject = $this->GetProcedureObject()->GetTableObject()->GetElement($valueArray["ID"]);
		if(!$elementObject) return [];
		// массив данных
		$RESULT =
			[
			"id"                    => $elementObject->GetElementId(),
			"active"                => $elementObject->GetProperty("active")                   ->GetValue(),
			"date"                  => $elementObject->GetProperty("date")                     ->GetValue("full_date"),
			"user"                  => $elementObject->GetProperty("user")                     ->GetValue(),
			"shop"                  => $elementObject->GetProperty("diy_shop")                 ->GetValue(),
			"nomenclature_count"    => $elementObject->GetProperty("nomenclature_count")       ->GetValue(),
			"nomenclature_position" => $elementObject->GetProperty("nomenclature_position_new")->GetValue()
			];
		// 1С код магазина
		if($RESULT["shop"])
			{
			$diyShopsTableObject = $this->GetProcedureObject()->GetDiyShopsTableObject();
			if($diyShopsTableObject) $diyShopsElementObject = $diyShopsTableObject->GetElement($RESULT["shop"]);
			if($diyShopsElementObject) $RESULT["shop"] = $diyShopsElementObject->GetProperty("code_1c")->GetValue();
			else                       unset($RESULT["shop"]);
			}
		// 1С код номенклатурной позиции
		if($RESULT["nomenclature_position"])
			{
			$nomenclatureTableObject = $this->GetProcedureObject()->GetNomenclatureTableObject();
			if($nomenclatureTableObject) $nomenclatureElementObject = $nomenclatureTableObject->GetElement($RESULT["nomenclature_position"]);
			if($nomenclatureElementObject) $RESULT["nomenclature_position"] = $nomenclatureElementObject->GetProperty("code_1c")->GetValue();
			else                           unset($RESULT["nomenclature_position"]);
			}
		// результат
		return $RESULT;
		}
	}
?>