<?
final class SIBlockPropertyDate extends SIBlockProperty
	{
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject()
		{
		parent::ConstructObject();
		$this
			->SetObjectType("date")
			->SetAttributeValue("date",     'Y')
			->SetAttributeValue("time",     'N')
			->SetAttributeValue("interval", 'N');
		}
	}
?>