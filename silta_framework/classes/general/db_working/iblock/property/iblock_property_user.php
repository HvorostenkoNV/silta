<?
final class SIBlockPropertyUser extends SIBlockProperty
	{
	protected $propertyType = 'user';
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject()
		{
		parent::ConstructObject();
		$this->SetAttributeValue("users",       'Y');
		$this->SetAttributeValue("departments", 'N');
		}
	}
?>