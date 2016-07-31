<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_framework"))                  return ShowError("module silta_framework required!");

if($arParams["DEPARTMENT_ID"])
	{
	$section_list = CIBlockSection::GetList([], ["ID" => $arParams["DEPARTMENT_ID"]], false, ["ID", "NAME"]);
	while($section = $section_list->GetNext())
		$arResult =
			[
			"title" => $section["NAME"],
			"id"    => $section["ID"]
			];
	}

$this->IncludeComponentTemplate(); 
?>