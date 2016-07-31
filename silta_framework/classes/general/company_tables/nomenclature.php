<?
class ScompanyTableNomenclature extends SIBlockTable
	{
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