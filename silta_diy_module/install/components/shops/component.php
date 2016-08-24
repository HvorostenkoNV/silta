<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_diy_module"))                 return ShowError("modules required: silta_diy_module");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

TABLE_PROPS                - свойства таблицы
FILTER_PROPS               - свойства фильтра
ELEMENTS_COUNT             - кол-во элементов на странице

FORM_PROPS                 - свойства формы
FORM_PROPS_BUFFER          - пространнство после свойст формы

CONTACTS_FORM_PROPS        - свойства формы
CONTACTS_FORM_PROPS_BUFFER - пространнство после свойст формы
*/
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$DiyModule   = SDiyModule::GetInstance();     // объект класса DIY модуль
$tableObject = $DiyModule->GetTable("shops"); // объект таблицы "магазины"
if(!$tableObject) return ShowError(GetMessage("SDM_SHOPS_TABLE_NOT_FOUND"));
/* -------------------------------------------------------------------- */
/* ------------------------------ ссылки ------------------------------ */
/* -------------------------------------------------------------------- */
foreach($DiyModule->GetMenuList() as $menu => $infoArray)
	{
	$index = ToUpper($menu).'_LINK';
	$menuLinks[$index]     = $arParams[$index];
	$componentLinks[$menu] = $arParams[$index];
	}
/* -------------------------------------------------------------------- */
/* ------------------------------ таблица ----------------------------- */
/* -------------------------------------------------------------------- */
if(!$_GET[SDM_URL_ELEMENT_ID_VAR])
	$arResult =
		[
		"page_type"        => 'table_list',                                                                                              // тип страницы - ТАБЛИЦА
		"filter_props"     => $arParams["FILTER_PROPS"],                                                                                 // свойства фильтра
		"table_props"      => $arParams["TABLE_PROPS"],                                                                                  // свойства таблицы
		"elements_count"   => $arParams["ELEMENTS_COUNT"],                                                                               // кол-во элементов на странице
		"menu_links"       => $menuLinks,                                                                                                // ссылки для меню
		"links"            => $componentLinks,                                                                                           // ссылки (для построения ссылкок между компонентами)
		"add_element_link" => $APPLICATION->GetCurPage().SgetUrlVarsString([SDM_URL_ELEMENT_ID_VAR => 'new'], [SDM_URL_ELEMENT_TAB_VAR]) // ссылка "создать элемент"
		];
/* -------------------------------------------------------------------- */
/* ------------------------------ элемент ----------------------------- */
/* -------------------------------------------------------------------- */
if($_GET[SDM_URL_ELEMENT_ID_VAR])
	{
	$elementObject = $tableObject->GetElement($_GET[SDM_URL_ELEMENT_ID_VAR]);
	if(!$elementObject)
		{
		if($_GET[SDM_URL_ELEMENT_ID_VAR] == 'new') return ShowError(GetMessage("SDM_SHOPS_CREATE_ELEMENT_NO_ACCESS"));
		else                                       return ShowError(GetMessage("SDM_SHOPS_ELEMENT_NOT_FOUND"));
		}
	$listLink    = $APPLICATION->GetCurPage().SgetUrlVarsString([], [SDM_URL_ELEMENT_ID_VAR, SDM_URL_ELEMENT_TAB_VAR]); // ссылка на страницу списка
	$elementLink = $APPLICATION->GetCurPage().SgetUrlVarsString([SDM_URL_ELEMENT_ID_VAR => '#ELEMENT_ID#']);            // ссылка на страницу элемента
	/* ------------------------------------------ */
	/* --------------- табы формы --------------- */
	/* ------------------------------------------ */
	if($elementObject->GetElementId() != 'new')
		{
		$formTabs =
			[
			"main_info" => GetMessage("SDM_SHOPS_TABS_MAIN_INFO"),
			"history"   => GetMessage("SDM_SHOPS_TABS_HISTORY"),
			"contacts"  => GetMessage("SDM_SHOPS_TABS_CONTACTS"),
			"sales"     => GetMessage("SDM_SHOPS_TABS_SALES")
			];
		foreach(["element_history" => 'history', "shop_contacts" => 'contacts', "sales" => 'sales'] as $table => $tab)
			if(!$DiyModule->GetTable($table))
				unset($formTabs[$tab]);
		if(count($formTabs) <= 1) unset($formTabs);
		}
	if(!$formTabs[$_GET[SDM_URL_ELEMENT_TAB_VAR]]) $_GET[SDM_URL_ELEMENT_TAB_VAR] = 'main_info';
	/* ------------------------------------------ */
	/* ------------- готовый массив ------------- */
	/* ------------------------------------------ */
	$arResult =
		[
		"page_type"         => 'element_form',                 // тип страницы - форма элемента
		"element_object"    => $elementObject,                 // оюъект элемента
		"current_form_tab"  => $_GET[SDM_URL_ELEMENT_TAB_VAR], // текущая таба
		"form_tabs"         => $formTabs,                      // массив табов формы
		"menu_links"        => $menuLinks,                     // ссылки для меню
		"list_link"         => $listLink                       // ссылка "домой" - к списку элементов
		];
	// вкладка основной информации
	if($_GET[SDM_URL_ELEMENT_TAB_VAR] == 'main_info')
		{
		$arResult["form_props"]        = $arParams["FORM_PROPS"];        // свойства формы
		$arResult["form_props_buffer"] = $arParams["FORM_PROPS_BUFFER"]; // буферы между строками свойств
		$arResult["links"]             = $componentLinks;                // ссылки (для построения ссылкок между компонентами)
		$arResult["save_redirect"]     = $elementLink;                   // URL-редирект после сохранения элемента
		$arResult["delete_redirect"]   = $listLink;                      // URL-редирект после удаления элемента
		}
	// вкладка истории изменений
	if($_GET[SDM_URL_ELEMENT_TAB_VAR] == 'history')
		$DiyModule->GetTable("element_history")->SetQueryOptions(["filter" => ["element" => $elementObject->GetElementId()]]); // SetQueryOptions not used
	// вкладка контактов
	if($_GET[SDM_URL_ELEMENT_TAB_VAR] == 'contacts')
		{
		$arResult["form_props"]        = $arParams["CONTACTS_FORM_PROPS"];        // свойства формы
		$arResult["form_props_buffer"] = $arParams["CONTACTS_FORM_PROPS_BUFFER"]; // буферы между строками свойств
		$arResult["links"]             = $componentLinks;                         // ссылки (для построения ссылкок между компонентами)
		}
	// вкладка истории изменений
	if($_GET[SDM_URL_ELEMENT_TAB_VAR] == 'sales')
		{
		$DiyModule->GetTable("sales")->SetQueryOptions(["filter" => ["diy_shop" => $elementObject->GetElementId()]]); // SetQueryOptions not used
		$arResult["sales_filter_props"] = $arParams["SALES_FILTER_PROPS"];                                                                          // свойства таблицы продаж
		$arResult["sales_table_props"]  = $arParams["SALES_TABLE_PROPS"];                                                                           // свойства формы продаж
		$arResult["add_sale_link"]      = $componentLinks["sales"].SgetUrlVarsString([SDM_URL_ELEMENT_ID_VAR => 'new'], [SDM_URL_ELEMENT_TAB_VAR]); // ссылка "создать продажу"
		$arResult["links"]              = $componentLinks;                                                                                          // ссылки (для построения ссылкок между компонентами)
		}
	}
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>