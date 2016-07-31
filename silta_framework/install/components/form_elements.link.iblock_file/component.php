<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_framework"))                  return ShowError("module silta_framework required!");

if($arParams["FILE_ID"])
	{
	$file_info = CFile::GetFileArray($arParams["FILE_ID"]);
	$arResult = 
		[
		"name" => $file_info["ORIGINAL_NAME"],
		"link" => $file_info["SRC"]
		];
	}

$this->IncludeComponentTemplate(); 
?>