<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_diy_module"))                 return ShowError("modules required: silta_diy_module");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

ELEMENT_OBJECT    - объект элемента

FORM_PROPS        - массив свойств формы
FORM_PROPS_BUFFER - пространнство после свойст формы
LINKS             - ссылки

SAVE_REDIRECT     - редирект после сохранения (путь. Подстроку #ELEMENT_ID# заменяет на ИД элемента)
DELETE_REDIRECT   - редирект после удаления (путь)
*/
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$elementObject = $arParams["ELEMENT_OBJECT"];
if(!is_subclass_of($elementObject, 'SDBElement')) return;
if($elementObject->GetElementId() == 'new' && !$elementObject->GetAccess("write")) return;
// свойства формы
foreach($arParams["FORM_PROPS"] as $index => $value)
	if(!$elementObject->GetProperty($value))
		unset($arParams["FORM_PROPS"][$index]);
$arParams["FORM_PROPS"] = SgetClearArray($arParams["FORM_PROPS"]);
if(!$arParams["FORM_PROPS"][0]) return;
// имена полей формы
$formInputesName  = 'form_props';
$submitButtonName = 'silta-diy-module-element-form-submit-'.$elementObject->GetElementId();
$deleteButtonName = 'silta-diy-module-element-form-delete-'.$elementObject->GetElementId();
/* -------------------------------------------------------------------- */
/* ---------------------------- обработчик ---------------------------- */
/* -------------------------------------------------------------------- */
// сохранение
if(is_set($_POST[$submitButtonName]))
	{
	$formValue = $_POST[$formInputesName];
	foreach($_FILES[$formInputesName]["name"] as $property => $infoArray)
		foreach($infoArray["new"] as $index => $name)
			$formValue[$property]["new"][] =
				[
				"name"     => $name,
				"tmp_name" => $_FILES[$formInputesName]["tmp_name"][$property]["new"][$index],
				];

	$elementObject->SaveDiyElement($formValue);
	if($arParams["SAVE_REDIRECT"]) LocalRedirect(str_replace('#ELEMENT_ID#', $elementObject->GetElementId(), $arParams["SAVE_REDIRECT"]));
	else                           LocalRedirect($APPLICATION->GetCurPage().SgetUrlVarsString());
	}
// удаление
if(is_set($_POST[$deleteButtonName]))
	{
	$success = $elementObject->DeleteElement();
	if(!$success || !$arParams["DELETE_REDIRECT"]) LocalRedirect($APPLICATION->GetCurPage().SgetUrlVarsString());
	else                                           LocalRedirect($arParams["DELETE_REDIRECT"]);
	}
/* -------------------------------------------------------------------- */
/* ---------------------------- тело формы ---------------------------- */
/* -------------------------------------------------------------------- */
foreach($arParams["FORM_PROPS"] as $property)
	{
	$propertyObject = $elementObject->GetProperty($property);
	// массив инфы для постройки полей
	$infoArray = ["object" => $propertyObject];
	if(in_array($property, $arParams["FORM_PROPS_BUFFER"])) $infoArray["features"]["space"] = 'bottom';
	$propsInfo[$property] = $infoArray;
	// свойства на чтение/запись
	if($elementObject->GetElementId() != 'new')                                   $formReadProps[]  = $property;
	if($elementObject->GetAccess("write") && $propertyObject->GetAccess("write")) $formWriteProps[] = $property;
	}
/* -------------------------------------------------------------------- */
/* ----------------------- параметры для шаблона ---------------------- */
/* -------------------------------------------------------------------- */
// доступы
$formAccessCreate = false;
$formAccessDelete = false;
if($elementObject->GetElementId() == 'new') $formAccessCreate = true;
if($elementObject->GetAccess("delete"))     $formAccessDelete = true;
// таблица
foreach(SDiyModule::GetInstance()->GetTablesInfo() as $table => $tableInfo)
	if($tableInfo["id"] == $elementObject->GetTableObject()->GetIblockId())
		$workTable = ToUpper($table);
// готовый массив
$arResult =
	[
	"work_table"   => $workTable,              // рабочая таблица
	"links"        => $arParams["LINKS"],      // ссылки на страницы с другими компонентами
	"access"       =>                          // массив прав
		[
		"create" => $formAccessCreate,
		"delete" => $formAccessDelete
		],
	"input_name"   =>                          // имена полей
		[
		"form_props"    => $formInputesName,        // базовое имя свойстваств
		"submit_button" => $submitButtonName,       // имя кнопки Submit
		"delete_button" => $deleteButtonName        // имя кнопки "Удалить"
		],
	"form_body"    =>                          // инфа для постройки тела формы
		[
		"props_info" => $propsInfo,                 // массив инфы для постройки полей
		"read_form"  => $formReadProps,             // свойства на чтение
		"write_form" => $formWriteProps             // свойства на запись
		]
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>