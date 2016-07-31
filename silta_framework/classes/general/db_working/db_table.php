<?
IncludeModuleLangFile(__FILE__);
abstract class SDBTable
	{
	protected
		$elementsClass  = '',                                  // имя класса "элемент таблицы"
		$availableProps = [],                                  // массив имен допустимых свойств таблицы
		$tableProps     = [],                                  // массив объектов свойств таблицы. Заполняется при создании нового объекта SDBProperty через методы SetProperty и SetUserProperty
		$propertyTypes  =                                      // массив допустимых типов свойств таблицы. Обязателен для переопределения в наследуемом классе!!!
			[
			"test_type" =>                                          // название типа свойства
				[
				"title"                  => 'test property type',        // титул типа свойства
				"property_class"         => 'SIBlockPropertyTest',       // имя класса типа свойства таблицы
				"element_property_class" => 'SIBlockElementPropertyTest' // имя класса типа свойства элемента
				],
			],
		$queryOptions   =                                      // параметры выборки
			[
			"sorter"    => [],
			"filter"    => [],
			"navigator" => []
			],
		$queryCache     = [],                                  // кэш выборки
		$tableElements  = [],                                  // кэш объектов элементов таблицы
		$queryAccess    = [],                                  // фильтр по умолчанию (регулировка доступа)
		$tableAccess    =                                      // доступ
			[
			"create_element" => true,
			"edit_element"   => true,
			"delete_element" => true
			],
		$errors         = [];                                  // массив ошибок
	/* ----------------------------------------------------------------- */
	/* --------------------- вспомогательные методы -------------------- */
	/* ----------------------------------------------------------------- */
	final public function GetPropertyTypes() {return $this->propertyTypes;}
	final public function SetPropertyType($type = '', array $infoArray = [])
		{
		if($type && $infoArray["title"] && $infoArray["property_class"] && $infoArray["element_property_class"])
			$this->propertyTypes[$type] = $infoArray;
		}

	final public function ClearCache()
		{
		$this->queryCache    = [];
		$this->tableElements = [];
		}
	// доступ
	final public function SetAccess($type = '', $value = false) {$this->tableAccess[$type] = (boolean) $value;}
	final public function GetAccess($accessType = '')           {return $this->tableAccess[$accessType];}
	final public function GetAccessArray()                      {return $this->tableAccess;}
	// ошибки
	final public function GetErrors()                           {return $this->errors;}
	final public function SetError($error = '')                 {if($error) $this->errors[] = $error;return false;}
	final public function UnsetErrors()                         {$this->errors = [];}
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	final public function __construct(array $params = [])
		{
		$this->ConstructObject($params);
		}
	/* ----------------------------------------------------------------- */
	/* ---------------------- ВЫБОРКА - параметры ---------------------- */
	/* ----------------------------------------------------------------- */
	// задать
	final public function SetQueryOptions(array $params = [])
		{
		foreach($params["sorter"] as $index => $value)
			if($index && $value && !is_array($value))
				$this->queryOptions["sorter"] = [$index => $value];
		foreach($params["filter"] as $index => $value)
			if($value)
				$this->queryOptions["filter"][$index] = $value;
		foreach(["page", "page_size", "limit"] as $type)
			if($params["navigator"][$type])
				$this->queryOptions["navigator"][$type] = $params["navigator"][$type];
		return $this;
		}
	// получить
	final public function GetQueryOptions() {return $this->queryOptions;}
	// удалить
	final public function UnsetQueryOptions()
		{
		foreach($this->queryOptions as $index => $value) $this->queryOptions[$index] = [];
		return $this;
		}
	/* ----------------------------------------------------------------- */
	/* ----------------- ВЫБОРКА - регулировка доступа ----------------- */
	/* ----------------------------------------------------------------- */
	final public function SetQueryAccess(array $params = []) {foreach($params as $index => $value) if($value) $this->queryAccess[$index] = $value;return $this;}
	final public function GetQueryAccess()                   {return $this->queryAccess;}
	final public function UnsetQueryAccess()                 {$this->queryAccess = [];return $this;}
	/* ----------------------------------------------------------------- */
	/* ----------------------- ВЫБОРКА - получить ---------------------- */
	/* ----------------------------------------------------------------- */
	final public function GetQuery($querySorter = [], $queryFilter = [], $queryNavigator = [])
		{
		$cacheIndex = base64_encode(serialize(["sort" => $querySorter, "filter" => $queryFilter, "navigation" => $queryNavigator, "access_filter" => $this->GetQueryAccess()]));
		if($this->queryCache[$cacheIndex]) return $this->queryCache[$cacheIndex];
		// преобразование параметров выборки
		$querySorter    = $this->PrepareQuerySorter($querySorter);
		$queryFilter    = $this->PrepareQueryFilter($queryFilter);
		$defaultFilter  = $this->PrepareQueryFilter($this->GetQueryAccess());
		$queryNavigator = $this->PrepareQueryNavigator($queryNavigator);
		$emptyQuery     = false;
		// допустимые значения
		foreach($defaultFilter as $filterIndex => $valueArray)
			{
			if(!$queryFilter[$filterIndex])
				$queryFilter[$filterIndex] = $valueArray;
			else
				{
				foreach($queryFilter[$filterIndex] as $index => $value)
					if(!in_array($value, $valueArray))
						unset($queryFilter[$filterIndex][$index]);
				if(!count($queryFilter[$filterIndex]))
					$emptyQuery = true;
				}
			}
		// результат выборки
		if($emptyQuery) $this->queryCache[$cacheIndex] = [];
		else            $this->queryCache[$cacheIndex] = $this->RunQuery($querySorter, $queryFilter, $queryNavigator);
		return $this->queryCache[$cacheIndex];
		}
	/* ----------------------------------------------------------------- */
	/* ---------------------- СВОЙСТВА - получить ---------------------- */
	/* ----------------------------------------------------------------- */
	final public function GetProperty($property = '') {return $this->tableProps[$property];}
	final public function GetPropertyList()           {return $this->tableProps;}

	final public function GetAvailableProps()
		{
		if($this->availableProps[0]) return $this->availableProps;
		$this->availableProps = $this->CalculateAvailableProps();
		return $this->availableProps;
		}
	/* ----------------------------------------------------------------- */
	/* ----------------------- СВОЙСТВА - задать ----------------------- */
	/* ----------------------------------------------------------------- */
	final public function SetProperty($property = '')
		{
		if($this->GetProperty($property) || !in_array($property, $this->GetAvailableProps())) return $this;
		$propertyObject = $this->CreateProperty($property);
		if($propertyObject) $this->tableProps[$property] = $propertyObject;
		return $this;
		}
	/* ----------------------------------------------------------------- */
	/* --------------- СВОЙСТВА - задать пользовательское -------------- */
	/* ----------------------------------------------------------------- */
	final public function SetUserProperty($name = '', $type = '', array $attributes = [])
		{
		if($this->GetProperty($property))  return $this->tableProps[$property];
		if(!$name || !$type) exit(ShowError(__CLASS__.'::'.__FUNCTION__.' - '.GetMessage("SF_FUNCTION_ERROR_DBT_SUP")));

		if(!$attributes["code"]) $attributes["code"] = $name;
		$propertyObjectName = $this->GetPropertyTypes()[$type]["property_class"];
		$this->tableProps[$name] = new $propertyObjectName($this, $name, $attributes);
		return $this->tableProps[$name];
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
		$propertyObjectName = $this->GetPropertyTypes()[$type]["property_class"];
		if(!$oldProperty || !$propertyObjectName) return false;

		$this->tableProps[$name] = new $propertyObjectName($this, $name, $oldProperty->GetAttributes());
		return $this->tableProps[$name];
		}
	/* ----------------------------------------------------------------- */
	/* ------------------------ элемент таблицы ------------------------ */
	/* ----------------------------------------------------------------- */
	final public function GetElement($elementId = '')
		{
		if($elementId != 'new') $elementId = (int) $elementId;
		if(!$elementId) exit(ShowError(__CLASS__.'::'.__FUNCTION__.' - '.GetMessage("SF_FUNCTION_ERROR_DBT_GE")));
		$className = $this->elementsClass;
		// возврат объекта нового элемента
		if($elementId == 'new')
			{
			if(!$this->GetAccess("create_element")) return false;
			return new $className($this, "new");
			}
		// поиск закэшированного объекта
		if($this->tableElements[$elementId])
			return $this->tableElements[$elementId];
		// поиск в кэше выборки
		$elementInCache = false;
		foreach($this->queryCache as $valueArray)
			if(in_array($elementId, $valueArray))
				{
				$elementInCache = true;
				break;
				}
		if(!$elementInCache) $elementId = $this->GetQuery([], ["ID" => $elementId])[0];
		// возврат объекта
		if(!$elementId) return false;
		$this->tableElements[$elementId] = new $className($this, $elementId);
		return $this->tableElements[$elementId];
		}
	/* ----------------------------------------------------------------- */
	/* -------------------- получить фильтр-элемент -------------------- */
	/* ----------------------------------------------------------------- */
	final public function GetFilterElement()
		{
		$className     = $this->elementsClass;
		$elementObject = new $className($this, "new");
		foreach($elementObject->GetPropertyList() as $propertyObject)
			$propertyObject->UpdateForFilter();
		return $elementObject;
		}
	/* ----------------------------------------------------------------- */
	/* --------------- проверить таблицу на корректность --------------- */
	/* ----------------------------------------------------------------- */
	final public function CheckTableValidation(array $params = [])
		{
		$tableErrorsArray = [];
		// обязательные свойства
		foreach($params["props_existence"] as $property)
			if($property && !$this->GetProperty($property))
				$tableErrorsArray[] = str_replace('#PROP_NAME#', $property, GetMessage("SF_TABLE_ERROR_PROP_NOT_SET"));
		// проверка типов
		foreach($params["props_types"] as $property => $type)
			if
				(
				$property && $type
				&&
				$this->GetProperty($property)
				&&
				$this->GetPropertyTypes()[$type]["title"]
				&&
				$this->GetProperty($property)->GetType() != $type
				)
				{
				$alertText = str_replace('#PROP_NAME#', $property,                                 GetMessage("SF_TABLE_ERROR_PROP_TYPE_WRONG"));
				$alertText = str_replace('#PROP_TYPE#', $this->GetPropertyTypes()[$type]["title"], $alertText);
				$tableErrorsArray[] = $alertText;
				}
		// обязательные к заполнению свойства
		foreach($params["props_required"] as $property)
			if($this->GetProperty($property) && $this->GetProperty($property)->GetAttributes()["required"] != 'on')
				$tableErrorsArray[] = str_replace('#PROP_NAME#', $property, GetMessage("SF_TABLE_ERROR_PROP_NOT_REQUIRED"));
		// множ.свойства
		foreach($params["props_multiply"] as $property)
			if($this->GetProperty($property) && $this->GetProperty($property)->GetAttributes()["multiply"] != 'Y')
				$tableErrorsArray[] = str_replace('#PROP_NAME#', $property, GetMessage("SF_TABLE_ERROR_PROP_NOT_MULTIPLY"));
		// не множ.свойства
		foreach($params["props_not_multiply"] as $property)
			if($this->GetProperty($property) && $this->GetProperty($property)->GetAttributes()["multiply"] != 'N')
				$tableErrorsArray[] = str_replace('#PROP_NAME#', $property, GetMessage("SF_TABLE_ERROR_PROP_IS_MULTIPLY"));
		// ссылка на таблицу
		foreach($params["props_list_element"] as $property => $tableId)
			if
				(
				$this->GetProperty($property)
				&&
				$this->GetProperty($property)->GetType() != 'list_element'
				&&
				$this->GetProperty($property)->GetAttributes()["table"] != $tableId
				)
				{
				$alertText = str_replace('#PROP_NAME#', $property, GetMessage("SF_TABLE_ERROR_PROP_LIST_ELEMENT"));
				$alertText = str_replace('#TABLE_ID#',  $tableId,  $alertText);
				$tableErrorsArray[] = $alertText;
				}
		// возврат
		if(!count($tableErrorsArray))
			return true;
		else
			{
			foreach($tableErrorsArray as $error) $this->SetError($error);
			return false;
			}
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- МЕТОДЫ ДЛЯ ПЕРЕГРУЗКИ --------------------- */
	/* ----------------------------------------------------------------- */
	abstract protected function ConstructObject(array $params = []);

	abstract protected function RunQuery($querySorter = [], $queryFilter = [], $queryNavigator = []);
	abstract protected function PrepareQuerySorter($optionsArray = []);
	abstract protected function PrepareQueryFilter($optionsArray = []);
	abstract protected function PrepareQueryNavigator($optionsArray = []);

	abstract protected function CalculateAvailableProps();
	abstract protected function CreateProperty($property = '');
	}
?>