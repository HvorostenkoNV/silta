<?
final class SexchangeIEnomenclature extends SexchangeImportElement
	{
	/* -------------------------------------------------------------------- */
	/* ------------------ преобразовать полученные данные ----------------- */
	/* -------------------------------------------------------------------- */
	protected function ConvertValue(array $params = [], array $valueArray = [])
		{
		$nomenclatureTable = SCompanyTables::GetInstance()->GetTable("nomenclature");
		$tradeMarksTable   = SCompanyTables::GetInstance()->GetTable("trade_marks");
		if(!$nomenclatureTable) $this->SetError(str_replace('#TABLE_OBJECT#', SCompanyTables::GetInstance()->GetTablesInfo()["nomenclature"]["title"], GetMessage("SF_TABLE_NOT_EXIST")));
		if(!$tradeMarksTable)   $this->SetError(str_replace('#TABLE_OBJECT#', SCompanyTables::GetInstance()->GetTablesInfo()["trade_marks"]["title"],  GetMessage("SF_TABLE_NOT_EXIST")));
		if(!$nomenclatureTable || !$tradeMarksTable) return $valueArray;
		/* ----------------------------------------- */
		/* ------------- торговая марка ------------ */
		/* ----------------------------------------- */
		$valueArray["trade_mark"] = $tradeMarksTable->GetQuery([], ["code_1c" => $valueArray["trade_mark"]])[0];
		/* ----------------------------------------- */
		/* ---------------- комплект --------------- */
		/* ----------------------------------------- */
		if(!is_array($valueArray["set"])) $valueArray["set"] = [$valueArray["set"]];
		if($valueArray["set"][0]) $valueArray["set"] = $nomenclatureTable->GetQuery([], ["code_1c" => $valueArray["set"]]);
		/* ----------------------------------------- */
		/* ------------ титул комплекта ------------ */
		/* ----------------------------------------- */
		foreach(SgetClearArray($valueArray["set"]) as $elementId)
			{
			$elementObject = $nomenclatureTable->GetElement($elementId);
			if($elementObject)
				$nameTitleArray[] =
					$elementObject->GetProperty("name")   ->GetValue().' '.
					$elementObject->GetProperty("nominal")->GetValue().
					$elementObject->GetProperty("packing")->GetValue();
			}

		if($nameTitleArray[0])
			{
			$nameTitleArray = array_count_values($nameTitleArray);
			foreach($nameTitleArray as $value => $count) $nameTitleArray[$value] = $value.' ('.$count.' шт.)';
			$valueArray["name"] = implode($nameTitleArray, ' + ');
			}
		/* ----------------------------------------- */
		/* --------- особенности разных ТМ --------- */
		/* ----------------------------------------- */
		if(!$valueArray["set"][0] && $valueArray["trade_mark"])
			{
			$tradeMarkName = $tradeMarksTable->GetElement($valueArray["trade_mark"])->GetProperty("name")->GetValue();
			/* ------------------------- */
			/* -------- ELEMENT -------- */
			/* ------------------------- */
			if($tradeMarkName == 'ELEMENT')
				{
				// парсинг названия
				$nameSearch = preg_match('/\(.*\)/', $valueArray["name"], $searchArray);
				if($nameSearch)
					foreach($searchArray as $search)
						if(preg_match('/\d(\d|\.|,)*/', $search, $numberFound))
							{
							if(substr_count($search, 'кг')) $valueArray["packing"] = 'кг';
							if(substr_count($search, 'л'))  $valueArray["packing"] = 'л';
							$valueArray["nominal"] = $numberFound[0];
							$valueArray["name"]    = str_replace(' '.$search, '', $valueArray["name"]);
							}
				// корректировки названия
				$replace_name_array =
					[
					["replace" => [', '],                                                                           "replace_to" => ' '],
					["replace" => ['Елемент', 'Элемент'],                                                           "replace_to" => 'Element'],
					["replace" => ['Аква антисептик', 'аква антисептик'],                                           "replace_to" => 'Aqua Antiseptik'],
					["replace" => ['econom', 'економ'],                                                             "replace_to" => 'Econom'],
					["replace" => ['Грунт Антисептик', 'грунт антисептик', 'Грунт антисептик', 'грунт Антисептик'], "replace_to" => 'Grund Antiseptik'],
					["replace" => ['Грунт', 'грунт'],                                                               "replace_to" => 'Grund'],
					["replace" => [' MGF', ' МГФ'],                                                                 "replace_to" => ''],
					];
				foreach($replace_name_array as $array_info)
					$valueArray["name"] = str_replace($array_info["replace"], $array_info["replace_to"], $valueArray["name"]);
				// корректировки фасовки
				if
					(
					$valueArray["packing"] == 'бан'
					||
					($valueArray["nominal"] && !$valueArray["packing"])
					)
					$valueArray["packing"] = 'л';
				}
			/* ------------------------- */
			/* -------- Harris --------- */
			/* ------------------------- */
			if($tradeMarkName == 'Harris')
				{
				if(isset($valueArray["nominal"]) && !$valueArray["nominal"]) $valueArray["nominal"] = 'шт';
				}
			}
		/* ----------------------------------------- */
		/* ---------------- фасовка ---------------- */
		/* ----------------------------------------- */
		$valueArray["nominal"] = (int) str_replace(',', '.', $valueArray["nominal"]);
		if($valueArray["nominal"] == '0')                                    unset($valueArray["nominal"]);
		if(!$valueArray["packing"])                                          unset($valueArray["nominal"]);
		if(!$valueArray["nominal"])                                          unset($valueArray["packing"]);
		if($valueArray["nominal"] == '1' && $this->value["packing"] == 'шт') unset($valueArray["nominal"], $valueArray["packing"]);
		/* ----------------------------------------- */
		/* ---------------- возврат ---------------- */
		/* ----------------------------------------- */
		return $valueArray;
		}
	/* -------------------------------------------------------------------- */
	/* ------------------- работа с полученными данными ------------------- */
	/* -------------------------------------------------------------------- */
	protected function ExchangeOperation(array $params = [], array $valueArray = [])
		{
		$nomenclatureTable = SCompanyTables::GetInstance()->GetTable("nomenclature");
		if(!$nomenclatureTable) return $this->SetError(str_replace('#TABLE_OBJECT#', SCompanyTables::GetInstance()->GetTablesInfo()["nomenclature"]["title"], GetMessage("SF_TABLE_NOT_EXIST")));
		// объект элемента
		$nomenclatureId = $nomenclatureTable->GetQuery([], ["code_1c" => $valueArray["code_1c"]])[0];
		if(!$nomenclatureId) $nomenclatureId = 'new';
		$nomenclatureObject = $nomenclatureTable->GetElement($nomenclatureId);
		if(!$nomenclatureObject) return $this->SetError(GetMessage("SEM_CE_ELEMENT_OBJECT_NOT_EXIST"));
		// свойства
		$saveProps = [];
		foreach($valueArray as $property => $value)
			if($nomenclatureObject->GetProperty($property))
				{
				$nomenclatureObject->GetProperty($property)->SetValue($value);
				$saveProps[] = $property;
				}
		// сохранение
		if(!count($saveProps)) return;
		if(!$nomenclatureObject->SaveElement($saveProps))
			foreach($nomenclatureObject->GetErrors() as $error)
				$this->SetError($error);
		}
	}
?>