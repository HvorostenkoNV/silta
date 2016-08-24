<?
class SProceduresFAWPurchaseApplicationTable extends SIBlockTable
	{
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject(array $params = [])
		{
		parent::ConstructObject($params);
		$this->SetElementsClassName("SProceduresFAWPurchaseApplicationElement");
		foreach($this->GetAvailableProps() as $property) $this->SetProperty($property);
		}
	}
?>