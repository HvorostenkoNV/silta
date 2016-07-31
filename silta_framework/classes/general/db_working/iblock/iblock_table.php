<?
IncludeModuleLangFile(__FILE__);
class SIBlockTable extends SDBTable
	{
	protected
		$iblockId,
		$iblockCode,
		$elementsClass = 'SIBlockElement';

	protected static
		$mainInfoProps =
			[
			"name"         => ["code" => 'NAME',        "type" => 'string', "title" => 'Название', "required" => 'on'],
			"created_by"   => ["code" => 'CREATED_BY',  "type" => 'user',   "title" => 'Кем создано'],
			"changed_by"   => ["code" => 'MODIFIED_BY', "type" => 'user',   "title" => 'Кем изменено'],
			"created_date" => ["code" => 'DATE_CREATE', "type" => 'date',   "title" => 'Дата создания'],
			"changed_date" => ["code" => 'TIMESTAMP_X', "type" => 'date',   "title" => 'Дата изменения'],

			"active"      => ["code" => 'ACTIVE',      "type" => 'boolean', "title" => 'Активность'],
			"active_from" => ["code" => 'ACTIVE_FROM', "type" => 'date',    "title" => 'Начало активности'],
			"active_to"   => ["code" => 'ACTIVE_TO',   "type" => 'date',    "title" => 'Окончание активности'],
			];
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject(array $params = [])
		{
		if(!$params["id"] &&  $params["code"]) $filterArray = ["CODE" => $params["code"]];
		if( $params["id"] && !$params["code"]) $filterArray = ["ID"   => $params["id"]];
		if(!$filterArray)                      exit(__CLASS__.'::'.__FUNCTION__.' - '.GetMessage("SF_FUNCTION_ERROR_IBE_CONTSR_PARAMS_ERROR"));
		// поиск инфоблока
		$iblockQuery = CIBlock::GetList([], $filterArray, false, false, ["ID", "CODE"]);
		while($infoArray = $iblockQuery->Fetch())
			{
			$this->iblockId   = $infoArray["ID"];
			$this->iblockCode = $infoArray["CODE"];
			}
		if(!$this->iblockId) exit(__CLASS__.'::'.__FUNCTION__.' - '.GetMessage("SF_FUNCTION_ERROR_IBE_IBLOCK_NOT_FOUNDED"));
		// типы свойств
		$this->propertyTypes =
			[
			"string" =>
				[
				"title"                  => GetMessage("SF_IBLOCK_PROPS_TITLE_STRING"),
				"property_class"         => 'SIBlockPropertyString',
				"element_property_class" => 'SIBlockElementPropertyString'
				],
			"number" =>
				[
				"title"                  => GetMessage("SF_IBLOCK_PROPS_TITLE_NUMBER"),
				"property_class"         => 'SIBlockPropertyNumber',
				"element_property_class" => 'SIBlockElementPropertyNumber'
				],
			"date" =>
				[
				"title"                  => GetMessage("SF_IBLOCK_PROPS_TITLE_DATE"),
				"property_class"         => 'SIBlockPropertyDate',
				"element_property_class" => 'SIBlockElementPropertyDate'
				],
			"text" =>
				[
				"title"                  => GetMessage("SF_IBLOCK_PROPS_TITLE_TEXT"),
				"property_class"         => 'SIBlockPropertyText',
				"element_property_class" => 'SIBlockElementPropertyText'
				],
			"section" =>
				[
				"title"                  => GetMessage("SF_IBLOCK_PROPS_TITLE_SECTION"),
				"property_class"         => 'SIBlockPropertySection',
				"element_property_class" => 'SIBlockElementPropertySection'
				],
			"list" =>
				[
				"title"                  => GetMessage("SF_IBLOCK_PROPS_TITLE_LIST"),
				"property_class"         => 'SIBlockPropertyList',
				"element_property_class" => 'SIBlockElementPropertyList'
				],
			"boolean" =>
				[
				"title"                  => GetMessage("SF_IBLOCK_PROPS_TITLE_BOOLEAN"),
				"property_class"         => 'SIBlockPropertyBoolean',
				"element_property_class" => 'SIBlockElementPropertyBoolean'
				],
			"user" =>
				[
				"title"                  => GetMessage("SF_IBLOCK_PROPS_TITLE_USER"),
				"property_class"         => 'SIBlockPropertyUser',
				"element_property_class" => 'SIBlockElementPropertyUser'
				],
			"list_element" =>
				[
				"title"                  => GetMessage("SF_IBLOCK_PROPS_TITLE_TABLE_ELEMENT"),
				"property_class"         => 'SIBlockPropertyListElement',
				"element_property_class" => 'SIBlockElementPropertyListElement'
				],
			"file" =>
				[
				"title"                  => GetMessage("SF_IBLOCK_PROPS_TITLE_FILE"),
				"property_class"         => 'SIBlockPropertyFile',
				"element_property_class" => 'SIBlockElementPropertyFile'
				],
			"phone" =>
				[
				"title"                  => GetMessage("SF_IBLOCK_PROPS_TITLE_PHONE"),
				"property_class"         => 'SIBlockPropertyPhone',
				"element_property_class" => 'SIBlockElementPropertyPhone'
				]
			];
		}
	/* ----------------------------------------------------------------- */
	/* ------------------------- простые методы ------------------------ */
	/* ----------------------------------------------------------------- */
	final public function GetIblockId()   {return $this->iblockId;}
	final public function GetIblockCode() {return $this->iblockCode;}
	/* ----------------------------------------------------------------- */
	/* ----------------------- получить выборку ------------------------ */
	/* ----------------------------------------------------------------- */
	protected function RunQuery($querySorter = [], $queryFilter = [], $queryNavigator = [])
		{
		if(!count($querySorter))  $querySorter = ["ID" => 'asc'];
		if(!$querySorter["name"]) $querySorter["name"] = 'asc';
		$queryFilter["IBLOCK_ID"] = $this->GetIblockId();
		if(!count($queryNavigator)) $queryNavigator = false;

		$elementList = CIBlockElement::GetList($querySorter, $queryFilter, false, $queryNavigator, ["ID"]);
		while($element = $elementList->GetNext()) $RESULT[] = $element["ID"];
		return array_unique($RESULT);
		}
	/* ----------------------------------------------------------------- */
	/* ----------------- ВЫБОРКА - получить сортировку ----------------- */
	/* ----------------------------------------------------------------- */
	protected function PrepareQuerySorter($optionsArray = [])
		{
		foreach($optionsArray as $index => $value)
			{
			$propertyObject = $this->GetProperty($index);
			if($propertyObject)
				{
				$index = $propertyObject->GetAttributes()["code"];
				if($propertyObject->GetAttributes()["main_info"] == 'N') $index = 'PROPERTY_'.$index;
				}
			$RESULT = [$index => $value];
			}
		return $RESULT;
		}
	/* ----------------------------------------------------------------- */
	/* ------------------- ВЫБОРКА - получить фильтр ------------------- */
	/* ----------------------------------------------------------------- */
	protected function PrepareQueryFilter($optionsArray = [])
		{
		$prefixesArray = ['>=', '<=', '><', '!><', '>', '<', '!=', '=', '!', '*'];
		foreach($optionsArray as $index => $value)
			{
			// префиксы
			unset($prefix);
			foreach($prefixesArray as $code)
				if(substr_count($index, $code))
					{
					$prefix = $code;
					$index  = str_replace($code, '', $index);
					}
			// использование свойств таблицы
			$className        = $this->elementsClass;
			$NewElementObject = new $className($this, "new");
			$propertyObject   = $NewElementObject->GetProperty($index);
			if($propertyObject)
				{
				$index = $propertyObject->GetAttributes()["code"];
				if($propertyObject->GetAttributes()["main_info"] == 'N') $index  = 'PROPERTY_'.$index;
				if($prefix != '*')                                       $value  = $propertyObject->SetValue($value)->GetFilter();
				if($propertyObject->GetType() == "date")                 $prefix = '><';
				}
			// установка фильтра
			if($prefix == '*') unset($prefix);
			$value = SgetClearArray($value);
			if($value[0]) $RESULT[$prefix.$index] = $value;
			}
		return $RESULT;
		}
	/* ----------------------------------------------------------------- */
	/* ------------------ ВЫБОРКА - получить навигатор ----------------- */
	/* ----------------------------------------------------------------- */
	protected function PrepareQueryNavigator($optionsArray = [])
		{
		foreach(["page" => 'iNumPage', "page_size" => 'nPageSize', "limit" => 'nTopCount'] as $code => $bitrixCode)
			if($optionsArray[$code])
				$RESULT[$bitrixCode] = $optionsArray[$code];
		return $RESULT;
		}
	/* ----------------------------------------------------------------- */
	/* -------------- получить массив допустимых свойств --------------- */
	/* ----------------------------------------------------------------- */
	protected function CalculateAvailableProps()
		{
		foreach(self::$mainInfoProps as $property => $propertyInfo) $RESULT[] = $property;
		$propertyList = CIBlock::GetProperties($this->GetIblockId(), ["sort" => "asc"], []);
		while($property = $propertyList->GetNext()) $RESULT[] = $property["CODE"];
		return $RESULT;
		}
	/* ----------------------------------------------------------------- */
	/* ----------------------- создать свойство ------------------------ */
	/* ----------------------------------------------------------------- */
	protected function CreateProperty($property = '')
		{
		$attributesArray = [];
		$propertyType    = '';
		/* ------------------------------------ */
		/* -------- основные свойства --------- */
		/* ------------------------------------ */
		if(self::$mainInfoProps[$property])
			{
			$attributesArray = array_merge(self::$mainInfoProps[$property], ["main_info" => 'Y']);
			$propertyType    = self::$mainInfoProps[$property]["type"];
			}
		/* ------------------------------------ */
		/* --------- свойство таблицы --------- */
		/* ------------------------------------ */
		else
			{
			$propertyList = CIBlockProperty::GetList(["sort" => 'asc'], ["IBLOCK_ID" => $this->GetIblockId(), "CODE" => $property]);
			while($propertyInfo = $propertyList->GetNext())
				{
				// основная инфа
				$attributesArray =
					[
					"id"       => $propertyInfo["ID"],
					"code"     => $propertyInfo["CODE"],

					"title"    => $propertyInfo["NAME"],
					"sort"     => $propertyInfo["SORT"],
					"multiply" => $propertyInfo["MULTIPLE"],
					"table"    => $propertyInfo["LINK_IBLOCK_ID"]
					];
				if($propertyInfo["IS_REQUIRED"] == 'Y') $attributesArray["required"] = 'on';
				// опр-е типа свойства
				switch($propertyInfo["PROPERTY_TYPE"])
					{
					case "S": $propertyType = 'string';      break;
					case "N": $propertyType = 'number';      break;
					case "G": $propertyType = 'section';     break;
					case "L": $propertyType = 'list';        break;
					case "E": $propertyType = 'list_element';break;
					case "F": $propertyType = 'file';        break;
					}
				if(in_array($propertyInfo["USER_TYPE"], ["UserID", "employee"])) $propertyType = 'user';
				if(in_array($propertyInfo["USER_TYPE"], ["Date",   "DateTime"])) $propertyType = 'date';
				if($propertyInfo["USER_TYPE"] == 'HTML')                         $propertyType = 'text';
				}
			}
		/* ------------------------------------ */
		/* --------- создание объекта --------- */
		/* ------------------------------------ */
		$propertyObjectName = $this->GetPropertyTypes()[$propertyType]["property_class"];
		if(!$propertyObjectName || !$attributesArray) return false;
		return new $propertyObjectName($this, $property, $attributesArray);
		}
	}
?>