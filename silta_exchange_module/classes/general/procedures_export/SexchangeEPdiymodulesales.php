<?
final class SexchangeEPdiymodulesales extends SexchangeExportProcedure
	{
	protected
		$procedureName      = "diy_module-sales",
		$tableObject        = false,
		$diyShopsObject     = false,
		$nomenclatureObject = false;
	/* -------------------------------------------------------------------- */
	/* -------------------------- объект таблицы -------------------------- */
	/* -------------------------------------------------------------------- */
	public function GetTableObject()
		{
		if($this->tableObject) return $this->tableObject;
		$this->tableObject = new SIBlockTable(["id" => 191]);
		if($this->tableObject)
			foreach($this->tableObject->GetAvailableProps() as $property)
				$this->tableObject->SetProperty($property);
		return $this->tableObject;
		}
	/* -------------------------------------------------------------------- */
	/* ------------------ объект таблицы "diy магазины" ------------------- */
	/* -------------------------------------------------------------------- */
	public function GetDiyShopsTableObject()
		{
		if($this->diyShopsObject) return $this->diyShopsObject;
		if(CModule::IncludeModule("silta_diy_module")) $diyShopsTableId = SDiyModule::GetInstance()->GetTablesInfo()["shops"]["id"];
		if($diyShopsTableId)                           $this->diyShopsObject = new SIBlockTable(["id" => $diyShopsTableId]);
		if($this->diyShopsObject)                      foreach($this->diyShopsObject->GetAvailableProps() as $property) $this->diyShopsObject->SetProperty($property);
		return $this->diyShopsObject;
		}
	/* -------------------------------------------------------------------- */
	/* ------------------ объект таблицы "номенклатура" ------------------- */
	/* -------------------------------------------------------------------- */
	public function GetNomenclatureTableObject()
		{
		if(!$this->nomenclatureObject) $this->nomenclatureObject = SCompanyTables::GetInstance()->GetTable("nomenclature");
		return $this->nomenclatureObject;
		}
	/* -------------------------------------------------------------------- */
	/* ----------------- приготовить параметры процедуры ------------------ */
	/* -------------------------------------------------------------------- */
	protected function PrepareParams()
		{

		}
	/* -------------------------------------------------------------------- */
	/* --------------- приготовить массив данных для обмена --------------- */
	/* -------------------------------------------------------------------- */
	protected function PrepareElementsInfo()
		{
		$startDate = $_GET["diy_sales_start_date"];
		$endDate   = $_GET["diy_sales_end_date"];
		if(!$startDate) $startDate = date("d.m.Y", AddToTimeStamp(["DD" => -1], MakeTimeStamp(date('d.m.Y'), "DD.MM.YYYY")));
		if(!$endDate)   $endDate   = date('d.m.Y');

		$RESULT      = [];
		$tableObject = $this->GetTableObject();
		if($tableObject)
			foreach($tableObject->GetQuery(["ID" => 'asc'], ["talks_type"   => 'retail', "date" => [$startDate, $endDate]]) as $elementId)
				$RESULT[] = ["ID" => $elementId];
		return $RESULT;
		}
	}
?>