<?
IncludeModuleLangFile(__FILE__);
abstract class SDBElement
	{
	protected
		$tableObject,      // родительская таблица, объект SDBTable
		$elementId,        // ИД элемента/new
		$tableProps  = [], // массив объектов свойств элемента. Заполняется при создании объекта. Берутся свойства родительской таблицы
		$errors      = [], // массив ошибок
		$accessArray =     // массив досутпов к работе с элементом
			[
			"write"  => false, // доступ на запись
			"delete" => false  // доступ на удаление
			];
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	final public function __construct($tableObject = false, $elementId = '')
		{
		if($elementId != 'new') $elementId = (int)$elementId;
		if(!is_subclass_of($tableObject, 'SDBTable') || !$elementId) return false;
		// запись свойств
		$this->tableObject = $tableObject;
		$this->elementId   = $elementId;
		// конструктор
		foreach($this->tableObject->GetPropertyList() as $property => $propertyObject)
			$this->SetProperty($property);
		$this->ConstructObject();
		// доступ
		$this->CalculateAccess();
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- вспомогательные методы -------------------- */
	/* ----------------------------------------------------------------- */
	final public function GetTableObject()            {return $this->tableObject;}
	final public function GetElementId()              {return $this->elementId;}
	final public function GetProperty($property = '') {return $this->tableProps[$property];}
	final public function GetPropertyList()           {return $this->tableProps;}

	final public function GetErrors()                 {return $this->errors;}
	final public function SetError($error = '')       {if($error) $this->errors[] = $error;return false;}
	/* ----------------------------------------------------------------- */
	/* ----------------------- методы по доступу ----------------------- */
	/* ----------------------------------------------------------------- */
	final public function GetAccess($accessType = '') {return $this->accessArray[$accessType];}
	final public function GetAccessArray()            {return $this->accessArray;}

	final public function SetAccess($type = '', $value = false)
		{
		$this->accessArray[$type] = (boolean) $value;
		if($this->GetElementId() == 'new') $this->accessArray["delete"] = false;
		}

	final protected function CalculateAccess()
		{
		if($this->GetElementId() == 'new' && $this->GetTableObject()->GetAccess("create_element")) $this->accessArray["write"]  = true;
		if($this->GetElementId() != 'new' && $this->GetTableObject()->GetAccess("edit_element"))   $this->accessArray["write"]  = true;
		if($this->GetElementId() != 'new' && $this->GetTableObject()->GetAccess("delete_element")) $this->accessArray["delete"] = true;
		$this->AccessCalculating();
		}
	/* ----------------------------------------------------------------- */
	/* ----------------------- СВОЙСТВА - задать ----------------------- */
	/* ----------------------------------------------------------------- */
	final protected function SetProperty($property = '')
		{
		$propertyObject = $this->GetTableObject()->GetProperty($property);
		if($this->GetProperty($property) || !$propertyObject) return $this;

		$propertyObjectName = $this->GetTableObject()->GetPropertyTypes()[$propertyObject->GetType()]["element_property_class"];
		$this->tableProps[$property] = new $propertyObjectName($this, $property, $propertyObject->GetAttributes());
		return $this;
		}
	/* ----------------------------------------------------------------- */
	/* ---------------------- СВОЙСТВА - удалить ----------------------- */
	/* ----------------------------------------------------------------- */
	final public function UnsetProperty($property = '')
		{
		if($this->GetProperty($property)) unset($this->tableProps[$property]);
		return $this;
		}
	/* ----------------------------------------------------------------- */
	/* ------------------ СВОЙСТВА - изменить объект ------------------- */
	/* ----------------------------------------------------------------- */
	final public function ChangePropertyType($name = '', $type = '')
		{
		if(!$name || !$type) return false;
		$oldProperty        = $this->GetProperty($name);
		$propertyObjectName = $this->GetTableObject()->GetPropertyTypes()[$type]["element_property_class"];
		if(!$oldProperty || !$propertyObjectName) return false;

		$this->tableProps[$name] = new $propertyObjectName($this, $name, $oldProperty->GetAttributes());
		return $this->tableProps[$name];
		}
	/* ----------------------------------------------------------------- */
	/* ----------------------- сохранить элемент ----------------------- */
	/* ----------------------------------------------------------------- */
	final public function SaveElement(array $propsArray = [])
		{
		// проверка доступа
		if(!$this->GetAccess("write"))
			return $this->SetError(GetMessage("SF_FUNCTION_ERROR_DBE_SE_ELEMENT_NO_ACCESS"));
		// рабочие свойства
		if(!count($propsArray))
			foreach($this->GetPropertyList() as $property => $propertyObject)
				$propsArray[] = $property;
		foreach($propsArray as $index => $property)
			if(!$this->GetProperty($property) || !$this->GetProperty($property)->GetAccess("write"))
				unset($propsArray[$index]);
		$propsArray = SgetClearArray($propsArray);
		if(!$propsArray[0]) return $this->SetError(GetMessage("SF_FUNCTION_ERROR_DBE_SE_PROPS_NO_ACCESS"));
		// обязательные свойства
		$requiredPropsEmpty = false;
		foreach($propsArray as $property)
			if($this->GetProperty($property)->GetAttributes()["required"] != 'N')
				{
				$this->GetProperty($property)->GetValue();
				if(!$this->GetProperty($property)->GetValueParams()["value_geted"])
					{
					$this->SetError(str_replace('#PROP_NAME#', $property, GetMessage("SF_FUNCTION_ERROR_DBE_SE_REQUIERD_PROP_EMPTY")));
					$requiredPropsEmpty = true;
					}
				}
		if($requiredPropsEmpty) return false;
		// сохранение
		$savingResult = $this->ElementSaving($propsArray);
		$this->AfterElementSaving();
		return $savingResult;
		}
	/* ----------------------------------------------------------------- */
	/* ------------------------ удалить элемент ------------------------ */
	/* ----------------------------------------------------------------- */
	final public function DeleteElement()
		{
		if(!$this->GetAccess("delete")) return $this->SetError(GetMessage("SF_FUNCTION_ERROR_DBE_DE_ELEMENT_NO_ACCESS"));
		return $this->ElementDeleting();
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- МЕТОДЫ ДЛЯ ПЕРЕГРУЗКИ --------------------- */
	/* ----------------------------------------------------------------- */
	abstract protected function ConstructObject();
	abstract protected function AccessCalculating();

	abstract protected function ElementSaving(array $propsArray = []);
	abstract protected function AfterElementSaving();
	abstract protected function ElementDeleting();
	}
?>