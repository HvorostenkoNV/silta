<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_diy_module"))                 return ShowError("modules required: silta_diy_module");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

FIELD_TYPE      - тип поля
PROPERTY_OBJECT - объект свойства
FIELD_PARAMS    - настройки поля

INPUT_NAME      - базовое имя свойстваств фильтра
WORK_TABLE      - имя рабочей таблицы (для постройки ссылок)
LINKS           - ссылки              (для постройки ссылок)
*/
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$DiyModule      = SDiyModule::GetInstance();                                            // объект класса DIY модуль
$propertyObject = $arParams["PROPERTY_OBJECT"];                                         // объект свойства элемента
$CompanyTables  = SCompanyTables::GetInstance();                                        // объект класса "таблицы компании"
if(!is_subclass_of($propertyObject, 'SDBElementProperty')) return;
foreach($arParams["LINKS"] as $menu => $link) $linksArray[$menu] = SgetClearUrl($link); // ссылки на страницы с другими компонентами
// ссылкы на элементы другой таблицы
if($propertyObject->GetType() == 'list_element' && $arParams["FIELD_TYPE"] == 'read')
	foreach($DiyModule->GetTablesInfo() as $table => $tableInfo)
		if($tableInfo["id"] == $propertyObject->GetAttributes()["table"] && $linksArray[$table])
			{
			$tableObject = $DiyModule->GetTable($table);
			if($tableObject)
				foreach(SgetClearArray($propertyObject->GetValue()) as $tableElementId)
					if($tableObject->GetElement($tableElementId))
						$componentLinks[] =
							[
							"link"           => $linksArray[$table],
							"element_object" => $tableObject->GetElement($tableElementId)
							];
			break;
			}
// ссылка на элемент этой таблицы
if($propertyObject->GetName() == 'name' && $arParams["FIELD_TYPE"] == 'read' && $linksArray[$arParams["WORK_TABLE"]])
	$componentLinks[] =
		[
		"link"           => $linksArray[$arParams["WORK_TABLE"]],
		"element_object" => $propertyObject->GetElementObject()
		];
// таблица изменений элемента
if($propertyObject->GetTableObject()->GetIblockId() == $DiyModule->GetTablesInfo()["element_history"]["id"]  && $propertyObject->GetName() == 'changing')
	$historyElementObject = $propertyObject->GetElementObject();
// обычные свойства
if(!$componentLinks && !$historyElementObject)
	{
	if($propertyObject->GetName() == 'user' && $DiyModule->GetDiyDepartmentObject())                         $propFeatures["start_roots"]    = [$DiyModule->GetDiyDepartmentObject()->GetId()];
	if($propertyObject->GetAttributes()["table"] == $CompanyTables->GetTablesInfo()["ukraine_cities"]["id"]) $propFeatures["ukraine_cities"] = true;
	if($arParams["INPUT_NAME"])                                                                              $propFeatures["input_name"]     = $arParams["INPUT_NAME"].'['.$propertyObject->GetName().']';
	if($propertyObject->GetAttributes()["table"] == $CompanyTables->GetTablesInfo()["nomenclature"]["id"])
		{
		$propFeatures["nomenclature"] = true;
		$propFeatures["nomenclature_filter"] = $CompanyTables->GetTable("trade_marks")->GetQuery(["name" => 'asc'], ["stream" => 'element']);
		}
	}
/* -------------------------------------------------------------------- */
/* ----------------------- параметры для шаблона ---------------------- */
/* -------------------------------------------------------------------- */
// ссылкы на элементы
if($componentLinks)
	$arResult =
		[
		"building_type" => 'component_links',
		"links"         => $componentLinks
		];
// таблица изменений элемента
elseif($historyElementObject)
	$arResult =
		[
		"building_type"          => 'history_changings',
		"history_element_object" => $historyElementObject
		];
// таблица изменений элемента
else
	$arResult =
		[
		"building_type"   => 'property_field',
		"property_object" => $propertyObject,
		"field_type"      => $arParams["FIELD_TYPE"],
		"field_params"    => $arParams["FIELD_PARAMS"],
		"features"        => $propFeatures,
		];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>