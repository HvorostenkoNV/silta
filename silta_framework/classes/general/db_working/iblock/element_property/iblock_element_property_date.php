<?
final class SIBlockElementPropertyDate extends SIBlockElementProperty
	{
	protected $propertyType = 'date';
	/* ----------------------------------------------------------------- */
	/* ---------------- ПРЕОБРАЗОВАНИЕ ЗНАЧЕНИЯ - из БД ---------------- */
	/* ----------------------------------------------------------------- */
	protected function ConvertDBValue(array $valueArray = [])
		{
		foreach($valueArray as $value)
			if($value)
				$RESULT[] = date('d.m.Y H:i', strtotime($value));
		return $RESULT;
		}
	/* ----------------------------------------------------------------- */
	/* --------------- ПРЕОБРАЗОВАНИЕ ЗНАЧЕНИЯ - из формы -------------- */
	/* ----------------------------------------------------------------- */
	protected function ConvertFormValue(array $valueArray = [])
		{
		return $this->ConvertDBValue($valueArray);
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
		$dateValue =
			[
			"start" => $this->value[0],
			"end"   => $this->value[1]
			];
		// значения по умолчанию
		if(!$dateValue["start"] && !$dateValue["end"]) return false;
		if(!$dateValue["start"]) $dateValue["start"] = '01.01.1970 00:00';
		if(!$dateValue["end"])   $dateValue["end"]   = date('d.m.Y H:i');
		// убераем время
		foreach($dateValue as $index => $value)
			{
			$explodeArray = explode(' ', $value);
			$dateValue[$index] = $explodeArray[0];
			}
		// добавляем +1 день для конечной даты интервала
		$dateValue["end"] = date("d.m.Y", AddToTimeStamp(["DD" => 1], MakeTimeStamp($dateValue["end"], "DD.MM.YYYY")));
		// для основных свойств
		if($this->GetAttributes()["main_info"] == 'N')
			foreach($dateValue as $index => $value)
				$dateValue[$index] = date('Y-m-d', strtotime($value));
		// возврат значений
		return [$dateValue["start"], $dateValue["end"]];
		}
	/* ----------------------------------------------------------------- */
	/* ------------------ получить значение свойства ------------------- */
	/* ----------------------------------------------------------------- */
	protected function GetPropertyValue($valueIndex = '')
		{
		// тип значения
		if(!in_array($valueIndex, ['date', 'time', 'full_date']))
			{
			    if($this->GetAttributes()["date"] == 'Y' && $this->GetAttributes()["time"] == 'N') $valueIndex = 'date';
			elseif($this->GetAttributes()["date"] == 'N' && $this->GetAttributes()["time"] == 'Y') $valueIndex = 'time';
			else                                                                                   $valueIndex = 'full_date';
			}
		// преобразование значений
		foreach($this->value as $value)
			{
			$explodeArray = explode(' ', $value);
			if($valueIndex == 'date') $value = $explodeArray[0];
			if($valueIndex == 'time') $value = $explodeArray[1];
			$RESULT[] = $value;
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
		return SgetClearArray($this->GetValue());
		}
	/* ----------------------------------------------------------------- */
	/* ----------------- получить свойство для фильтра ----------------- */
	/* ----------------------------------------------------------------- */
	public function UpdatePropertyForFilter()
		{
		$this->SetAttributes(["interval" => 'Y']);
		}
	}
?>