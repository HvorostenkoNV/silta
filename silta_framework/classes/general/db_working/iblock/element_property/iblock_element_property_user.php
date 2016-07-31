<?
final class SIBlockElementPropertyUser extends SIBlockElementProperty
	{
	protected $propertyType = 'user';
	/* ----------------------------------------------------------------- */
	/* ---------------- ПРЕОБРАЗОВАНИЕ ЗНАЧЕНИЯ - из БД ---------------- */
	/* ----------------------------------------------------------------- */
	protected function ConvertDBValue(array $valueArray = [])
		{
		foreach($valueArray as $value)
			{
			$index = 'user';
			if(substr_count($value, '|'))
				{
				$explodeArray = explode('|', $value);
				$value = $explodeArray[1];
				if(in_array($explodeArray[0], ['user', 'department']))
					$index = $explodeArray[0];
				}
			$value = (int)$value;
			if($value) $RESULT[] = $index.'|'.$value;
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
		$usersArray = $this->GetUsersArray();
		if(!$usersArray[0]) return false;
		// фильтр по юзерам
		foreach($usersArray as $userId)
			{
			$RESULT[] = $userId;
			$RESULT[] = 'user|'.$userId;
			}
		// фильтр по отделам
		$usersList = CUser::GetList($by = "ID", $order = "desc", ["ID" => implode('|', $usersArray)], ["FIELDS" => ["ID"], "SELECT" => ["UF_DEPARTMENT"]]);
		while($user = $usersList->GetNext())
			foreach($user["UF_DEPARTMENT"] as $departmentId)
				{
				$departmentsList = CIBlockSection::GetNavChain(false, $departmentId);
				while($department = $departmentsList -> GetNext()) $RESULT[] = 'department|'.$department["ID"];
				}
		// возврат
		return $RESULT;
		}
	/* ----------------------------------------------------------------- */
	/* ------------------ получить значение свойства ------------------- */
	/* ----------------------------------------------------------------- */
	protected function GetPropertyValue($valueIndex = '')
		{
		// тип значения
		if(!in_array($valueIndex, ['users', 'departments']))
			{
			$valueIndex = 'users';
			if($this->GetAttributes()["users"] == 'N' && $this->GetAttributes()["departments"] == 'Y') $valueIndex = 'departments';
			}
		// проход по массиву значений
		foreach($this->value as $value)
			{
			$explodeArray = explode('|', $value);
			if
				(
				($explodeArray[0] == 'user'       && $valueIndex == 'users')
				||
				($explodeArray[0] == 'department' && $valueIndex == 'departments')
				)
				$RESULT[] = $explodeArray[1];
			}
		// возврат
		if($this->GetAttributes()["multiply"] == 'N') $RESULT = $RESULT[0];
		return $RESULT;
		}
	/* ----------------------------------------------------------------- */
	/* ---------------- получить массив для сохранения ----------------- */
	/* ----------------------------------------------------------------- */
	protected function PrepareSavingArray()
		{
		// значения
		foreach(["users", "departments"] as $type)
			{
			$value = $this->GetValue($type);
			if(!is_array($value)) $value = [$value];
			if($value[0]) $propValue[$type] = $value;
			}
		// users only
		if(!$propValue["departments"][0]) return $propValue["users"];
		// full value
		foreach($propValue["users"]       as $value) $RESULT[] = 'user|'.$value;
		foreach($propValue["departments"] as $value) $RESULT[] = 'department|'.$value;
		return $RESULT;
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- получить массив юзеров -------------------- */
	/* ----------------------------------------------------------------- */
	public function GetUsersArray()
		{
		if(!$this->GetValueParams()["is_set"]) $this->SetValue($this->GetQuery(), 'db');
		// ИД юзеров
		foreach($this->value as $value)
			{
			$explodeArray = explode('|', $value);
			if($explodeArray[0] == 'user') $RESULT[]      = $explodeArray[1];
			else                           $DEPARTMENTS[] = $explodeArray[1];
			}
		// ИД отделов + вложенные отделы
		foreach($DEPARTMENTS as $departmentId)
			{
			$dbSection = CIBlockSection::GetByID($departmentId);
			if($section = $dbSection->GetNext())
				{
				$sectionList = CIBlockSection::GetList
					(
					[],
						[
						'IBLOCK_ID'     => $section['IBLOCK_ID'],
						'>LEFT_MARGIN'  => $section['LEFT_MARGIN'],
						'<RIGHT_MARGIN' => $section['RIGHT_MARGIN'],
						'>DEPTH_LEVEL'  => $section['DEPTH_LEVEL']
						]
					);
				while($sectionResulr = $sectionList->GetNext())
					$DEPARTMENTS[] = $sectionResulr["ID"];
				}
			}
		// ИД юзеров из отделов
		if($DEPARTMENTS[0])
			{
			$usersList = CUser::GetList($by = "ID", $order = "asc" , ["UF_DEPARTMENT" => $DEPARTMENTS], ["FIELDS" => ["ID"]]);
			while($user = $usersList->GetNext()) $RESULT[] = $user["ID"];
			}
		// возврат
		return $RESULT;
		}
	/* ----------------------------------------------------------------- */
	/* ----------------- получить свойство для фильтра ----------------- */
	/* ----------------------------------------------------------------- */
	public function UpdatePropertyForFilter()
		{
		$this->SetAttributes(["users" => 'Y', "departments" => 'Y']);
		}
	}
?>