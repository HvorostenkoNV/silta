<?
final class SexchangeEPportalusers extends SexchangeExportProcedure
	{
	protected $procedureName = "portal_users";
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
		$userList = CUser::GetList
			(
			$by = 'ID', $order = 'asc',
			["TIMESTAMP_1" => date("d.m.Y", AddToTimeStamp(["DD" => -1], MakeTimeStamp(date('d.m.Y'), "DD.MM.YYYY"))), "TIMESTAMP_2" => date('d.m.Y'), "ACTIVE" => "Y"],
			["FIELDS" => ["ID"]]
			);
		while($user = $userList->GetNext())
			$RESULT[] = ["ID" => $user["ID"]];

		return $RESULT;
		}
	}
?>