<?
abstract class SexchangeElement
	{
	protected
		$procedureObject = false,
		$value           = [],
		$error           = [];
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	final public function __construct($procedureObject = false, array $elementInfo = [])
		{
		$this->procedureObject = $procedureObject;
		$this->SetValue($elementInfo);
		}
	/* ----------------------------------------------------------------- */
	/* ------------------------ простые методы ------------------------- */
	/* ----------------------------------------------------------------- */
	final public function GetProcedureObject() {return $this->procedureObject;}
	final public function SetError($value)     {if(!in_array($value, $this->errors)) $this->errors[] = $value;return false;}
	final public function GetErrors()          {return $this->errors;}
	final public function GetValue()           {return $this->value;}
	// задать значения
	final public function SetValue(array $elementInfo = [])
		{
		foreach($this->CalculateValue($elementInfo) as $index => $value)
			if(in_array($index, $this->GetProcedureObject()->GetOptions()["props"]))
				$this->value[$index] = $value;
		// проверка обязательных свойств
		if(count($this->value))
			foreach($this->GetProcedureObject()->GetOptions()["props_required"] as $propName)
				if(!$this->value[$propName])
					$this->SetError(str_replace('#PROP_NAME#', $propName, GetMessage("SEM_CEE_REQUIRED_PROP_NOT_EXIST")));
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- методы для перегрузки --------------------- */
	/* ----------------------------------------------------------------- */
	abstract protected function CalculateValue(array $elementInfo = []);
	}
?>