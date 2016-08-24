<?
abstract class SDBElementProperty
	{
	protected
		$elementObject = false, // родительский элемент, объект SDBElement
		$propertyName  = '',    // имя свойства
		$propertyType  = '',    // тип свойства
		$attributes    = [],    // массив аттрибутов свойства. Копия аттрибутов таблицы
		$value         = [],    // массив значений свойства
		$valueParams   =        // массив параметров значений свойства
			[
			"is_set"      => false,  // значение было установленно(возможно пустое)
			"value_geted" => false,  // установленное значение не пустое
			"default"     => false   // установленное значение является значением по умолчанию
			],
		$accessArray =          // массив досутпов к работе со свойством
			[
			"write" => true          // доступ на запись
			];
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	final public function __construct($elementObject = false, $propertyName = '', array $propertyAttributes = [])
		{
		if(!is_subclass_of($elementObject,  'SDBElement') || !$propertyName) return false;
		$this->elementObject = $elementObject;
		$this->propertyName  = $propertyName;
		$this->attributes    = $propertyAttributes;
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- вспомогательные методы -------------------- */
	/* ----------------------------------------------------------------- */
	final public function GetElementObject() {return $this->elementObject;}
	final public function GetTableObject()   {return $this->GetElementObject()->GetTableObject();}
	final public function GetName()          {return $this->propertyName;}
	final public function GetType()          {return $this->propertyType;}
	final public function ChangeType($type = '')
		{
		$this->GetElementObject()->ChangePropertyType($this->GetName(), $type);
		return $this->GetElementObject()->GetProperty($this->GetName());
		}
	/* ----------------------------------------------------------------- */
	/* ----------------------- методы по доступу ----------------------- */
	/* ----------------------------------------------------------------- */
	final public function GetAccess($accessType = '')           {return $this->accessArray[$accessType];}
	final public function GetAccessArray()                      {return $this->accessArray;}
	final public function SetAccess($type = '', $value = false) {$this->accessArray[$type] = (boolean) $value;}
	/* ----------------------------------------------------------------- */
	/* --------------------------- аттрибуты --------------------------- */
	/* ----------------------------------------------------------------- */
	final public function GetAttributes() {return $this->attributes;}
	final public function SetAttributes(array $params = [])
		{
		// заливка новых значений
		foreach($params as $index => $value)
			{
			$mergeType = 'replace';
			if(substr_count($index, '[]'))
				{
				$mergeType = 'merge';
				$index = str_replace('[]', '', $index);
				}
			if(array_key_exists($index, $this->attributes))
				{
				if(!is_array($this->attributes[$index]))
					$this->attributes[$index] = $value;
				else
					{
					if(!is_array($value)) $value = [$value];
					if($mergeType == 'merge') $value = array_merge($this->attributes[$index], $value);
					$this->attributes[$index] = $value;
					}
				}
			}
		// корректировка значений
		if(!in_array($this->attributes["multiply"], ["Y", "N"]))         $this->attributes["multiply"] = 'N';
		if(!in_array($this->attributes["required"], ["on", "off", "N"])) $this->attributes["required"] = 'N';
		// возврат значения
		return $this;
		}
	/* ----------------------------------------------------------------- */
	/* ----------------------- ЗНАЧЕНИЕ - задать ----------------------- */
	/* ----------------------------------------------------------------- */
	final public function SetValue($valueArray = [], $type = '')
		{
		if(!is_array($valueArray)) $valueArray = [$valueArray];
		// преобразование значения
		if($type == 'db' && $this->GetElementObject()->GetElementId() != 'new') $valueArray = $this->ConvertDBValue  ($valueArray);
		elseif($type == 'form')                                                 $valueArray = $this->ConvertFormValue($valueArray);
		else                                                                    $valueArray = $this->ConvertUserValue($valueArray);
		// установка значения
		$this->value = SgetClearArray($valueArray);
		$this->valueParams =
			[
			"is_set"      => true,
			"value_geted" => false,
			"default"     => false
			];
		// допустимые значения
		$availableValue = SgetClearArray($this->GetAvailableValue());
		if($availableValue[0])
			{
			$clearValue = [];
			foreach($this->value as $value)
				if(in_array($value, $availableValue))
					$clearValue[] = $value;
			$this->value = $clearValue;
			}
		// значение получено
		if($this->value[0])
			$this->valueParams["value_geted"] = true;
		// значение по умолчанию
		$defaultValue = SgetClearArray($this->GetDefaultValue());
		if(!$this->valueParams["value_geted"] && $defaultValue[0])
			{
			$this->value = $defaultValue;
			$this->valueParams["default"] = true;
			}
		// возврат
		return $this;
		}

	final public function UnsetValue()
		{
		$this->value = [];
		$this->valueParams =
			[
			"is_set"      => true,
			"value_geted" => true,
			"default"     => false
			];
		}
	/* ----------------------------------------------------------------- */
	/* ---------------------- ЗНАЧЕНИЕ - получить ---------------------- */
	/* ----------------------------------------------------------------- */
	final public function GetValue($valueType = '')
		{
		if(!$this->GetValueParams()["is_set"]) $this->SetValue($this->GetQuery(), 'db');
		return $this->GetPropertyValue($valueType);
		}

	final public function GetValueParams()    {return $this->valueParams;}
	final public function GetAvailableValue() {return $this->ConvertUserValue($this->GetAttributes()["available_value"]);}
	final public function GetDefaultValue()   {return $this->ConvertUserValue($this->GetAttributes()["default_value"]);}
	/* ----------------------------------------------------------------- */
	/* ----------------- получить значения для фильтра ----------------- */
	/* ----------------------------------------------------------------- */
	final public function GetFilter()
		{
		if(!$this->GetValueParams()["is_set"]) $this->SetValue($this->GetQuery(), 'db');
		return SgetClearArray($this->PrepareFilterValue());
		}
	/* ----------------------------------------------------------------- */
	/* ------------ получить массив для сохранения элемента ------------ */
	/* ----------------------------------------------------------------- */
	final public function GetSavingArray()
		{
		if(!$this->GetValueParams()["is_set"]) $this->SetValue($this->GetQuery(), 'db');
		return SgetClearArray($this->PrepareSavingArray());
		}
	/* ----------------------------------------------------------------- */
	/* --------------- преобразовать свойство для фильтра -------------- */
	/* ----------------------------------------------------------------- */
	final public function UpdateForFilter()
		{
		$this->SetAccess("write", true);
		$this->SetAttributes(["required" => 'N', "multiply" => 'Y']);
		$this->UpdatePropertyForFilter();
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- МЕТОДЫ ДЛЯ ПЕРЕГРУЗКИ --------------------- */
	/* ----------------------------------------------------------------- */
	abstract protected function ConvertDBValue  (array $valueArray = []);
	abstract protected function ConvertFormValue(array $valueArray = []);
	abstract protected function ConvertUserValue(array $valueArray = []);

	abstract protected function PrepareFilterValue();
	abstract protected function PrepareSavingArray();

	abstract protected function GetPropertyValue($valueType = '');
	abstract protected function GetQuery();

	abstract protected function UpdatePropertyForFilter();
	}
?>