<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_diy_module"))                 return;
// группы
$arComponentParameters["GROUPS"] =
	[
	"MENU_LINKS" => ["NAME" => GetMessage("SDM_MM_GROUPS_LINKS")]
	];
// ссылки на другие компоненты
foreach(SDiyModule::GetInstance()->GetMenuList() as $menu => $infoArray)
	$arComponentParameters["PARAMETERS"][ToUpper($menu).'_LINK'] =
		[
		"PARENT" => 'MENU_LINKS',
		"NAME"   => str_replace('#COMPONENT_TITLE#', $infoArray["title"], GetMessage("SDM_MM_LINK_TITLE_TEMPLATE")),
		"TYPE"   => 'STRING'
		];
?>