<?
final class SIBlockPropertyDate extends SIBlockProperty
	{
	protected $propertyType = 'date';
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject()
		{
		parent::ConstructObject();
		$this->SetAttributeValue("date",     'Y');
		$this->SetAttributeValue("time",     'N');
		$this->SetAttributeValue("interval", 'N');
		}
	}
?>