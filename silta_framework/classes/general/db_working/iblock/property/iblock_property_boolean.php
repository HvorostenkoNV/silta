<?
IncludeModuleLangFile(__FILE__);
final class SIBlockPropertyBoolean extends SIBlockProperty
	{
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject()
		{
		parent::ConstructObject();
		$this
			->SetObjectType("boolean")
			->SetAttributeValue("list",
				[
				"Y" => ["title" => GetMessage("SF_IBLOCK_PROP_BOOLEAN_LIST_Y"), "value" => 'Y', "code" => 'Y'],
				"N" => ["title" => GetMessage("SF_IBLOCK_PROP_BOOLEAN_LIST_N"), "value" => 'N', "code" => 'N'],
				]);
		}
	}
?>