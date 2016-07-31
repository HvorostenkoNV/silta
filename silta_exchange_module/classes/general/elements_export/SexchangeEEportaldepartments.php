<?
final class SexchangeEEportaldepartments extends SexchangeExportElement
	{
	/* -------------------------------------------------------------------- */
	/* ------------------ приготовить данные по элементу ------------------ */
	/* -------------------------------------------------------------------- */
	protected function PrepareValue(array $params = [], array $valueArray = [])
		{
		return
			[
			"id"   => $valueArray["ID"],
			"name" => $valueArray["NAME"]
			];
		}
	}
?>