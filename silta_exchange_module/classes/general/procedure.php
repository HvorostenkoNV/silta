<?
abstract class SexchangeProcedure
	{
	protected
		$exchangeObject   = false,
		$xmlProcedureRoot = false,
		$procedureName    = '',
		$options          = [],
		$params           = [],
		$elements         = [],
		$errors           = [];
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	final public function __construct($exchangeObject = false, array $options = [])
		{
		$this->exchangeObject = $exchangeObject;
		$this->options        = $options;
		}
	/* ----------------------------------------------------------------- */
	/* ------------------------ простые методы ------------------------- */
	/* ----------------------------------------------------------------- */
	final public function GetName()           {return $this->procedureName;}
	final public function GetExchangeObject() {return $this->exchangeObject;}
	final public function GetOptions()        {return $this->options;}
	final public function SetError($value)    {if(!in_array($value, $this->errors)) $this->errors[] = $value;return false;}
	final public function GetErrors()         {return $this->errors;}
	// получить объект корня процедуры в XML документе
	final public function GetXmlProcedureRoot()
		{
		if(!$this->GetExchangeObject()->GetXmlDocument()) return false;
		if(!$this->xmlProcedureRoot) $this->xmlProcedureRoot = $this->CalculateProcedureXmlRoot();
		return $this->xmlProcedureRoot;
		}
	// получить параметры
	final public function GetParams()
		{
		if(count($this->params)) return $this->params;

		if(!$this->GetXmlProcedureRoot()) return [];
		if(!$this->GetOptions()["params_tag_name"])
			{
			$this->SetError(str_replace('#PROCEDURE#', $this->GetName(), GetMessage("SEM_CE_PROCEDURE_PARAMS_NOT_FULL")));
			return [];
			}

		foreach($this->CalculateProcedureParams() as $index => $value)
			if(in_array($index, $this->GetOptions()["params"]))
				$this->params[$index] = $value;
		return $this->params;
		}
	// получить элементы
	final public function GetElements()
		{
		if(count($this->elements)) return $this->elements;

		if(!$this->GetXmlProcedureRoot()) return [];
		if(!$this->GetOptions()["elements_tag_name"])
			{
			$this->SetError(str_replace('#PROCEDURE#', $this->GetName(), GetMessage("SEM_CE_PROCEDURE_PARAMS_NOT_FULL")));
			return [];
			}

		$className = $this->GetOptions()["element_class_name"];
		if($className)
			foreach($this->CalculateProcedureElements() as $elementInfo)
				$this->elements[] = new $className($this, $elementInfo);
		return $this->elements;
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- методы для перегрузки --------------------- */
	/* ----------------------------------------------------------------- */
	abstract protected function CalculateProcedureXmlRoot();
	abstract protected function CalculateProcedureParams();
	abstract protected function CalculateProcedureElements();
	}
?>