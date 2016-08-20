<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_framework"))                  return ShowError("module silta_framework required!");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

PROPERTY_OBJECT - объект свойства элемента (SDBElementProperty)
FIELD_TYPE      - тип поля read/write
FIELD_PARAMS    - массив параметров поля
*/
$propertyObject = $arParams["PROPERTY_OBJECT"];
if(!is_subclass_of($propertyObject, 'SDBElementProperty')) return;
$propAttributes = $propertyObject->GetAttributes();
/* ============================================================================================= */
/* ===================================== СВОЙСТВА НА ЧТЕНИЕ ==================================== */
/* ============================================================================================= */
if($arParams["FIELD_TYPE"] == 'read')
	switch($propertyObject->GetType())
		{
		// property string
		case "string":
			$arResult["value_read"] = SgetClearArray($propertyObject->GetValue());
			break;
		// property number
		case "number":
			$arResult["value_read"] = SgetClearArray($propertyObject->GetValue());
			break;
		// property date
		case "date":
			$value = SgetClearArray($propertyObject->GetValue());
			if($propAttributes["interval"] == 'N') $arResult["value_read"] = $value;
			else                                   $arResult["value_read"] = [$value[0].' - '.$value[1]];
			break;
		// property file
		case "file":
			$arResult["iblock_file_links"] = SgetClearArray($propertyObject->GetValue("uploaded"));
			break;
		// property text
		case "text":
			foreach(SgetClearArray($propertyObject->GetValue()) as $value)
				$arResult["value_read"][] = htmlspecialchars_decode(TxtToHTML(nl2br($value)));
			break;
		// property list
		case "list":
			$arResult["value_read"] = SgetClearArray($propertyObject->GetValue("title"));
			break;
		// boolean
		case "boolean":
			$arResult["value_read"] = SgetClearArray($propertyObject->GetValue("title"));
			break;
		// list element
		case "list_element":
			$arResult =
				[
				"iblock_elements" => SgetClearArray($propertyObject->GetValue()),
				"table"           => $propAttributes["table"],
				"props"           => $arParams["FIELD_PARAMS"]["PROPS"]
				];
			break;
		// user
		case "user":
			$arResult["user_links"]       = SgetClearArray($propertyObject->GetValue("users"));
			$arResult["department_links"] = SgetClearArray($propertyObject->GetValue("departments"));
			break;
		// phone
		case "phone":
			foreach($propertyObject->GetValue() as $arrayInfo)
				$arResult["phones"][] =
					[
					"number" => $arrayInfo["number"],
					"type"   => $propertyObject->GetAttributes()["phone_type"][$arrayInfo["type"]]
					];
			break;
		// section
		case "section":
			$arResult["value_read"] = SgetClearArray($propertyObject->GetValue("title"));
			break;
		}
