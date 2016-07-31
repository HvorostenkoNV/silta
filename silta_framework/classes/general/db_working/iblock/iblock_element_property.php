<?
abstract class SIBlockElementProperty extends SDBElementProperty
	{
	/* ----------------------------------------------------------------- */
	/* ------------------------ выборка значений ----------------------- */
	/* ----------------------------------------------------------------- */
	final protected function GetQuery()
		{
		$elementId    = $this->GetElementObject()->GetElementId();
		$iblockId     = $this->GetElementObject()->GetTableObject()->GetIblockId();
		$propertyCode = $this->GetAttributes()["code"];

		if($elementId == 'new') return false;
		if($this->GetAttributes()["main_info"] == 'Y')
			{
			$elementList = CIBlockElement::GetList([], ["IBLOCK_ID" => $iblockId, "ID" => $elementId], false, false, ["ID", $propertyCode]);
			while($element = $elementList->GetNext()) $RESULT[] = $element[$propertyCode];
			}
		else
			{
			$propList = CIBlockElement::GetProperty($iblockId, $elementId, [], ["CODE" => $propertyCode]);
			while($prop = $propList->GetNext()) $RESULT[] = $prop["VALUE"];
			}
		return $RESULT;
		}
	}
?>