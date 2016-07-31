<?
final class SIBlockPropertyListElement extends SIBlockProperty
	{
	protected $propertyType = 'list_element';
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject()
		{
		parent::ConstructObject();
		$this->SetAttributeValue("table", '');
		}
	}
?>