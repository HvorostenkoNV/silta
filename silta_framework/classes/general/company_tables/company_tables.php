<?
IncludeModuleLangFile(__FILE__);
final class SCompanyTables
	{
	use singltone;

	protected
		$tablesInfo    = [], // инфа по таблицам
		$tablesErrors  = [], // ошибки по таблицам
		$tablesObjects = []; // массив объектов таблиц
	/* ----------------------------------------------------------------- */
	/* -------------------- массив инфы по таблицам -------------------- */
	/* ----------------------------------------------------------------- */
	public function GetTablesInfo()
		{
		if(count($this->tablesInfo)) return $this->tablesInfo;
		$this->tablesInfo =
			[
			"ukraine_cities"      => ["module_option" => 'ukraine_cities_iblock_id',      "table_class_name" => 'ScompanyTableUkraineCities'],
			"contragents"         => ["module_option" => 'contragents_iblock_id',         "table_class_name" => 'ScompanyTableContragents'],
			"trade_marks"         => ["module_option" => 'trade_marks_iblock_id',         "table_class_name" => 'ScompanyTableTradeMarks'],
			"nomenclature"        => ["module_option" => 'nomenclature_iblock_id',        "table_class_name" => 'ScompanyTableNomenclature'],
			"fixed_assets"        => ["module_option" => 'fixed_assets_iblock_id',        "table_class_name" => 'ScompanyTableFixedAssets'],
			"fixed_assets_groups" => ["module_option" => 'fixed_assets_groups_iblock_id', "table_class_name" => 'ScompanyTableFixedAssetsGroups'],
			"absence"             => ["module_option" => 'absence_iblock_id',             "table_class_name" => 'ScompanyTableAbsence']
			];
		foreach($this->tablesInfo as $table => $arrayInfo)
			{
			$this->tablesInfo[$table]["id"]    = COption::GetOptionString("silta_framework", $arrayInfo["module_option"]);
			$this->tablesInfo[$table]["title"] = GetMessage('SCT_TABLE_TITLE_'.ToUpper($table));
			}
		return $this->tablesInfo;
		}
	/* ----------------------------------------------------------------- */
	/* ----------------------- получить таблицу ------------------------ */
	/* ----------------------------------------------------------------- */
	public function GetTable($table = '')
		{
		if($this->tablesObjects[$table])          return $this->tablesObjects[$table];
		if($this->tablesErrors[$table])           return false;
		if(!$this->GetTablesInfo()[$table]["id"]) return false;

		$className   = $this->GetTablesInfo()[$table]["table_class_name"];
		$tableObject = new $className(["id" => $this->GetTablesInfo()[$table]["id"]]);

		if(!count($tableObject->GetErrors())) $this->tablesObjects[$table] = $tableObject;
		elseif(!$this->tablesErrors[$table])  $this->tablesErrors[$table]  = $tableObject->GetErrors();

		return $this->tablesObjects[$table];
		}
	}
?>