<?
final class SIBlockPropertyUser extends SIBlockProperty
	{
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject()
		{
		parent::ConstructObject();
		$this
			->SetObjectType("user")
			->SetAttributeValue("users",       'Y')
			->SetAttributeValue("departments", 'N');
		}
	}
?>