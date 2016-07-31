<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_diy_module"))                 return ShowError("modules required: silta_diy_module");
/* ------------------------------------------------------------------ */
/* -------------- формирование стартового массива меню -------------- */
/* ------------------------------------------------------------------ */
$menuList = SDiyModule::GetInstance()->GetMenuList();
foreach($menuList as $menu => $infoArray)
	{
	$menuList[$menu]["link"] = SgetClearUrl($arParams[ToUpper($menu).'_LINK']);
	if(!$menuList[$menu]["link"]) unset($menuList[$menu]);
	}
/* ------------------------------------------------------------------ */
/* --------------- формирование конечного массива меню -------------- */
/* ------------------------------------------------------------------ */
foreach($menuList as $index => $infoArray)
	{
	// основное меню
	if(!$infoArray["parent"])
		{
		$menuInfo = array_merge($infoArray, ["type" => $index]);
		if($_SERVER["SCRIPT_NAME"] == $menuInfo["link"]) $menuInfo["checked"] = true;
		$arResult["menu"]["main_list"][] = $menuInfo;
		}
	// подменю
	else
		{
		if(!$arResult["menu"]["sublists"][$infoArray["parent"]])
			$arResult["menu"]["sublists"][$infoArray["parent"]][] = $menuList[$infoArray["parent"]];
		$arResult["menu"]["sublists"][$infoArray["parent"]][] = $infoArray;
		}
	}
/* ------------------------------------------------------------------ */
/* ----------------------------- вывод ------------------------------ */
/* ------------------------------------------------------------------ */
$this->IncludeComponentTemplate();
?>