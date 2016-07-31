<?
abstract class SexchangeImportProcedure extends SexchangeProcedure
	{
	/* ----------------------------------------------------------------- */
	/* ---------------------- XML корень процедуры --------------------- */
	/* ----------------------------------------------------------------- */
	protected function CalculateProcedureXmlRoot()
		{
		$procedureName = $this->GetName();
		return $this->GetExchangeObject()->GetXmlDocument()->$procedureName;
		}
	/* ----------------------------------------------------------------- */
	/* ---------------------- параметры процедуры ---------------------- */
	/* ----------------------------------------------------------------- */
	protected function CalculateProcedureParams()
		{
		$paramsTagName   = $this->GetOptions()["params_tag_name"];
		$paramsTagObject = $this->GetXmlProcedureRoot()->$paramsTagName;
		if(!$paramsTagObject) return $this->SetError(str_replace('#PROCEDURE#', $this->GetName(), GetMessage("SEM_CEIP_PARAMS_TAG_NOT_EXIST")));

		$RESULT = [];
		foreach($paramsTagObject->children() as $index => $xmlNode)
			{
			if(!$xmlNode->children())
				{
				$value = (string) $xmlNode;
				if($value) $RESULT[$index] = $value;
				}
			else
				foreach($xmlNode->children() as $xmlNodeChild)
					{
					$value = (string) $xmlNodeChild;
					if($value) $RESULT[$index][] = $value;
					}
			}
		return $this->ConvertParams($RESULT);
		}
	/* ----------------------------------------------------------------- */
	/* ---------------------- элементы процедуры ----------------------- */
	/* ----------------------------------------------------------------- */
	protected function CalculateProcedureElements()
		{
		$elementsTagName   = $this->GetOptions()["elements_tag_name"];
		$elementsTagObject = $this->GetXmlProcedureRoot()->$elementsTagName;
		if(!$elementsTagObject) return $this->SetError(str_replace('#PROCEDURE#', $this->GetName(), GetMessage("SEM_CEIP_ELEMENTS_TAG_NOT_EXIST")));

		foreach($elementsTagObject->children() as $xmlNode)
			$RESULT[] = ["xml_node" => $xmlNode];
		return $RESULT;
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- методы для перегрузки --------------------- */
	/* ----------------------------------------------------------------- */
	abstract protected function ConvertParams(array $valueArray = []);
	}
?>