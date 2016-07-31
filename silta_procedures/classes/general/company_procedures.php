<?
abstract class SCompanyProcedures
	{
	use singltone;

	protected
		$procedureCode    = '', // сим.код процедуры
		$procedureOptions = [], // массив настроек процедуры
		$tablesInfo       = [], // массив инфы по таблицам
		$tablesErrors     = [], // массив ошибок таблиц
		$tablesObjects    = []; // массив объектов таблиц
	/* ----------------------------------------------------------------- */
	/* ------------------------ параметры модуля ----------------------- */
	/* ----------------------------------------------------------------- */
	final public function GetProcedureOptions()
		{
		if(!count($this->procedureOptions)) $this->procedureOptions = unserialize(COption::GetOptionString(GetModuleID(__FILE__), $this->procedureCode));
		return $this->procedureOptions;
		}
	/* ----------------------------------------------------------------- */
	/* -------------------- массив инфы по таблицам -------------------- */
	/* ----------------------------------------------------------------- */
	final public function GetTablesInfo()
		{
		if(!count($this->tablesInfo)) $this->tablesInfo = $this->BuildTablesInfo();
		return $this->tablesInfo;
		}
	protected function BuildTablesInfo() {}
	/* ----------------------------------------------------------------- */
	/* ----------------------- получить таблицу ------------------------ */
	/* ----------------------------------------------------------------- */
	final public function GetTable($table = '')
		{
		if($this->tablesObjects[$table])          return $this->tablesObjects[$table];
		if(!$this->GetTablesInfo()[$table]["id"]) return false;

		$className   = $this->GetTablesInfo()[$table]["class_name"];
		$tableObject = new $className(["id" => $this->GetTablesInfo()[$table]["id"]]);

		if(count($tableObject->GetErrors())) $this->tablesErrors[$table]  = $tableObject->GetErrors();
		else                                 $this->tablesObjects[$table] = $tableObject;

		return $this->tablesObjects[$table];
		}
	final public function GetTableErrors() {return $this->tablesErrors;}
	}
?>