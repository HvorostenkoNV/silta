<?
abstract class SDBProperty
	{
	private
		$tableObject  = false, // родительская таблица, объект SDBTable
		$propertyName = '',    // имя свойства
		$propertyType = '',    // тип свойства
		$attributes   =        // массив аттрибутов свойства
			[
			"code"            => '',  // символьный код свойства
			"title"           => '',  // обязательность свойства к заполнению = Y/N
			"sort"            => '',  // порядок свойства в инфоблоке
			"multiply"        => 'N', // множественность значений свойства = Y/N
			"required"        => 'N', // обязательность свойства к заполнению = on/off/N
			"default_value"   => [],  // массив значений по умолчанию
			"available_value" => []   // массив допустимых значений
			];
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	final public function __construct($tableObject = false, $propertyName = '', array $attributes = [])
		{
		if(!is_subclass_of($tableObject, 'SDBTable') || !$propertyName) SthrowFunctionError(GetMessage("SF_FUNCTION_ERROR_DBP_CONSTRUCTOR"));
		$this->tableObject  = $tableObject;
		$this->propertyName = $propertyName;
		$this->ConstructObject();
		if(!$this->GetType()) SthrowFunctionError(GetMessage("SF_PROPERTY_ERROR_TYPE_NOT_EXIST"));
		$this->SetAttributes($attributes);
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- вспомогательные методы -------------------- */
	/* ----------------------------------------------------------------- */
	final public function GetTableObject() {return $this->tableObject;}
	final public function GetName()        {return $this->propertyName;}
	final public function GetType()        {return $this->propertyType;}

	final public function ChangeType($type = '')
		{
		$this->GetTableObject()->ChangePropertyType($this->GetName(), $type);
		return $this->GetTableObject()->GetProperty($this->GetName());
		}

	final protected function SetAttributeValue($index = '', $value = '') {$this->attributes[$index] = $value;return $this;}
	final protected function SetObjectType($value = '')                  {$this->propertyType = $value;      return $this;}
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
	/* --------------------- МЕТОДЫ ДЛЯ ПЕРЕГРУЗКИ --------------------- */
	/* ----------------------------------------------------------------- */
	abstract protected function ConstructObject();
	}
?>