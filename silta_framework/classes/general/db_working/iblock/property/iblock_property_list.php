<?
final class SIBlockPropertyList extends SIBlockProperty
	{
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject()
		{
		parent::ConstructObject();
		$list = [];
		$dbQuery = CIBlockProperty::GetPropertyEnum($this->GetName(), ["SORT" => 'asc'], ["IBLOCK_ID" => $this->GetTableObject()->GetIblockId()]);
		while($arrayInfo = $dbQuery->GetNext())
			$list[$arrayInfo["EXTERNAL_ID"]] =
				[
				"title" => $arrayInfo["VALUE"],
				"value" => $arrayInfo["ID"],
				"code"  => $arrayInfo["EXTERNAL_ID"],
				];
		$this
			->SetObjectType("list")
			->SetAttributeValue("list", $list);
		}
	}
?>