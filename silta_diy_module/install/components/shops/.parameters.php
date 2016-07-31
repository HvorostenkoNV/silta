<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_diy_module"))                 return;
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$DiyModule = SDiyModule::GetInstance();
if(!$DiyModule->GetTable("shops")) return;
// свойства магазинов
foreach($DiyModule->GetTable("shops")->GetPropertyList() as $property => $propertyObject)
	$shopsPropsList[$property] = $propertyObject->GetAttributes()["title"];
// свойства контатков
if($DiyModule->GetTable("shop_contacts"))
	foreach($DiyModule->GetTable("shop_contacts")->GetPropertyList() as $property => $propertyObject)
		$contactsPropsList[$property] = $propertyObject->GetAttributes()["title"];
// свойства продаж
if($DiyModule->GetTable("sales"))
	foreach($DiyModule->GetTable("sales")->GetPropertyList() as $property => $propertyObject)
		$salesPropsList[$property] = $propertyObject->GetAttributes()["title"];
/* -------------------------------------------------------------------- */
/* ----------------------------- разделы ------------------------------ */
/* -------------------------------------------------------------------- */
$arComponentParameters["GROUPS"] =
	[
	"MENU_LINKS"    => ["NAME" => GetMessage("SDM_SHOPS_GROUPS_LINKS")],
	"SHOPS_TABLE"   => ["NAME" => GetMessage("SDM_SHOPS_GROUPS_SHOPS_TABLE")],
	"SHOPS_FORM"    => ["NAME" => GetMessage("SDM_SHOPS_GROUPS_SHOPS_FORM")],
	"CONTACTS_FORM" => ["NAME" => GetMessage("SDM_SHOPS_GROUPS_CONTACTS_FORM")],
	"SALES_TABLE"   => ["NAME" => GetMessage("SDM_SHOPS_GROUPS_SALES_TABLE")]
	];
/* -------------------------------------------------------------------- */
/* ---------------------------- параметры ----------------------------- */
/* -------------------------------------------------------------------- */
// ссылки на компоненты
foreach($DiyModule->GetMenuList() as $menu => $infoArray)
	$arComponentParameters["PARAMETERS"][ToUpper($menu).'_LINK'] =
		[
		"PARENT" => 'MENU_LINKS',
		"NAME"   => str_replace('#COMPONENT_TITLE#', $infoArray["title"], GetMessage("SDM_SHOPS_MENU_LINK_TEMPLATE")),
		"TYPE"   => 'STRING'
		];
// табличное представление магазинов
foreach(["TABLE_PROPS" => 'SDM_SHOPS_TABLE_PROPS', "FILTER_PROPS" => 'SDM_SHOPS_FILTER_PROPS'] as $variable => $title)
	$arComponentParameters["PARAMETERS"][$variable] =
		[
		"PARENT"   => 'SHOPS_TABLE',
		"NAME"     => GetMessage($title),
		"TYPE"     => 'LIST',
		"SIZE"     => 5,
		"MULTIPLE" => 'Y',
		"VALUES"   => $shopsPropsList
		];
$arComponentParameters["PARAMETERS"]["ELEMENTS_COUNT"] =
	[
	"PARENT"   => 'SHOPS_TABLE',
	"NAME"     => GetMessage("SDM_SHOPS_ELEMENTS_COUNT"),
	"TYPE"     => 'LIST',
	"DEFAULT"  => 25,
	"VALUES"   =>
		[
		25  => 25,
		50  => 50,
		100 => 100,
		500 => 500
		]
	];
// форма магазинов
foreach(["FORM_PROPS" => 'SDM_SHOPS_FORM_PROPS', "FORM_PROPS_BUFFER" => 'SDM_SHOPS_FORM_PROPS_BUFFER'] as $variable => $title)
	$arComponentParameters["PARAMETERS"][$variable] =
		[
		"PARENT"   => 'SHOPS_FORM',
		"NAME"     => GetMessage($title),
		"TYPE"     => 'LIST',
		"SIZE"     => 5,
		"MULTIPLE" => 'Y',
		"VALUES"   => $shopsPropsList
		];
// форма контактов
if($DiyModule->GetTable("shop_contacts"))
	foreach(["CONTACTS_FORM_PROPS" => 'SDM_SHOPS_FORM_PROPS', "CONTACTS_FORM_PROPS_BUFFER" => 'SDM_SHOPS_FORM_PROPS_BUFFER'] as $variable => $title)
		$arComponentParameters["PARAMETERS"][$variable] =
			[
			"PARENT"   => 'CONTACTS_FORM',
			"NAME"     => GetMessage($title),
			"TYPE"     => 'LIST',
			"SIZE"     => 5,
			"MULTIPLE" => 'Y',
			"VALUES"   => $contactsPropsList
			];
// таблица продаж
if($DiyModule->GetTable("shop_contacts"))
	foreach(["SALES_TABLE_PROPS" => 'SDM_SHOPS_TABLE_PROPS', "SALES_FILTER_PROPS" => 'SDM_SHOPS_FILTER_PROPS'] as $variable => $title)
		$arComponentParameters["PARAMETERS"][$variable] =
			[
			"PARENT"   => 'SALES_TABLE',
			"NAME"     => GetMessage($title),
			"TYPE"     => 'LIST',
			"SIZE"     => 5,
			"MULTIPLE" => 'Y',
			"VALUES"   => $salesPropsList
			];
?>