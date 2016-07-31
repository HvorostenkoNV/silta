<?
final class SexchangeEPfixedassetswork extends SexchangeExportProcedure
	{
	protected $procedureName = "fixed_assets_work";
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
		$RESULT = [];
		if(!CModule::IncludeModule("silta_procedures")) return $this->SetError("modules required: silta_procedures");
		// перемещение
		$displacementApplicationTable = SProceduresFixedAssetsWork::GetInstance()->GetTable("displacement_application");
		if(!$displacementApplicationTable)
			return $this->SetError(str_replace("#TABLE_NAME#", SProceduresFixedAssetsWork::GetInstance()->GetTablesInfo()["displacement_application"]["title"], GetMessage("SF_TABLE_NOT_EXIST")));
		foreach($displacementApplicationTable->GetQuery([], ["active" => 'Y', "stage" => 'send_to_1c']) as $elementId)
			$RESULT[] = ["element_object" => $displacementApplicationTable->GetElement($elementId), "type" => 'displacement'];
		return $RESULT;
		}
	}
?>