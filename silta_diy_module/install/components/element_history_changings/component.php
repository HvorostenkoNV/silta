<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_diy_module"))                 return ShowError("modules required: silta_diy_module");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

ELEMENT_OBJECT - объект элемента таблицы истории
*/
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$DiyModule            = SDiyModule::GetInstance();   // объект класса DIY модуль
$historyElementObject = $arParams["ELEMENT_OBJECT"]; // объект элемента таблицы истории
if(!$historyElementObject || $historyElementObject->GetTableObject()->GetIblockId() != $DiyModule->GetTablesInfo()["element_history"]["id"]) return;
// тип операции
$operationType = $historyElementObject->GetProperty("operation_type")->GetValue();
if(!in_array($operationType, ["change", "comment"])) return;
// объект таблицы рабочего элемента
$changedElementId = $historyElementObject->GetProperty("element")->GetValue();
if(!$changedElementId) return;
$elementList = CIBlockElement::GetList([], ["ID" => $changedElementId], false, false, ["ID", "IBLOCK_ID"]);
while($element = $elementList->GetNext())
	foreach($DiyModule->GetTablesInfo() as $table => $infoArray)
		if($infoArray["id"] == $element["IBLOCK_ID"])
			{
			$changedElementTableObject = $DiyModule->GetTable($table);
			break;
			}
if(!$changedElementTableObject) return;
/* -------------------------------------------------------------------- */
/* ------------------------- таблица комментов ------------------------ */
/* -------------------------------------------------------------------- */
if($operationType == 'comment')
	$arResult =
		[
		"building_type" => 'comment_table',
		"props"         =>
			[
			$historyElementObject->GetProperty("text"),
			$historyElementObject->GetProperty("files")
			]
		];
/* -------------------------------------------------------------------- */
/* ------------------------- таблица изменений ------------------------ */
/* -------------------------------------------------------------------- */
if($operationType == 'change')
	{
	$changedElementObjectOld = $changedElementTableObject->GetElement("new");
	$changedElementObjectNew = $changedElementTableObject->GetElement("new");
	if($changedElementObjectOld && $changedElementObjectNew)
		foreach($historyElementObject->GetProperty("changing")->GetValue() as $property => $infoArray)
			{
			$propertyObjectOld = $changedElementObjectOld->GetProperty($property);
			$propertyObjectNew = $changedElementObjectNew->GetProperty($property);
			if(!$propertyObjectOld || !$propertyObjectNew) continue;

			$propertyObjectOld->SetValue($infoArray["old_value"]);
			$propertyObjectNew->SetValue($infoArray["new_value"]);
			$propsInfo[] =
				[
				"title"        => $propertyObjectOld->GetAttributes()["title"],
				"old_property" => $propertyObjectOld,
				"new_property" => $propertyObjectNew
				];
			}
	$arResult =
		[
		"building_type" => 'changings_table',
		"props_info"    => $propsInfo
		];
	}
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>