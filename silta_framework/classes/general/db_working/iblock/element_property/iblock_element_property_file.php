<?
final class SIBlockElementPropertyFile extends SIBlockElementProperty
	{
	protected $propertyType = 'file';
	/* ----------------------------------------------------------------- */
	/* ---------------- ПРЕОБРАЗОВАНИЕ ЗНАЧЕНИЯ - из БД ---------------- */
	/* ----------------------------------------------------------------- */
	protected function ConvertDBValue(array $valueArray = [])
		{
		foreach($valueArray as $value)
			{
			$value = (int)$value;
			if($value) $RESULT[] = 'uploaded|'.$value;
			}
		return $RESULT;
		}
	/* ----------------------------------------------------------------- */
	/* --------------- ПРЕОБРАЗОВАНИЕ ЗНАЧЕНИЯ - из формы -------------- */
	/* ----------------------------------------------------------------- */
	protected function ConvertFormValue(array $valueArray = [])
		{
		foreach($valueArray["uploaded"] as $value)
			{
			$value = (int)$value;
			if($value) $RESULT[] = 'uploaded|'.$value;
			}
		foreach($valueArray["new"] as $value)
			if($value["name"] && $value["tmp_name"])
				$RESULT[] = 'new|'.$value["tmp_name"].'|'.$value["name"];
		return $RESULT;
		}
	/* ----------------------------------------------------------------- */
	/* ---------- ПРЕОБРАЗОВАНИЕ ЗНАЧЕНИЯ - пользовательское ----------- */
	/* ----------------------------------------------------------------- */
	protected function ConvertUserValue(array $valueArray = [])
		{
		return $this->ConvertDBValue($valueArray);
		}
	/* ----------------------------------------------------------------- */
	/* ------------- ПРЕОБРАЗОВАНИЕ ЗНАЧЕНИЯ - для фильтра ------------- */
	/* ----------------------------------------------------------------- */
	protected function PrepareFilterValue()
		{
		return false;
		}
	/* ----------------------------------------------------------------- */
	/* ------------------ получить значение свойства ------------------- */
	/* ----------------------------------------------------------------- */
	protected function GetPropertyValue($valueIndex = '')
		{
		if(!in_array($valueIndex, ['uploaded', 'new'])) $valueIndex = 'uploaded';
		// проход по массиву значений
		foreach($this->value as $value)
			{
			$explodeArray = explode('|', $value);
			if($explodeArray[0] == $valueIndex) $RESULT[] = $explodeArray[1];
			}
		// возврат значения
		if($this->GetAttributes()["multiply"] == 'N') $RESULT = $RESULT[0];
		return $RESULT;
		}
	/* ----------------------------------------------------------------- */
	/* ---------------- получить массив для сохранения ----------------- */
	/* ----------------------------------------------------------------- */
	protected function PrepareSavingArray()
		{
		// значение свойства
		foreach($this->value as $value)
			{
			$explodeArray = explode('|', $value);
			if($explodeArray[0] == 'uploaded') $propValue["uploaded"][] = $explodeArray[1];
			if($explodeArray[0] == 'new')      $propValue["new"][]      = ["tmp_name" => $explodeArray[1], "name" => $explodeArray[2]];
			}
		// старые файлы
		$filesList = CIBlockElement::GetProperty
			(
			$this->GetElementObject()->GetTableObject()->GetIblockId(),
			$this->GetElementObject()->GetElementId(),
			[],
			["CODE" => $this->GetAttributes()["code"]]
			);
		while($file = $filesList->GetNext())
			{
			if(in_array($file["VALUE"], $propValue["uploaded"]))
				$RESULT[] = ["VALUE" => CFile::MakeFileArray(CFile::GetFileArray($file["VALUE"])["SRC"]), "DESCRIPTION" => ''];
			else
				{
				CFile::Delete($file["VALUE"]);
				$RESULT[] = ["VALUE" => ["del" => 'Y']];
				}
			}
		// новые файлы
		foreach($propValue["new"] as $file)
			if($file["tmp_name"] && $file["name"])
				$RESULT[] =
					[
					"VALUE"       => array_merge(CFile::MakeFileArray($file["tmp_name"]), ["name" => $file["name"]]),
					"DESCRIPTION" => ''
					];
		return $RESULT;
		}
	/* ----------------------------------------------------------------- */
	/* --------------- преобразовать свойство для фильтра -------------- */
	/* ----------------------------------------------------------------- */
	public function UpdatePropertyForFilter()
		{
		$this->GetElementObject()->UnsetProperty($this->GetName());
		}
	}
?>