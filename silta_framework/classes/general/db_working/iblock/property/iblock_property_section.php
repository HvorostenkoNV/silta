<?
final class SIBlockPropertySection extends SIBlockProperty
	{
	protected $propertyType = 'section';
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject()
		{
		parent::ConstructObject();
		$this->SetAttributeValue("table",          '');
		$this->SetAttributeValue("start_sections", []);
		}
	}
?>