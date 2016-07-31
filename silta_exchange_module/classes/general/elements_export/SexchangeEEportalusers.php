<?
final class SexchangeEEportalusers extends SexchangeExportElement
	{
	/* -------------------------------------------------------------------- */
	/* ------------------ приготовить данные по элементу ------------------ */
	/* -------------------------------------------------------------------- */
	protected function PrepareValue(array $params = [], array $valueArray = [])
		{
		$userList = CUser::GetList($by = 'ID', $order = 'asc', ["ID" => $valueArray["ID"]], ["FIELDS" => ["ID", "NAME", "LAST_NAME", "SECOND_NAME"]]);
		while($user = $userList->GetNext())
			return
				[
				"id"          => $user["ID"],
				"name"        => $user["NAME"],
				"last_name"   => $user["LAST_NAME"],
				"second_name" => $user["SECOND_NAME"]
				];
		}
	}
?>