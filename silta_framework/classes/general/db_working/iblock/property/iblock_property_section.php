<?
final class SIBlockPropertySection extends SIBlockProperty
	{
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject()
		{
		parent::ConstructObject();
		$this
			->SetObjectType("section")
			->SetAttributeValue("table",          '')
			->SetAttributeValue("start_sections", []);
		}
	}
?>