<?
if(!CModule::IncludeModule("silta_framework")) return ShowError('silta_framework not instaled');
IncludeModuleLangFile(__FILE__);

define("SDM_URL_ELEMENT_ID_VAR",  'sdm_element_id');
define("SDM_URL_ELEMENT_TAB_VAR", 'sdm_element_tab');

$class_folder = 'classes/general/';
CModule::AddAutoloadClasses
	(
	"silta_diy_module",
		[
		"SDiyModule" => $class_folder.'diy_module.php',

		"SDiyModuleTableShops"        => $class_folder.'diy_tables/shops.php',
		"SDiyModuleTableShopContacts" => $class_folder.'diy_tables/shop_contacts.php',
		"SDiyModuleTableSales"        => $class_folder.'diy_tables/sales.php',
		"SDiyModuleTableHistory"      => $class_folder.'diy_tables/element_history.php',

		"SDiyModuleElement"             => $class_folder.'diy_element.php',
		"SDiyModuleElementShops"        => $class_folder.'diy_element/shops.php',
		"SDiyModuleElementShopContacts" => $class_folder.'diy_element/shop_contacts.php',
		"SDiyModuleElementSales"        => $class_folder.'diy_element/sales.php',

		"SDMPropertyDiyHistory"        => $class_folder.'property_classes/diy_element_change_history.php',
		"SDMPropertyElementDiyHistory" => $class_folder.'element_property_classes/diy_element_change_history.php'
		]
	);
?>