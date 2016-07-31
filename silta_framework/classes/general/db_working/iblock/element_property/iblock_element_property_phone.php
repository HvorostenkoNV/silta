<?
final class SIBlockElementPropertyPhone extends SIBlockElementProperty
	{
	protected $propertyType = 'phone';
	/* ----------------------------------------------------------------- */
	/* -------------- проверить тип телефона на валидность ------------- */
	/* ----------------------------------------------------------------- */
	protected function GetPhoneType($phoneType)
		{
		if(!$phoneType || !$this->GetAttributes()["phone_type"][$phoneType])
			foreach($this->GetAttributes()["phone_type"] as $type => $title)
				return $type;
		return $phoneType;
		}
	/* ----------------------------------------------------------------- */
	/* ---------------- ПРЕОБРАЗОВАНИЕ ЗНАЧЕНИЯ - из БД ---------------- */
	/* ----------------------------------------------------------------- */
	protected function ConvertDBValue(array $valueArray = [])
		{
		foreach($valueArray as $value)
			{
			$explodeArray = explode('|', $value);
			if(!$explodeArray[0]) continue;
			$RESULT[] = $explodeArray[0].'|'.$this->GetPhoneType($explodeArray[1]);
			}
		return $RESULT;
		}
	/* ----------------------------------------------------------------- */
	/* --------------- ПРЕОБРАЗОВАНИЕ ЗНАЧЕНИЯ - из формы -------------- */
	/* ----------------------------------------------------------------- */
	protected function ConvertFormValue(array $valueArray = [])
		{
		foreach($valueArray["number"] as $index => $value)
			$RESULT[] = $value.'|'.$this->GetPhoneType($valueArray["type"][$index]);
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
	protected function GetPropertyValue($valueIndex = false)
		{
		$RESULT = [];
		foreach($this->value as $value)
			{
			$explodeArray = explode('|', $value);
			$RESULT[] = ["number" => $explodeArray[0], "type" => $explodeArray[1]];
			}
		if($this->GetAttributes()["multiply"] == 'N') return $RESULT[0];
		return $RESULT;
		}
	/* ----------------------------------------------------------------- */
	/* ---------------- получить массив для сохранения ----------------- */
	/* ----------------------------------------------------------------- */
	protected function PrepareSavingArray()
		{
		return $this->value;
		}
	/* ----------------------------------------------------------------- */
	/* ----------------- получить свойство для фильтра ----------------- */
	/* ----------------------------------------------------------------- */
	public function UpdatePropertyForFilter()
		{
		$this->ChangeType("string");
		return $this->GetElementObject()->GetProperty($this->GetName());
		}
	}
?>