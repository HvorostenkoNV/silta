<?
abstract class SDiyModuleElement extends SIBlockElement
	{
	/* ----------------------------------------------------------------- */
	/* ----------------------- сохранить элемент ----------------------- */
	/* ----------------------------------------------------------------- */
	final public function SaveDiyElement(array $valueArray = [])
		{
		$savingResult = false;                                                  // результат операции
		$propsSaving  = [];                                                     // массив свойств, которые будут записаны
		$historyTable = SDiyModule::GetInstance()->GetTable("element_history"); // таблица истории изменений
		if($historyTable)
			{
			$historyOperationType = 'change';                                        // тип изменения элемента (для истоии изменений)
			$propsChangings       = [];                                              // массив изменений
			$historyPropsSaving   = ["name", "element", "operation_type"];           // массив свойств, которые будут записаны
			if($this->GetElementId() == 'new')    $historyOperationType = 'create';
			if($historyOperationType == 'change') $historyPropsSaving[] = 'changing';
			}
		/* ----------------------------------------- */
		/*   проход по переданному массиву значений  */
		/* ----------------------------------------- */
		foreach($valueArray as $property => $infoArray)
			{
			$propertyObject = $this->GetProperty($property);
			if(!$propertyObject || !$propertyObject->GetAccess("write")) continue;
			$propsSaving[] = $property;
			// запись свойств
			$propOldValue = SgetClearArray($propertyObject->GetValue());
			if(!$propertyObject->GetValueParams()["value_geted"]) unset($propOldValue);
			$propertyObject->SetValue($infoArray, "form");
			if($propertyObject->GetValueParams()["value_geted"]) $propNewValue = SgetClearArray($propertyObject->GetValue());
			// массив изменений
			if($historyOperationType == 'change' && $propertyObject->GetType() != 'file')
				if
					(
					count(array_diff($propNewValue, $propOldValue))
					||
					count(array_diff($propOldValue, $propNewValue))
					||
					($propOldValue && !$propNewValue)
					||
					($propNewValue && !$propOldValue)
					)
					$propsChangings[$property] =
						[
						"old_value" => $propOldValue,
						"new_value" => $propNewValue
						];
			}
		/* ----------------------------------------- */
		/* -------------- сохранение --------------- */
		/* ----------------------------------------- */
		if(!$propsSaving[0])                                             return false;
		$savingResult = $this->SaveElement($propsSaving);
		if(!$savingResult)                                               return false;
		if($historyOperationType == 'change' && !count($propsChangings)) return true;
		/* ----------------------------------------- */
		/* ----------- история изменений ----------- */
		/* ----------------------------------------- */
		if($historyTable) $historyElement = $historyTable->GetElement("new");
		if(!$historyElement) return true;
		$historyElement->GetProperty("operation_type")->SetValue($historyOperationType);
		$historyElement->GetProperty("name")          ->SetValue($historyElement->GetProperty("operation_type")->GetValue("title"));
		$historyElement->GetProperty("element")       ->SetValue($this->GetElementId());
		if($historyOperationType == 'change') $historyElement->GetProperty("changing")->SetValue($propsChangings);

		$historyElement->SaveElement($historyPropsSaving);
		/* ----------------------------------------- */
		/* ----------- возврат результата ---------- */
		/* ----------------------------------------- */
		return true;
		}
	}
?>