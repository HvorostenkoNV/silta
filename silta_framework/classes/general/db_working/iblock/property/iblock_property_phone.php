<?
IncludeModuleLangFile(__FILE__);
final class SIBlockPropertyPhone extends SIBlockProperty
	{
	protected $propertyType = 'phone';
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject()
		{
		parent::ConstructObject();
		$this->SetAttributeValue
			(
			"phone_type",
				[
				"work"   => GetMessage("SF_IBLOCK_PROP_PHONE_LIST_WORK"),
				"mobile" => GetMessage("SF_IBLOCK_PROP_PHONE_LIST_MOBILE"),
				"fax"    => GetMessage("SF_IBLOCK_PROP_PHONE_LIST_FAX"),
				"other"  => GetMessage("SF_IBLOCK_PROP_PHONE_LIST_OTHER")
				]
			);
		}
	}
?>