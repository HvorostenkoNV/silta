<?
abstract class SexchangeExportElement extends SexchangeElement
	{
	/* ----------------------------------------------------------------- */
	/* ------------------ приготовить массив значений ------------------ */
	/* ----------------------------------------------------------------- */
	protected function CalculateValue(array $elementInfo = [])
		{
		return $this->PrepareValue($this->GetProcedureObject()->GetParams(), $elementInfo);
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- методы для перегрузки --------------------- */
	/* ----------------------------------------------------------------- */
	abstract protected function PrepareValue(array $params = [], array $valueArray = []);
	}
?>