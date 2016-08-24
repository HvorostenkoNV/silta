<?
final class SIBlockPropertyListElement extends SIBlockProperty
	{
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject()
		{
		parent::ConstructObject();
		$this
			->SetObjectType("list_element")
			->SetAttributeValue("table", '');
		}
	}
?>