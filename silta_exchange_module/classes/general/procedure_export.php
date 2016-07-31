<?
abstract class SexchangeExportProcedure extends SexchangeProcedure
	{
	protected
		$xmlProcedureParamsNode   = false,
		$xmlProcedureElementsNode = false;
	/* ----------------------------------------------------------------- */
	/* ---------------------- XML корень процедуры --------------------- */
	/* ----------------------------------------------------------------- */
	protected function CalculateProcedureXmlRoot()
		{
		$procedureName   = $this->GetName();
		$xmlDocument     = $this->GetExchangeObject()->GetXmlDocument();
		$xmlDocumentRoot = $this->GetExchangeObject()->GetXmlDocumentRoot();
		if($xmlDocumentRoot) return $xmlDocumentRoot->appendChild($xmlDocument->createElement($procedureName));
		}
	/* ----------------------------------------------------------------- */
	/* ---------------------- параметры процедуры ---------------------- */
	/* ----------------------------------------------------------------- */
	protected function CalculateProcedureParams()
		{
		return $this->PrepareParams();
		}
	/* ----------------------------------------------------------------- */
	/* ---------------------- элементы процедуры ----------------------- */
	/* ----------------------------------------------------------------- */
	protected function CalculateProcedureElements()
		{
		return $this->PrepareElementsInfo();
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- методы для перегрузки --------------------- */
	/* ----------------------------------------------------------------- */
	abstract protected function PrepareParams();
	abstract protected function PrepareElementsInfo();
	}
?>