<?
final class SIBlockElementPropertySection extends SIBlockElementProperty
	{
	protected $propertyType = 'section';
	/* ----------------------------------------------------------------- */
	/* ---------------- ПРЕОБРАЗОВАНИЕ ЗНАЧЕНИЯ - из БД ---------------- */
	/* ----------------------------------------------------------------- */
	protected function ConvertDBValue(array $valueArray = [])
		{
		foreach($valueArray as $value)
			{
			$value = (int)$value;
			if($value) $RESULT[] = $value;
			}
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
		return $this->value;
		}
	/* ----------------------------------------------------------------- */
	/* ------------------ получить значение свойства ------------------- */
	/* ----------------------------------------------------------------- */
	protected function GetPropertyValue($valueIndex = '')
		{
		if(!$valueIndex) $RESULT = $this->value;
		if($valueIndex == 'title' && $this->value[0])
			{
			$sectionList = CIBlockSection::GetList(["SORT" => 'asc'], ["ID" => $this->value], false, ["ID", "NAME",], false);
			while($section = $sectionList->GetNext()) $titlesArray[$section["ID"]] = $section["NAME"];
			foreach($this->value as $departmentId) $RESULT[] = $titlesArray[$departmentId];
			}

		if($this->GetAttributes()["multiply"] == 'N') return $RESULT[0];
		else                                          return $RESULT;
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
	public function UpdatePropertyForFilter() {}
	}
?>