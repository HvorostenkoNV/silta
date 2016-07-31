<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_diy_module"))                 return ShowError("modules required: silta_diy_module");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

ELEMENT_OBJECT - объект элемента
SAVE_REDIRECT  - редирект после сохранения (путь. Подстроку #ELEMENT_ID# заменяет на ИД элемента)
*/
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$DiyModule     = SDiyModule::GetInstance();
$elementObject = $arParams["ELEMENT_OBJECT"];
if(!is_subclass_of($elementObject, 'SDBElement') || $elementObject->GetElementId() == 'new') return;
if($DiyModule->GetTable("element_history")) $historyElementObject = $DiyModule->GetTable("element_history")->GetElement("new");
if(!$historyElementObject || !$historyElementObject->GetAccess("write")) return;
// имена полей
$formInputesName = 'comment-props';
$inputNameSumit  = 'silta-diy-module-comment-form-'.$elementObject->GetElementId();
/* -------------------------------------------------------------------- */
/* ---------------------------- обработчик ---------------------------- */
/* -------------------------------------------------------------------- */
if(is_set($_POST[$inputNameSumit]))
	{
	$savingProps = ["name", "element", "operation_type"]; // массив свойств на сохранения
	$formValue   = $_POST[$formInputesName];              // переданный массив формы
	$saveComment = false;                                 // сохронять элемент
	// файлы формы
	foreach($_FILES[$formInputesName]["name"] as $property => $infoArray)
		foreach($infoArray["new"] as $index => $name)
			$formValue[$property]["new"][] =
				[
				"name"     => $name,
				"tmp_name" => $_FILES[$formInputesName]["tmp_name"][$property]["new"][$index],
				];
	// переданные значения свойств
	foreach($formValue as $property => $value)
		{
		$propertyObject = $historyElementObject->GetProperty($property);
		if(!$propertyObject) continue;

		$propertyObject->SetValue($value, "form");
		$savingProps[] = $property;
		if($propertyObject->GetValueParams()["value_geted"]) $saveComment = true;
		}
	// сохранение
	if($saveComment)
		{
		$historyElementObject->GetProperty("operation_type")->SetValue("comment");
		$historyElementObject->GetProperty("name")          ->SetValue($historyElementObject->GetProperty("operation_type")->GetValue("title"));
		$historyElementObject->GetProperty("element")       ->SetValue($elementObject->GetElementId());
		$historyElementObject->SaveElement($savingProps);
		}
	// редирект
	if($arParams["SAVE_REDIRECT"]) LocalRedirect(str_replace('#ELEMENT_ID#', $elementObject->GetElementId(), $arParams["SAVE_REDIRECT"]));
	else                           LocalRedirect($APPLICATION->GetCurPage().SgetUrlVarsString());
	}
/* -------------------------------------------------------------------- */
/* ----------------------- параметры для шаблона ---------------------- */
/* -------------------------------------------------------------------- */
// массив инфы по свойствам
foreach(["text", "files"] as $property)
	if($historyElementObject->GetProperty($property))
		{
		$infoArray =
			[
			"object"     => $historyElementObject->GetProperty($property),
			"input_name" => $formInputesName.'['.$property.']'
			];
		if($property == 'text') $infoArray["main_text_field"] = true;
		$propsInfo[] = $infoArray;
		}
// название элемента
if($elementObject->GetProperty("name"))
	$elementName = $elementObject->GetProperty("name")->GetValue();
// таблица
foreach($DiyModule->GetTablesInfo() as $table => $tableInfo)
	if($tableInfo["id"] == $elementObject->GetTableObject()->GetIblockId())
		$workTable = ToUpper($table);
// готовый массив
$arResult =
	[
	"table"        => $workTable,                          // таблица
	"element_name" => $elementName,                        // название элемента
	"props"        => $propsInfo,                          // массив инфы по свойствам
	"input_name"   => ["submit_button" => $inputNameSumit] // массив имен элементов формы
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>