<?
IncludeModuleLangFile(__FILE__);
final class SProceduresFixedAssetsWork extends SCompanyProcedures
	{
	protected
		$procedureCode   = 'fixed_assets_work', // сим.код процедуры
		$userDepartments = [];                  // массив подразделений юзера
	/* ----------------------------------------------------------------- */
	/* ------------------------ ПРОСТЫЕ МЕТОДЫ ------------------------- */
	/* ----------------------------------------------------------------- */
	public function GetComponentUrl() {return '/services/company_procedures/fixed_assets_work/';}
	// подразделения юзера
	public function GetUserDepartments()
		{
		if($this->userDepartments[0]) return $this->userDepartments;
		$userList = CUser::GetList($by = "ID", $order = "asc" , ["ID" => CUser::GetID()], ["FIELDS" => ["ID"], "SELECT" => ["UF_DEPARTMENT"]]);
		while($user = $userList->GetNext()) $this->userDepartments = $user["UF_DEPARTMENT"];
		return $this->userDepartments;
		}
	/* ----------------------------------------------------------------- */
	/* -------------------- массив инфы по таблицам -------------------- */
	/* ----------------------------------------------------------------- */
	public function BuildTablesInfo()
		{
		$RESULT =
			[
			"provision_application"    => ["class_name" => 'SProceduresFAWProvisionApplicationTable'],
			"displacement_application" => ["class_name" => 'SProceduresFAWDisplacementApplicationTable'],
			"purchase_application"     => ["class_name" => 'SProceduresFAWPurchaseApplicationTable'],
			"write_off_application"    => ["class_name" => 'SProceduresFAWWriteOffApplicationTable'],
			"comments"                 => ["class_name" => 'SProceduresFAWCommentsTable']
			];
		foreach($RESULT as $table => $arrayInfo)
			{
			$RESULT[$table]["id"]    = $this->GetProcedureOptions()["iblock_id"][$table];
			$RESULT[$table]["title"] = GetMessage('SP_FAW_TABLE_TITLE_'.ToUpper($table));
			}
		return $RESULT;
		}
	}
?>