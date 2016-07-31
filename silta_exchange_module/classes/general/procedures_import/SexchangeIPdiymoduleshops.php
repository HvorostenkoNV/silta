<?
final class SexchangeIPdiymoduleshops extends SexchangeImportProcedure
	{
	protected
		$procedureName      = "diy_module_shops",
		$diyShopsTable      = false,
		$diyShopsTableSeted = false;
	/* -------------------------------------------------------------------- */
	/* ----------- преобразовать полученные параметры процедуры ----------- */
	/* -------------------------------------------------------------------- */
	protected function ConvertParams(array $valueArray = [])
		{
		return $valueArray;
		}
	/* -------------------------------------------------------------------- */
	/* ---------------- получить объект таблицы магазинов ----------------- */
	/* -------------------------------------------------------------------- */
	public function GetDiyShopsTable()
		{
		if($this->diyShopsTableSeted) return $this->diyShopsTable;
		$this->diyShopsTableSeted = true;

		if(!CModule::IncludeModule("silta_diy_module")) return false;
		$shopsTableId = SDiyModule::GetInstance()->GetTablesInfo()["shops"]["id"];
		if($shopsTableId) $this->diyShopsTable = new SIBlockTable(["id" => $shopsTableId]);
		if(!$this->diyShopsTable) return false;

		foreach($this->diyShopsTable->GetAvailableProps() as $property) $this->diyShopsTable->SetProperty($property);
		if($this->diyShopsTable->GetProperty("user"))                   $this->diyShopsTable->GetProperty("user")->ChangeType("user");
		return $this->diyShopsTable;
		}
	}
?>