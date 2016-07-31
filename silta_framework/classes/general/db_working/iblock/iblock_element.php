<?
class SIBlockElement extends SDBElement
	{
	protected function ConstructObject()    {}
	protected function AccessCalculating()  {}
	protected function PrepareForSaving()   {}
	protected function AfterElementSaving() {}
	/* ----------------------------------------------------------------- */
	/* ---------------------- сохранение элемента ---------------------- */
	/* ----------------------------------------------------------------- */
	public function ElementSaving(array $propsArray = [])
		{
		$newElementObject = new CIBlockElement;
		// значения
		foreach($propsArray as $property)
			if(!in_array($property, ["created_by", "changed_by", "created_date", "changed_date"]))
				{
				$propertyObject = $this->GetProperty($property);
				$value          = $propertyObject->GetSavingArray();
				$index          = 'props';

				if($propertyObject->GetAttributes()["main_info"] == 'Y')
					{
					$index = 'main_info';
					$value = $value[0];
					}
				$savingArray[$index][$propertyObject->GetAttributes()["code"]] = $value;
				}
		// корректировка значений
		$savingArray["main_info"]["IBLOCK_ID"]   = $this->GetTableObject()->GetIblockId();
		$savingArray["main_info"]["MODIFIED_BY"] = CUser::GetID();
		if(!$savingArray["main_info"]["ACTIVE"]) $savingArray["main_info"]["ACTIVE"] = 'Y';
		foreach($savingArray["props"] as $property => $valueArray)
			if(!count($valueArray))
				$savingArray["props"][$property] = false;
		// создание элемента
		if($this->GetElementId() == 'new')
			{
			$newElementId    = $newElementObject->Add($savingArray["main_info"]);
			if(!$newElementId) return $this->SetError("saving", $newElementObject->LastError);
			$this->elementId = $newElementId;
			}
		// изменение элемента
		if($this->GetElementId() != 'new')
			{
			$elementSaved = $newElementObject->Update($this->GetElementId(), $savingArray["main_info"]);
			if(!$elementSaved) return $this->SetError("saving", $newElementObject->LastError);
			}
		// сохранение свойств
		CIBlockElement::SetPropertyValuesEx
			(
			$this->GetElementId(),
			$this->GetTableObject()->GetIblockId(),
			$savingArray["props"]
			);
		// возврат
		return true;
		}
	/* ----------------------------------------------------------------- */
	/* ----------------------- обновить осн.инфу ----------------------- */
	/* ----------------------------------------------------------------- */
	public function UpdateElement()
		{
		foreach(["created_by", "changed_by", "created_date", "changed_date"] as $property)
			{
			$propertyObject = $this->GetProperty($property);
			if(!$propertyObject) continue;

			$value = $propertyObject->GetSavingArray();
			if(is_array($value)) $value = $value[0];
			$savingArray[$propertyObject->GetAttributes()["code"]] = $value;
			}
		if($savingArray)
			(new CIBlockElement)->Update($this->GetElementId(), $savingArray);
		}
	/* ----------------------------------------------------------------- */
	/* ----------------------- удаление элемента ----------------------- */
	/* ----------------------------------------------------------------- */
	public function ElementDeleting()
		{
		return CIBlockElement::Delete($this->GetElementId());
		}
	}
?>