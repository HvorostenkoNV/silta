<?
class SProceduresFAWWriteOffApplicationTable extends SIBlockTable
	{
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject(array $params = [])
		{
		parent::ConstructObject($params);
		$this->SetElementsClassName("SProceduresFAWWriteOffApplicationElement");
		foreach($this->GetAvailableProps() as $property) $this->SetProperty($property);
		}
	}
?>