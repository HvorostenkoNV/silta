<?
final class SexchangeImport extends Sexchange
	{
	protected $uploadedXmlFile = '';
	/* ----------------------------------------------------------------- */
	/* ------------- получить массив имен классов процедур ------------- */
	/* ----------------------------------------------------------------- */
	protected function GetProceduresInfo()
		{
		$RESULT = [];
		foreach(explode('|', COption::GetOptionString($this->GetModuleID(), "import_procedures")) as $procedureName)
			{
			$procedureInfo = COption::GetOptionString($this->GetModuleID(), 'procedure_import_'.$procedureName);
			if($procedureInfo) $RESULT[$procedureName] = unserialize($procedureInfo);
			}
		return $RESULT;
		}
	/* ----------------------------------------------------------------- */
	/* ------------------- получить параметры обмена ------------------- */
	/* ----------------------------------------------------------------- */
	protected function GetExchangeOptions()
		{
		return unserialize(COption::GetOptionString($this->GetModuleID(), "import_settings"));
		}
	/* ----------------------------------------------------------------- */
	/* ---------------------- задать XML документ ---------------------- */
	/* ----------------------------------------------------------------- */
	protected function BuildXmlDocument()
		{
		if(!$this->uploadedXmlFile) return $this->SetError(GetMessage("SEM_CEI_XML_FILE_NOT_EXIST"));
		return new SimpleXMLElement($this->uploadedXmlFile);
		}
	/* ----------------------------------------------------------------- */
	/* ------------------------ задать XML файл ------------------------ */
	/* ----------------------------------------------------------------- */
	public function SetXmlFile($xmlFilePath = '')
		{
		$this->uploadedXmlFile = file_get_contents($_SERVER["DOCUMENT_ROOT"].$xmlFilePath);
		}
	/* ----------------------------------------------------------------- */
	/* ---------------------- получить XML ответ ----------------------- */
	/* ----------------------------------------------------------------- */
	public function GetXmlAnswer()
		{
		if(!$this->GetOptions()["xml_version"] || !$this->GetOptions()["xml_encoding"] || !$this->GetOptions()["xml_root_name"]) return false;
		$xmlDocument = new DomDocument($this->GetOptions()["xml_version"], $this->GetOptions()["xml_encoding"]);
		$xmlRoot     = $xmlDocument->appendChild($xmlDocument->createElement($this->GetOptions()["xml_root_name"]));
		/* ------------------------------------------ */
		/* ------------- ошибки обмена -------------- */
		/* ------------------------------------------ */
		if(count($this->GetErrors()) && $this->GetOptions()["exchange_errors_tag"])
			{
			$exchangeErrorsTag = $xmlRoot->appendChild($xmlDocument->createElement($this->GetOptions()["exchange_errors_tag"]));
			foreach($this->GetErrors() as $error) $exchangeErrorsTag->appendChild($xmlDocument->createElement("error", $error));
			}
		/* ------------------------------------------ */
		/* ---------- проход по процедурам ---------- */
		/* ------------------------------------------ */
		foreach($this->GetProcedures() as $procedure => $procedureObject)
			{
			$elementsErrors  = [];
			$elementsSuccess = [];
			$procedureTag = $xmlRoot->appendChild($xmlDocument->createElement($procedure));
			// ошибки процедуры
			if(count($procedureObject->GetErrors()) && $this->GetOptions()["procedure_errors_tag"])
				{
				$procedureErrorsTag = $procedureTag->appendChild($xmlDocument->createElement($this->GetOptions()["procedure_errors_tag"]));
				foreach($procedureObject->GetErrors() as $error) $procedureErrorsTag->appendChild($xmlDocument->createElement("error", $error));
				}
			// проход по элементов
			if($procedureObject->GetOptions()["prop_id"])
				foreach($procedureObject->GetElements() as $elementObject)
					{
					$elementIndex = $elementObject->GetValue()[$procedureObject->GetOptions()["prop_id"]];
					if(is_array($elementIndex)) $elementIndex = $elementIndex[0];
					if(!$elementIndex)          $elementIndex = 'unknown';
					$elementIndex = trim($elementIndex);
					if(!count($elementObject->GetErrors())) $elementsSuccess[] = $elementIndex;
					if(count($elementObject->GetErrors()))  $elementsErrors[$elementIndex] = $elementObject->GetErrors();
					}
			// успешные элементы
			if(count($elementsSuccess) && $this->GetOptions()["elements_success_tag"])
				{
				$elementsSuccessTag = $procedureTag->appendChild($xmlDocument->createElement($this->GetOptions()["elements_success_tag"]));
				foreach($elementsSuccess as $elementId) $elementsSuccessTag->appendChild($xmlDocument->createElement("element", $elementId));
				}
			// ошибки элементов
			if(count($elementsErrors) && $this->GetOptions()["elements_errors_tag"])
				{
				$elementsErrorsTag = $procedureTag->appendChild($xmlDocument->createElement($this->GetOptions()["elements_errors_tag"]));
				foreach($elementsErrors as $elementIndex => $errorsArray)
					{
					$elementErrorsTag  = $elementsErrorsTag->appendChild($xmlDocument->createElement("element"));
					$elementErrorsAttr = $xmlDocument->createAttribute("id");
					$elementErrorsAttr->value = $elementIndex;
					$elementErrorsTag->appendChild($elementErrorsAttr);
					foreach($errorsArray as $error) $elementErrorsTag->appendChild($xmlDocument->createElement("error", $error));
					}
				}
			}
		/* ------------------------------------------ */
		/* ------------- создание файла ------------- */
		/* ------------------------------------------ */
		return $this->SaveXmlFile($xmlDocument->saveXML());
		}
	}
?>