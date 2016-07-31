<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_framework"))                  return ShowError("module silta_framework required!");
$this->IncludeComponentTemplate();
?>