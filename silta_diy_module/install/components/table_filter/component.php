<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_diy_module"))                 return ShowError("modules required: silta_diy_module");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

WORK_TABLE   - рабочая таблица
FILTER_PROPS - свойтва фильтра
*/
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$tableObject = SDiyModule::GetInstance()->GetTable($arParams["WORK_TABLE"]); // объект таблицы
if($tableObject) $elementObject = $tableObject->GetFilterElement();          // объект элемента
// имена
$filterVarName   = 'sdm_filter_'.$arParams["WORK_TABLE"];                    // имя переменной фильтра
$formInputesName = 'filter_props';                                           // имя префикса полей филтра
$inputNameSumit  = 'silta-diy-module-table-filter-apply';                    // имя кнопки SUBMIT
$inputNameCancel = 'silta-diy-module-table-filter-cancel';                   // имя кнопки CANCEL
/* -------------------------------------------------------------------- */
/* ------------------------- свойства фильтра ------------------------- */
/* -------------------------------------------------------------------- */
$arParams["FILTER_PROPS"] = SgetClearArray($arParams["FILTER_PROPS"]);
if(!$arParams["FILTER_PROPS"][0]) return;

foreach($elementObject->GetPropertyList() as $property => $propertyObject)
	if(!in_array($property, $arParams["FILTER_PROPS"]))
		$elementObject->UnsetProperty($property);
/* -------------------------------------------------------------------- */
/* ---------------------------- обработчик ---------------------------- */
/* -------------------------------------------------------------------- */
// применить филтр
if(isset($_POST[$inputNameSumit]))
	{
	unset($_SESSION[$filterVarName][$USER->GetId()]);
	foreach($_POST[$formInputesName] as $property => $value)
		if($elementObject->GetProperty($property))
			{
			$propertyValue = $elementObject->GetProperty($property)->SetValue($value, 'form')->GetSavingArray();
			if($propertyValue[0]) $_SESSION[$filterVarName][$USER->GetId()][$property] = $propertyValue;
			}
	LocalRedirect($APPLICATION->GetCurPage().SgetUrlVarsString());
	}
// отменить филтр
if(isset($_POST[$inputNameCancel]))
	{
	unset($_SESSION[$filterVarName][$USER->GetId()]);
	LocalRedirect($APPLICATION->GetCurPage().SgetUrlVarsString());
	}
/* -------------------------------------------------------------------- */
/* ------------------------ примененный фильтр ------------------------ */
/* -------------------------------------------------------------------- */
foreach($_SESSION[$filterVarName][$USER->GetId()] as $property => $value)
	{
	$propertyObject = $elementObject->GetProperty($property);
	if(!$propertyObject || !$value) continue;
	$propertyObject->SetValue($value);
	$tableObject->SetQueryOptions(["filter" => ['*'.$property => $propertyObject->GetFilter()]]); // SetQueryOptions not used
	}
/* -------------------------------------------------------------------- */
/* ------------------------- свойства фильтра ------------------------- */
/* -------------------------------------------------------------------- */
foreach($elementObject->GetPropertyList() as $propertyObject)
	{
	$writeFormProps[] = $propertyObject;
	if($propertyObject->GetValueParams()["value_geted"]) $readFormProps[] = $propertyObject;
	}
/* -------------------------------------------------------------------- */
/* ----------------------- параметры для шаблона ---------------------- */
/* -------------------------------------------------------------------- */
$arResult =
	[
	"read_form_props"  => $readFormProps,  // свойства примененного фильтра
	"write_form_props" => $writeFormProps, // свойства фильтра
	"input_name"       =>                  // имена полей
		[
		"submit_button" => $inputNameSumit,
		"cancel_button" => $inputNameCancel,
		"form"          => $formInputesName
		]
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>