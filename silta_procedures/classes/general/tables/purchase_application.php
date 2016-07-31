<?
class SProceduresFAWPurchaseApplicationTable extends SIBlockTable
	{
	protected $elementsClass = 'SProceduresFAWPurchaseApplicationElement';
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject(array $params = [])
		{
		parent::ConstructObject($params);
		foreach($this->GetAvailableProps() as $property) $this->SetProperty($property);
		}
	}
?>