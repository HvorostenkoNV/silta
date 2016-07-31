<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_exchange_module"))            return ShowError("modules required: silta_exchange_module");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

EXCHANGE_TYPE - тип обмена
PROCEDURE     - процедура обмена
*/
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
if($arParams["EXCHANGE_TYPE"] == 'import') $exchangeObject = SexchangeImport::GetInstance();
if($arParams["EXCHANGE_TYPE"] == 'export') $exchangeObject = SexchangeExport::GetInstance();
if($exchangeObject) $procedureObject = $exchangeObject->GetProcedures()[$arParams["PROCEDURE"]];
if(!$procedureObject) return ShowError(GetMessage("SEM_TP_PROCEDURE_NOT_EXIST"));
/* -------------------------------------------------------------------- */
/* ------------------------------ импорт ------------------------------ */
/* -------------------------------------------------------------------- */
if($arParams["EXCHANGE_TYPE"] == 'import')
	{
	$inputNames =
		[
		"build_xml"       => 'sem-tpi-build-xml',    // кнопка "построить XML"
		"unset_xml"       => 'sem-tpi-unset-xml',    // кнопка "сбросить XML"
		"run_exchange"    => 'sem-tpi-run-exchange', // кнопка "запустить обмен"

		"form_prefix"     => 'sem-tpif',             // базовый префикс полей формы
		"xml_version"     => 'xml-version',          // имя поля "версия XML"
		"xml_encoding"    => 'xml-encoding',         // имя поля "кодировка XML"
		"params_prefix"   => 'params',               // префикс полей параметров
		"elements_prefix" => 'elements',             // префикс полей элементов
		"element_prefix"  => '01'                    // префикс поля элемента
		];
	/* ------------------------------------------- */
	/* -------- обработчик - создание XML -------- */
	/* ------------------------------------------- */
	if(is_set($_POST[$inputNames["build_xml"]]))
		{
		$xmlInfo         = $_POST[$inputNames["form_prefix"]];
		$paramsTagName   = $procedureObject->GetOptions()["params_tag_name"];
		$elementsTagName = $procedureObject->GetOptions()["elements_tag_name"];
		if(!$paramsTagName)   $paramsTagName   = 'PROCEDURE_PARAMS';
		if(!$elementsTagName) $elementsTagName = 'PROCEDURE_ELEMENTS';
		// объекты частей XML документа
		$xmlDocument              = new DomDocument($xmlInfo[$inputNames["xml_version"]], $xmlInfo[$inputNames["xml_encoding"]]);
		$xmlProcedureRoot         =
			$xmlDocument
				->appendChild($xmlDocument->createElement("XML_ROOT"))
				->appendChild($xmlDocument->createElement($procedureObject->GetName()));
		$xmlProcedureParamsNode   = $xmlProcedureRoot->appendChild($xmlDocument->createElement($paramsTagName));
		$xmlProcedureElementsNode = $xmlProcedureRoot->appendChild($xmlDocument->createElement($elementsTagName));
		// параметры процедуры
		foreach($xmlInfo[$inputNames["params_prefix"]] as $paramName => $valueArray)
			{
			$valueArray = SgetClearArray($valueArray);
			if(!$valueArray[0]) continue;

			if(count($valueArray) <= 1)
				$xmlProcedureParamsNode->appendChild($xmlDocument->createElement($paramName, $valueArray[0]));
			else
				{
				$paramNode = $xmlProcedureParamsNode->appendChild($xmlDocument->createElement($paramName));
				foreach($valueArray as $value) $paramNode->appendChild($xmlDocument->createElement("value", $value));
				}
			}
		// элементы процедуры
		foreach($xmlInfo[$inputNames["elements_prefix"]] as $elementInfo)
			{
			$elementNode = $xmlProcedureElementsNode->appendChild($xmlDocument->createElement("ELEMENT"));
			foreach($elementInfo as $propName => $valueArray)
				{
				$valueArray = SgetClearArray($valueArray);
				if(!$valueArray[0]) continue;

				if(count($valueArray) <= 1)
					$elementNode->appendChild($xmlDocument->createElement($propName, $valueArray[0]));
				else
					{
					$propNode = $elementNode->appendChild($xmlDocument->createElement($propName));
					foreach($valueArray as $value) $propNode->appendChild($xmlDocument->createElement("value", $value));
					}
				}
			}
		// создание файла
		$_SESSION["SEM_TPI_EMULATED_XML_FILE_PATH"][$USER->GetId()] = $exchangeObject->SaveXmlFile($xmlDocument->saveXML());
		LocalRedirect($APPLICATION->GetCurPage().SgetUrlVarsString());
		}
	/* ------------------------------------------- */
	/* -------- обработчик - удаление XML -------- */
	/* ------------------------------------------- */
	if(is_set($_POST[$inputNames["unset_xml"]]))
		{
		foreach(["SEM_TPI_EMULATED_XML_FILE_PATH", "SEM_TPI_ANSWER_XML_FILE_PATH"] as $index)
			if($_SESSION[$index][$USER->GetId()])
				{
				unlink($_SERVER["DOCUMENT_ROOT"].$_SESSION[$index][$USER->GetId()]);
				unset ($_SESSION[$index][$USER->GetId()]);
				}
		LocalRedirect($APPLICATION->GetCurPage().SgetUrlVarsString());
		}
	/* ------------------------------------------- */
	/* -------- обработчик - запуск обмена ------- */
	/* ------------------------------------------- */
	if(is_set($_POST[$inputNames["run_exchange"]]))
		{
		// удаление пред.файла
		if($_SESSION["SEM_TPI_ANSWER_XML_FILE_PATH"][$USER->GetId()])
			{
			unlink($_SERVER["DOCUMENT_ROOT"].$_SESSION["SEM_TPI_ANSWER_XML_FILE_PATH"][$USER->GetId()]);
			unset ($_SESSION["SEM_TPI_ANSWER_XML_FILE_PATH"][$USER->GetId()]);
			}
		// создание нового файла
		$exchangeObject->SetXmlFile($_SESSION["SEM_TPI_EMULATED_XML_FILE_PATH"][$USER->GetId()]);
		foreach($procedureObject->GetElements() as $elementObject) $elementObject->RunExchange();
		$_SESSION["SEM_TPI_ANSWER_XML_FILE_PATH"][$USER->GetId()] = $exchangeObject->GetXmlAnswer();
		LocalRedirect($APPLICATION->GetCurPage().SgetUrlVarsString());
		}
	/* ------------------------------------------- */
	/* --------- форма для эмуляции XML ---------- */
	/* ------------------------------------------- */
	if(!$_SESSION["SEM_TPI_EMULATED_XML_FILE_PATH"][$USER->GetId()])
		$arResult =
			[
			"page_type"          => 'import_build_xml',
			"procedure_name"     => $procedureObject->GetOptions()["name"],
			"params"             => $procedureObject->GetOptions()["params"],
			"props"              => $procedureObject->GetOptions()["props"],
			"xml_default_params" =>
				[
				"version"  => '1.0',
				"encoding" => 'utf-8'
				],
			"input_name"         =>
				[
				"build_xml"       => $inputNames["build_xml"],
				"form_prefix"     => $inputNames["form_prefix"],
				"xml_version"     => $inputNames["xml_version"],
				"xml_encoding"    => $inputNames["xml_encoding"],
				"params_prefix"   => $inputNames["params_prefix"],
				"elements_prefix" => $inputNames["elements_prefix"],
				"element_prefix"  => $inputNames["element_prefix"]
				]
			];
	/* ------------------------------------------- */
	/* -------- вывод сэмулированного XML -------- */
	/* ------------------------------------------- */
	if($_SESSION["SEM_TPI_EMULATED_XML_FILE_PATH"][$USER->GetId()])
		$arResult =
			[
			"page_type"      => 'import_view_xml',
			"procedure_name" => $procedureObject->GetOptions()["name"],
			"xml_link"       => $_SESSION["SEM_TPI_EMULATED_XML_FILE_PATH"][$USER->GetId()],
			"answer_link"    => $_SESSION["SEM_TPI_ANSWER_XML_FILE_PATH"]  [$USER->GetId()],
			"input_name"     =>
				[
				"unset_xml"    => $inputNames["unset_xml"],
				"run_exchange" => $inputNames["run_exchange"]
				]
			];
	}
