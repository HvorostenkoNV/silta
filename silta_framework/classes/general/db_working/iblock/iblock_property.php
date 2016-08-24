<?
abstract class SIBlockProperty extends SDBProperty
	{
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject()
		{
		$this
			->SetAttributeValue("main_info", 'N')
			->SetAttributeValue("id", '');
		}
	}
?>