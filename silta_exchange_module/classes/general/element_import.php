<?
abstract class SexchangeImportElement extends SexchangeElement
	{
	/* ----------------------------------------------------------------- */
	/* ------------------ приготовить массив значений ------------------ */
	/* ----------------------------------------------------------------- */
	protected function CalculateValue(array $elementInfo = [])
		{
		$RESULT  = [];
		$xmlNode = $elementInfo["xml_node"];
		// выборка переданных данных
		foreach($xmlNode->children() as $index => $xmlNode)
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
		// проверка обязательных свойств
		$error = false;
		foreach($this->GetProcedureObject()->GetOptions()["props_required"] as $propName)
			if(!$RESULT[$propName])
				{
				$this->SetError(str_replace('#PROP_NAME#', $propName, GetMessage("SEM_CEIE_REQUIRED_PROP_NOT_EXIST")));
				$error = true;
				}
		// возврат значений
		if($error) return [];
		else       return $this->ConvertValue($this->GetProcedureObject()->GetParams(), $RESULT);
		}
	/* ----------------------------------------------------------------- */
	/* ------------------- выполнить операцию обмена ------------------- */
	/* ----------------------------------------------------------------- */
	public function RunExchange()
		{
		if(!count($this->GetErrors()))
			$this->ExchangeOperation($this->GetProcedureObject()->GetParams(), $this->GetValue());
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- методы для перегрузки --------------------- */
	/* ----------------------------------------------------------------- */
	abstract protected function      ConvertValue(array $params = [], array $valueArray = []);
	abstract protected function ExchangeOperation(array $params = [], array $valueArray = []);
	}
?>