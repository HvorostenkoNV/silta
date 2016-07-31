<?
final class SexchangeEEfixedassetswork extends SexchangeExportElement
	{
	/* -------------------------------------------------------------------- */
	/* ------------------ приготовить данные по элементу ------------------ */
	/* -------------------------------------------------------------------- */
	protected function PrepareValue(array $params = [], array $valueArray = [])
		{
		// перемещение
		if($valueArray["type"] == 'displacement')
			{
			$RESULT =
				[
				"type"        => 'displacement',
				"application" => $valueArray["element_object"]->GetElementId(),
				];

			foreach(["author" => 'created_by', "fixed_asset" => 'fixed_asset', "new_user" => 'new_user', "text" => 'text'] as $index => $property)
				{
				$propertyObject = $valueArray["element_object"]->GetProperty($property);
				if($propertyObject) $RESULT[$index] = $propertyObject->GetValue();
				}

			if($RESULT["fixed_asset"])
				{
				$fixedAssetsTable = SCompanyTables::GetInstance()->GetTable("fixed_assets");
				if($fixedAssetsTable) $fixedAssetsElement = $fixedAssetsTable->GetElement($RESULT["fixed_asset"]);
				if($fixedAssetsElement) $RESULT["fixed_asset"] = $fixedAssetsElement->GetProperty("code_1c")->GetValue();
				else                    unset($RESULT["fixed_asset"]);
				}

			return $RESULT;
			}
		}
	}
?>