<?
IncludeModuleLangFile(__FILE__);
// классы
$class_folder = 'classes/general/';
CModule::AddAutoloadClasses
	(
	"silta_framework",
		[
		"SFramework"         => $class_folder.'silta_framework.php',

		"SDBTable"           => $class_folder.'db_working/db_table.php',
		"SDBProperty"        => $class_folder.'db_working/db_property.php',
		"SDBElement"         => $class_folder.'db_working/db_element.php',
		"SDBElementProperty" => $class_folder.'db_working/db_element_property.php',

		"SIBlockTable"           => $class_folder.'db_working/iblock/iblock_table.php',
		"SIBlockProperty"        => $class_folder.'db_working/iblock/iblock_property.php',
		"SIBlockElement"         => $class_folder.'db_working/iblock/iblock_element.php',
		"SIBlockElementProperty" => $class_folder.'db_working/iblock/iblock_element_property.php',

		"SIBlockPropertyBoolean"     => $class_folder.'db_working/iblock/property/iblock_property_boolean.php',
		"SIBlockPropertyDate"        => $class_folder.'db_working/iblock/property/iblock_property_date.php',
		"SIBlockPropertyFile"        => $class_folder.'db_working/iblock/property/iblock_property_file.php',
		"SIBlockPropertyList"        => $class_folder.'db_working/iblock/property/iblock_property_list.php',
		"SIBlockPropertyListElement" => $class_folder.'db_working/iblock/property/iblock_property_list_element.php',
		"SIBlockPropertyNumber"      => $class_folder.'db_working/iblock/property/iblock_property_number.php',
		"SIBlockPropertyPhone"       => $class_folder.'db_working/iblock/property/iblock_property_phone.php',
		"SIBlockPropertyString"      => $class_folder.'db_working/iblock/property/iblock_property_string.php',
		"SIBlockPropertyText"        => $class_folder.'db_working/iblock/property/iblock_property_text.php',
		"SIBlockPropertySection"     => $class_folder.'db_working/iblock/property/iblock_property_section.php',
		"SIBlockPropertyUser"        => $class_folder.'db_working/iblock/property/iblock_property_user.php',

		"SIBlockElementPropertyBoolean"     => $class_folder.'db_working/iblock/element_property/iblock_element_property_boolean.php',
		"SIBlockElementPropertyDate"        => $class_folder.'db_working/iblock/element_property/iblock_element_property_date.php',
		"SIBlockElementPropertyFile"        => $class_folder.'db_working/iblock/element_property/iblock_element_property_file.php',
		"SIBlockElementPropertyList"        => $class_folder.'db_working/iblock/element_property/iblock_element_property_list.php',
		"SIBlockElementPropertyListElement" => $class_folder.'db_working/iblock/element_property/iblock_element_property_list_element.php',
		"SIBlockElementPropertyNumber"      => $class_folder.'db_working/iblock/element_property/iblock_element_property_number.php',
		"SIBlockElementPropertyPhone"       => $class_folder.'db_working/iblock/element_property/iblock_element_property_phone.php',
		"SIBlockElementPropertyString"      => $class_folder.'db_working/iblock/element_property/iblock_element_property_string.php',
		"SIBlockElementPropertyText"        => $class_folder.'db_working/iblock/element_property/iblock_element_property_text.php',
		"SIBlockElementPropertySection"     => $class_folder.'db_working/iblock/element_property/iblock_element_property_section.php',
		"SIBlockElementPropertyUser"        => $class_folder.'db_working/iblock/element_property/iblock_element_property_user.php',

		"SCompanyDepartment" => $class_folder.'company_departmants.php',

		"SCompany"               => $class_folder.'company/company.php',
		"ScompanyTableStructure" => $class_folder.'company/structure.php',

		"SCompanyTables"                 => $class_folder.'company_tables/company_tables.php',
		"ScompanyTableUkraineCities"     => $class_folder.'company_tables/ukraine_cities.php',
		"ScompanyTableContragents"       => $class_folder.'company_tables/contragents.php',
		"ScompanyTableTradeMarks"        => $class_folder.'company_tables/trade_marks.php',
		"ScompanyTableNomenclature"      => $class_folder.'company_tables/nomenclature.php',
		"ScompanyTableFixedAssets"       => $class_folder.'company_tables/fixed_assets.php',
		"ScompanyTableFixedAssetsGroups" => $class_folder.'company_tables/fixed_assets_groups.php',
		"ScompanyTableAbsence"           => $class_folder.'company_tables/absence.php'
		]
	);
// функции
include 'functions.php';
// трейты
include 'traits.php';
// JS
CJSCore::Init(['jquery']);
$GLOBALS["APPLICATION"]->AddHeadScript('/bitrix/js/silta_framework/main.js');
$GLOBALS["APPLICATION"]->AddHeadScript('/bitrix/js/silta_framework/form_elements.js');
?>