/* -------------------------------------------------------------------- */
/* ------------------------------ экспорт ----------------------------- */
/* -------------------------------------------------------------------- */
if($arParams["EXCHANGE_TYPE"] == 'export')
	{
	$buildXmlInputName = 'sem-tpe-build-xml'; // кнопка "построить XML"
	/* ------------------------------------------- */
	/* ---------------- обработчик --------------- */
	/* ------------------------------------------- */
	if(is_set($_POST[$buildXmlInputName]))
		{
		// удаление пред.файлов
		foreach(["SEM_TPE_XML_FILE_PATH", "SEM_TPE_ERRORS_FILE_PATH"] as $index)
			if($_SESSION[$index][$USER->GetId()])
				unlink($_SERVER["DOCUMENT_ROOT"].$_SESSION[$index][$USER->GetId()]);
		// создание XML обмена
		$_SESSION["SEM_TPE_XML_FILE_PATH"][$USER->GetId()] = $exchangeObject->GetXmlExportFile([$procedureObject->GetName()]);
		// создание XML с ошибками
		$xmlDocument        = new DomDocument('1.0', 'utf-8');
		$xmlRoot            = $xmlDocument->appendChild($xmlDocument->createElement("root"));
		$exchangeErrorsTag  = $xmlRoot->appendChild($xmlDocument->createElement("exchange_errors"));
		$procedureErrorsTag = $xmlRoot->appendChild($xmlDocument->createElement("procedure_errors"));
		$elementsErrorsTag  = $xmlRoot->appendChild($xmlDocument->createElement("elements_errors"));

		foreach($exchangeObject->GetErrors()  as $error) $exchangeErrorsTag ->appendChild($xmlDocument->createElement("error", $error));
		foreach($procedureObject->GetErrors() as $error) $procedureErrorsTag->appendChild($xmlDocument->createElement("error", $error));
		foreach($procedureObject->GetElements() as $elementObject)
			{
			$elementTag = $xmlRoot->appendChild($xmlDocument->createElement("element"));
			foreach($elementObject->GetErrors() as $error) $elementTag->appendChild($xmlDocument->createElement("error", $error));
			}

		$_SESSION["SEM_TPE_ERRORS_FILE_PATH"][$USER->GetId()] = $exchangeObject->SaveXmlFile($xmlDocument->saveXML());
		// редирект
		LocalRedirect($APPLICATION->GetCurPage().SgetUrlVarsString());
		}
	/* ------------------------------------------- */
	/* ----------- стартовая страница ------------ */
	/* ------------------------------------------- */
	$arResult =
		[
		"page_type"         => 'export',
		"procedure_name"    => $procedureObject->GetOptions()["name"],
		"xml_link"          => $_SESSION["SEM_TPE_XML_FILE_PATH"]   [$USER->GetId()],
		"errors_link"       => $_SESSION["SEM_TPE_ERRORS_FILE_PATH"][$USER->GetId()],
		"submit_input_name" => $buildXmlInputName
		];
	}
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>