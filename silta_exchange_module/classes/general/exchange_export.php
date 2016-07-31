<?
final class SexchangeExport extends Sexchange
	{
	protected $xmlDocumentRoot = false;
	/* ----------------------------------------------------------------- */
	/* ------------- получить массив имен классов процедур ------------- */
	/* ----------------------------------------------------------------- */
	protected function GetProceduresInfo()
		{
		$RESULT = [];
		foreach(explode('|', COption::GetOptionString($this->GetModuleID(), "export_procedures")) as $procedureName)
			{
			$procedureInfo = COption::GetOptionString($this->GetModuleID(), 'procedure_export_'.$procedureName);
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
		if(!$this->GetOptions()["xml_version"] || !$this->GetOptions()["xml_encoding"]) return $this->SetError(GetMessage("SEM_CEE_XML_CREATING_ERROR"));
		return new DomDocument($this->GetOptions()["xml_version"], $this->GetOptions()["xml_encoding"]);
		}
	/* ----------------------------------------------------------------- */
	/* ----------------- получить корень XML документа ----------------- */
	/* ----------------------------------------------------------------- */
	public function GetXmlDocumentRoot()
		{
		if($this->xmlDocumentRoot) return $this->xmlDocumentRoot;
		$xmlDocument = $this->GetXmlDocument();
		if(!$xmlDocument || !$this->GetOptions()["xml_root_name"]) return false;
		$this->xmlDocumentRoot = $xmlDocument->appendChild($xmlDocument->createElement($this->GetOptions()["xml_root_name"]));
		return $this->xmlDocumentRoot;
		}
	/* ----------------------------------------------------------------- */
	/* ------------------- получить XML экспорт-файл ------------------- */
	/* ----------------------------------------------------------------- */
	public function GetXmlExportFile(array $workProcedures = [])
		{
		if(!$this->GetOptions()["xml_version"] || !$this->GetOptions()["xml_encoding"] || !$this->GetOptions()["xml_root_name"]) return false;
		$xmlDocument = new DomDocument($this->GetOptions()["xml_version"], $this->GetOptions()["xml_encoding"]);
		$xmlRoot     = $xmlDocument->appendChild($xmlDocument->createElement($this->GetOptions()["xml_root_name"]));
		/* ------------------------------------------ */
		/* ---------- проход по процедурам ---------- */
		/* ------------------------------------------ */
		foreach($workProcedures as $procedure)
			{
			$procedureObject = $this->GetProcedures()[$procedure];
			if(!$procedureObject) continue;

			$procedureTag = $xmlRoot->appendChild($xmlDocument->createElement($procedure));
			// параметры
			if(count($procedureObject->GetParams()))
				{
				$procedureParamsTag = $procedureTag->appendChild($xmlDocument->createElement($procedureObject->GetOptions()["params_tag_name"]));
				foreach($procedureObject->GetParams() as $index => $value) $procedureParamsTag->appendChild($xmlDocument->createElement($index, $value));
				}
			// элементы
			if(count($procedureObject->GetElements()))
				{
				$procedureElementsTag = $procedureTag->appendChild($xmlDocument->createElement($procedureObject->GetOptions()["elements_tag_name"]));
				foreach($procedureObject->GetElements() as $elementObject)
					{
					$elementTag = $procedureElementsTag->appendChild($xmlDocument->createElement("ELEMENT"));
					foreach($elementObject->GetValue() as $propName => $valueArray)
						{
						$valueArray = SgetClearArray($valueArray);
						if(!$valueArray[0]) continue;

						if(count($valueArray) <= 1)
							$elementTag->appendChild($xmlDocument->createElement($propName, $valueArray[0]));
						else
							{
							$propTag = $elementTag->appendChild($xmlDocument->createElement($propName));
							foreach($valueArray as $value) $propTag->appendChild($xmlDocument->createElement("value", $value));
							}
						}
					}
				}
			}
		/* ------------------------------------------ */
		/* ---------------- возврат ----------------- */
		/* ------------------------------------------ */
		return $this->SaveXmlFile($xmlDocument->saveXML());
		}
	}
?>