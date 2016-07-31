<?
IncludeModuleLangFile(__FILE__);
final class SProceduresBusinessTrip extends SCompanyProcedures
	{
	protected $procedureCode = 'business_trip'; // сим.код процедуры
	/* ----------------------------------------------------------------- */
	/* -------------------- массив инфы по таблицам -------------------- */
	/* ----------------------------------------------------------------- */
	public function BuildTablesInfo()
		{
		return
			[
			"business_trip" =>
				[
				"class_name" => 'SProceduresBusinessTripTable',
				"id"         => $this->GetProcedureOptions()["iblock_id"]["business_trip"],
				"title"      => GetMessage("SP_BUSTRP_TABLE_TITLE_BUSINESS_TRIP")
				]
			];
		}
	/* ----------------------------------------------------------------- */
	/* ------------------ ответственные по выполнению ------------------ */
	/* ----------------------------------------------------------------- */
	public function GetResponsibles()
		{
		$RESULT = [];
		foreach(SCompanyDepartment::GetRootChildren() as $departmentObject)
			$RESULT[$departmentObject->GetId()] = 1;
		foreach($this->GetProcedureOptions()["responsibles"]["department"] as $index => $value)
			if($this->GetProcedureOptions()["responsibles"]["user"][$index])
				$RESULT[$value] = $this->GetProcedureOptions()["responsibles"]["user"][$index];
		return $RESULT;
		}
	}
?>