<?
final class SIBlockElementPropertyList extends SIBlockElementProperty
	{
	protected $propertyType = 'list';
	/* ----------------------------------------------------------------- */
	/* ---------------- ПРЕОБРАЗОВАНИЕ ЗНАЧЕНИЯ - из БД ---------------- */
	/* ----------------------------------------------------------------- */
	protected function ConvertDBValue(array $valueArray = [])
		{
		foreach($valueArray as $value)
			foreach($this->GetAttributes()["list"] as $listInfo)
				if($value == $listInfo["value"] || $value == $listInfo["code"])
					$RESULT[] = $listInfo["code"];
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
		return $this->GetValue("id");
		}
	/* ----------------------------------------------------------------- */
	/* ------------------ получить значение свойства ------------------- */
	/* ----------------------------------------------------------------- */
	protected function GetPropertyValue($valueIndex = '')
		{
		if(!in_array($valueIndex, ['code', 'title', 'id'])) $valueIndex = 'code';
		// проход по списку
		foreach($this->value as $value)
			foreach($this->GetAttributes()["list"] as $listInfo)
				if($value == $listInfo["code"])
					{
					if($valueIndex == 'title') $RESULT[] = $listInfo["title"];
					if($valueIndex == 'code')  $RESULT[] = $listInfo["code"];
					if($valueIndex == 'id')    $RESULT[] = $listInfo["value"];
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
		return $this->GetValue("id");
		}
	/* ----------------------------------------------------------------- */
	/* ----------------- получить свойство для фильтра ----------------- */
	/* ----------------------------------------------------------------- */
	public function UpdatePropertyForFilter() {}
	}
?>