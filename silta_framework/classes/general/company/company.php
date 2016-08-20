<?
IncludeModuleLangFile(__FILE__);
final class SCompany
	{
	use singltone;

	private
		$tablesInfo    = [], // инфа по таблицам
		$tablesObjects = []; // массив объектов таблиц
	/* ----------------------------------------------------------------- */
	/* -------------------- массив инфы по таблицам -------------------- */
	/* ----------------------------------------------------------------- */
	public function GetTableInfo()
		{
		if(count($this->tablesInfo)) return $this->tablesInfo;
		// таблицы интранета
		if(CModule::IncludeModule('intranet'))
			$this->tablesInfo =
				[
				"structure" =>
					[
					"id"               => COption::GetOptionString("intranet", 'iblock_structure'),
					"title"            => GetMessage("SC_TABLE_TITLE_STRUCTURE"),
					"table_class_name" => 'ScompanyTableStructure'
					]
				];
		return $this->tablesInfo;
		}
	/* ----------------------------------------------------------------- */
	/* ----------------------- получить таблицу ------------------------ */
	/* ----------------------------------------------------------------- */
	public function GetTable($table = '')
		{
		if($this->tablesObjects[$table])                                                   return $this->tablesObjects[$table];
		if(!$this->tablesInfo[$table]["id"] || count($this->tablesInfo[$table]["errors"])) return false;

		$className   = $this->tablesInfo[$table]["table_class_name"];
		$tableObject = new $className(["id" => $this->tablesInfo[$table]["id"]]);

		if(!count($tableObject->GetErrors())) $this->tablesObjects[$table]        = $tableObject;
		else                                  $this->tablesInfo[$table]["errors"] = $tableObject->GetErrors();

		return $this->tablesObjects[$table];
		}
	}
?>