<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_framework"))                  return ShowError("module silta_framework required!");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

TABLE      - ИД инфоблока
ELEMENT_ID - ИД элемента
PROPS      - массив свойств
*/
/* -------------------------------------------------------------------- */
/* ----------------------- параметры для шаблона ---------------------- */
/* -------------------------------------------------------------------- */
$arParams["PROPS"] = SgetClearArray($arParams["PROPS"]);
if(!$arParams["PROPS"][0]) $arParams["PROPS"] = ["name"];
/* -------------------------------------------------------------------- */
/* --------------------- IBlock таблица + элемент --------------------- */
/* -------------------------------------------------------------------- */
if($arParams["TABLE"])
	$tableObject = new SIBlockTable(["id" => $arParams["TABLE"]]);
if($tableObject)
	foreach($arParams["PROPS"] as $property)
		$tableObject->SetProperty($property);
if($tableObject && $arParams["ELEMENT_ID"])
	$elementObject = $tableObject->GetElement($arParams["ELEMENT_ID"]);
/* -------------------------------------------------------------------- */
/* ------------------- проход по заданным элементам ------------------- */
/* -------------------------------------------------------------------- */
if($elementObject)
	foreach($elementObject->GetPropertyList() as $propertyObject)
		{
		$value = [];
		//строки
		if(in_array($propertyObject->GetType(), ["boolean", "list"]))          $value = SgetClearArray($propertyObject->GetValue("title"));
		if(in_array($propertyObject->GetType(), ["date", "number", "string"])) $value = SgetClearArray($propertyObject->GetValue());
		// ссылка на элемент
		if($propertyObject->GetType() == 'list_element')
			{
			$propTableObject = new SIBlockTable(["id" => $propertyObject->GetAttributes()["table"]]);
			if($propTableObject)
				{
				$propTableObject->SetProperty("name");
				foreach(SgetClearArray($propertyObject->GetValue()) as $propElementId)
					if($propTableObject->GetElement($propElementId))
						$value[] = $propTableObject->GetElement($propElementId)->GetProperty("name")->GetValue();
				}
			}
		// юзер
		if($propertyObject->GetType() == 'user')
			{
			$departments = SgetClearArray($propertyObject->GetValue("departments"));
			$users       = SgetClearArray($propertyObject->GetValue("users"));
			if($departments[0])
				{
				$sectionList = CIBlockSection::GetList([], ["ID" => $departments], false, ["ID", "NAME"]);
				while($section = $sectionList->GetNext()) $value[] = $section["NAME"];
				}
			if($users[0])
				{
				$usersList = CUser::GetList($by = "ID", $order = "asc" , ["ID" => implode('|', $users)], ["FIELDS" => ["ID", "NAME", "LAST_NAME"]]);
				while($user = $usersList->GetNext()) $value[] = $user["NAME"].' '.$user["LAST_NAME"];
				}
			}
		// часть титула
		if($value[0]) $arResult["title"][] = implode('/', $value);
		}
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
if($arResult["title"][0]) $arResult["title"] = implode(' - ', $arResult["title"]);
$this->IncludeComponentTemplate();
?>