<?
final class SexchangeEPportaldepartments extends SexchangeExportProcedure
	{
	protected $procedureName = "portal_departments";
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
		$sectionList = CIBlockSection::GetList(["ID" => 'asc'], ["IBLOCK_ID" => SCompanyDepartment::GetRootId()], false, ["ID", "NAME"], false);
		while($section = $sectionList->GetNext()) $RESULT[] = $section;
		return $RESULT;
		}
	}
?>