/* ============================================================================================= */
/* ===================================== СВОЙСТВА НА ЗАПИСЬ ==================================== */
/* ============================================================================================= */
if($arParams["FIELD_TYPE"] == 'write')
	switch($propertyObject->GetType())
		{
		/* ------------------------------------------------------------------ */
		/* ------------------------- property string ------------------------ */
		/* ------------------------------------------------------------------ */
		case "string":
			// значения
			$value = SgetClearArray($propertyObject->GetValue());
			if(!$value[0]) $value = [''];
			// имя поля
			$inputName = $arParams["FIELD_PARAMS"]["INPUT_NAME"];
			if($inputName && $propAttributes["multiply"] == 'Y') $inputName .= '[]';
			// готовый массив
			$arResult =
				[
				"type"         => 'input_string',
				"value"        => $value,
				"multiply"     => $propAttributes["multiply"],
				"field_params" =>
					[
					"input_name"  => $inputName,
					"attr"        => $arParams["FIELD_PARAMS"]["ATTR"],
					"placeholder" => $arParams["FIELD_PARAMS"]["PLACEHOLDER"],
					"size"        => $arParams["FIELD_PARAMS"]["SIZE"],
					"mask"        => $arParams["FIELD_PARAMS"]["MASK"]
					]
				];
			break;
		/* ------------------------------------------------------------------ */
		/* ------------------------- property number ------------------------ */
		/* ------------------------------------------------------------------ */
		case "number":
			// значения
			$value = SgetClearArray($propertyObject->GetValue());
			if(!$value[0]) $value = [''];
			// имя поля
			$inputName = $arParams["FIELD_PARAMS"]["INPUT_NAME"];
			if($inputName && $propAttributes["multiply"] == 'Y') $inputName .= '[]';
			// готовый массив
			$arResult =
				[
				"type"         => 'input_number',
				"value"        => $value,
				"multiply"     => $propAttributes["multiply"],
				"field_params" =>
					[
					"input_name"  => $inputName,
					"attr"        => $arParams["FIELD_PARAMS"]["ATTR"],
					"placeholder" => $arParams["FIELD_PARAMS"]["PLACEHOLDER"],
					"size"        => $arParams["FIELD_PARAMS"]["SIZE"]
					]
				];
			break;
		/* ------------------------------------------------------------------ */
		/* -------------------------- property date ------------------------- */
		/* ------------------------------------------------------------------ */
		case "date":
			// тип поля
			$fieldType = 'input_date';
			if($propAttributes["interval"] == 'Y') $fieldType = 'date_interval';
			// значения
			$value = SgetClearArray($propertyObject->GetValue());
			if(!$value[0]) $value = [''];
			// имя поля
			$inputName = $arParams["FIELD_PARAMS"]["INPUT_NAME"];
			if($inputName && ($propAttributes["multiply"] == 'Y' || $propAttributes["interval"] == 'Y')) $inputName .= '[]';
			// готовый массив
			$arResult =
				[
				"type"         => $fieldType,
				"value"        => $value,
				"multiply"     => $propAttributes["multiply"],
				"field_params" =>
					[
					"input_name" => $inputName,
					"date"       => $arParams["FIELD_PARAMS"]["DATE"],
					"time"       => $arParams["FIELD_PARAMS"]["TIME"],
					"start_date" => $arParams["FIELD_PARAMS"]["START_DATE"],
					"attr"       => $arParams["FIELD_PARAMS"]["ATTR"]
					]
				];
			break;
		/* ------------------------------------------------------------------ */
		/* -------------------------- property file ------------------------- */
		/* ------------------------------------------------------------------ */
		case "file":
			$arResult =
				[
				"type"         => 'input_file',
				"field_params" =>
					[
					"value"               => $propertyObject->GetValue(),
					"multiply"            => $propAttributes["multiply"],
					"input_name"          => $arParams["FIELD_PARAMS"]["INPUT_NAME"].'[new][]',
					"input_name_uploaded" => $arParams["FIELD_PARAMS"]["INPUT_NAME"].'[uploaded][]',
					"attr"                => $arParams["FIELD_PARAMS"]["ATTR"]
					]
				];
			break;
		/* ------------------------------------------------------------------ */
		/* -------------------------- property text ------------------------- */
		/* ------------------------------------------------------------------ */
		case "text":
			// значения
			$value = SgetClearArray($propertyObject->GetValue());
			if(!$value[0]) $value = [''];
			// имя поля
			$inputName = $arParams["FIELD_PARAMS"]["INPUT_NAME"];
			if($inputName && $propAttributes["multiply"] == 'Y') $inputName .= '[]';
			// готовый массив
			$arResult =
				[
				"type"         => 'input_textarea',
				"value"        => $value,
				"field_params" =>
					[
					"multiply"    => $inputName,
					"input_name"  => $inputName,
					"placeholder" => $arParams["FIELD_PARAMS"]["PLACEHOLDER"],
					"size"        => $arParams["FIELD_PARAMS"]["SIZE"],
					"attr"        => $arParams["FIELD_PARAMS"]["ATTR"]
					]
				];
			break;
		/* ------------------------------------------------------------------ */
		/* -------------------------- property list ------------------------- */
		/* ------------------------------------------------------------------ */
		case "list":
			/* ---------------------------------------- */
			/* ---------------- select ---------------- */
			/* ---------------------------------------- */
			if($propAttributes["multiply"] == 'N')
				{
				// список
				foreach($propAttributes["list"] as $listInfo)
					$list[$listInfo["code"]] = $listInfo["title"];
				// готовый массив
				$arResult =
					[
					"type"         => 'input_select',
					"field_params" =>
						[
						"value"       => $propertyObject->GetValue(),
						"list"        => $list,
						"input_name"  => $arParams["FIELD_PARAMS"]["INPUT_NAME"],
						"empty_value" => $arParams["FIELD_PARAMS"]["EMPTY_VALUE"],
						"width"       => $arParams["FIELD_PARAMS"]["WIDTH"],
						"attr"        => $arParams["FIELD_PARAMS"]["ATTR"]
						]
					];
				}
			/* ---------------------------------------- */
			/* --------------- checkbox --------------- */
			/* ---------------------------------------- */
			if($propAttributes["multiply"] == 'Y')
				{
				// список
				$value = $propertyObject->GetValue();
				foreach($propAttributes["list"] as $listInfo)
					{
					$listInfo = ["value" => $listInfo["code"], "title" => $listInfo["title"]];
					if(in_array($listInfo["value"], $value)) $listInfo["checked"] = 'Y';
					$list[] = $listInfo;
					}
				// имя поля
				$inputName = $arParams["FIELD_PARAMS"]["INPUT_NAME"];
				if($inputName) $inputName .= '[]';
				// готовый массив
				$arResult =
					[
					"type"         => 'input_checkboxes',
					"list"         => $list,
					"field_params" =>
						[
						"input_name" => $inputName,
						"attr"       => $arParams["FIELD_PARAMS"]["ATTR"]
						]
					];
				}
			break;
		/* ------------------------------------------------------------------ */
		/* ------------------------ property boolean ------------------------ */
		/* ------------------------------------------------------------------ */
		case "boolean":
			/* ---------------------------------------- */
			/* ---------------- select ---------------- */
			/* ---------------------------------------- */
			if($propAttributes["multiply"] == 'N')
				{
				// список
				foreach($propAttributes["list"] as $listInfo)
					$list[$listInfo["code"]] = $listInfo["title"];
				// готовый массив
				$arResult =
					[
					"type"         => 'input_select',
					"field_params" =>
						[
						"value"       => $propertyObject->GetValue(),
						"list"        => $list,
						"empty_value" => 'N',
						"width"       => '100',
						"input_name"  => $arParams["FIELD_PARAMS"]["INPUT_NAME"],
						"attr"        => $arParams["FIELD_PARAMS"]["ATTR"]
						]
					];
				}
			/* ---------------------------------------- */
			/* --------------- checkbox --------------- */
			/* ---------------------------------------- */
			if($propAttributes["multiply"] == 'Y')
				{
				// список
				$value = $propertyObject->GetValue();
				foreach($propAttributes["list"] as $listInfo)
					{
					$listInfo = ["value" => $listInfo["code"], "title" => $listInfo["title"]];
					if(in_array($listInfo["value"], $value)) $listInfo["checked"] = 'Y';
					$list[] = $listInfo;
					}
				// имя поля
				$inputName = $arParams["FIELD_PARAMS"]["INPUT_NAME"];
				if($inputName) $inputName .= '[]';
				// готовый массив
				$arResult =
					[
					"type"         => 'input_checkboxes',
					"list"         => $list,
					"field_params" =>
						[
						"input_name" => $inputName,
						"attr"       => $arParams["FIELD_PARAMS"]["ATTR"]
						]
					];
				}
			break;
		/* ------------------------------------------------------------------ */
		/* ---------------------- property list_element --------------------- */
		/* ------------------------------------------------------------------ */
		case "list_element":
			// фильтр
			$filter = $arParams["FIELD_PARAMS"]["FILTER"];
			if(count($propAttributes["available_value"])) $filter["ID"] = SgetClearArray($propAttributes["available_value"]);
			// готовый массив
			$arResult =
				[
				"type"         => 'element_selector',
				"value"        => $propertyObject->GetValue(),
				"field_params" =>
					[
					"table"      => $propAttributes["table"],
					"multiply"   => $propAttributes["multiply"],
					"input_name" => $arParams["FIELD_PARAMS"]["INPUT_NAME"],
					"props"      => $arParams["FIELD_PARAMS"]["PROPS"],
					"filter"     => $filter,
					"attr"       => $arParams["FIELD_PARAMS"]["ATTR"]
					]
				];
			break;
		/* ------------------------------------------------------------------ */
		/* -------------------------- property user ------------------------- */
		/* ------------------------------------------------------------------ */
		case "user":
			$arResult =
				[
				"type"         => 'user_selector',
				"value"        =>
					[
					"users"       => $propertyObject->GetValue("users"),
					"departments" => $propertyObject->GetValue("departments")
					],
				"field_params" =>
					[
					"multiply"    => $propAttributes["multiply"],
					"input_name"  => $arParams["FIELD_PARAMS"]["INPUT_NAME"],
					"users"       => $propAttributes["users"],
					"departments" => $propAttributes["departments"],
					"start_roots" => $arParams["FIELD_PARAMS"]["START_ROOTS"],
					"attr"        => $arParams["FIELD_PARAMS"]["ATTR"]
					]
				];
			break;
		/* ------------------------------------------------------------------ */
		/* ------------------------- property phone ------------------------- */
		/* ------------------------------------------------------------------ */
		case "phone":
			// значения
			$value = SgetClearArray($propertyObject->GetValue());
			if(!$value[0]) $value = [''];
			// имя поля
			$inputNamesArray =
				[
				"input_name_number" => $arParams["FIELD_PARAMS"]["INPUT_NAME"].'[number]',
				"input_name_type"   => $arParams["FIELD_PARAMS"]["INPUT_NAME"].'[type]'
				];
			if($propAttributes["multiply"] == 'Y')
				foreach($inputNamesArray as $index => $inputName)
					if($inputName)
						$inputNamesArray[$index] .= '[]';
			// готовый массив
			$arResult =
				[
				"type"         => 'input_phone',
				"value"        => $value,
				"multiply"     => $propAttributes["multiply"],
				"field_params" =>
					[
					"input_name_number" => $inputNamesArray["input_name_number"],
					"input_name_type"   => $inputNamesArray["input_name_type"],
					"list"              => $propAttributes["phone_type"],
					"attr"              => $arParams["FIELD_PARAMS"]["ATTR"],
					]
				];
			break;
		/* ------------------------------------------------------------------ */
		/* ------------------------ property section ------------------------ */
		/* ------------------------------------------------------------------ */
		case "section":
			/* ---------------------------------------- */
			/* ---------------- select ---------------- */
			/* ---------------------------------------- */
			if($propAttributes["multiply"] == 'N')
				$arResult =
					[
					"type"         => 'input_select',
					"field_params" =>
						[
						"value"       => $propertyObject->GetValue(),
						"list"        => SGetSectionsList($propAttributes["table"], $propAttributes["start_sections"]),
						"input_name"  => $arParams["FIELD_PARAMS"]["INPUT_NAME"],
						"empty_value" => $arParams["FIELD_PARAMS"]["EMPTY_VALUE"],
						"width"       => $arParams["FIELD_PARAMS"]["WIDTH"],
						"attr"        => $arParams["FIELD_PARAMS"]["ATTR"]
						]
					];
			break;
		}
/* ============================================================================================= */
/* =========================================== ВЫВОД =========================================== */
/* ============================================================================================= */
$this->IncludeComponentTemplate();
?>