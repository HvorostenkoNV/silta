<?
IncludeModuleLangFile(__FILE__);
final class SProceduresBusinessTrip extends SCompanyProcedures
	{
	protected
		$procedureCode          = 'business_trip', // сим.код процедуры
		$subordinateDepartments = [],              // подразделения, которыми руководит пользователь
		$assistDepartments      = [];              // подразделения, которые администрирует пользователь
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
	/* ----------------------------------------------------------------- */
	/* ------------------- подчиненные подразделения ------------------- */
	/* ----------------------------------------------------------------- */
	public function GetSubordinateDepartments()
		{
		if($this->subordinateDepartments[0]) return $this->subordinateDepartments;

		$userList = CUser::GetList($by = "ID", $order = "asc" , ["ID" => CUser::GetID()], ["FIELDS" => ["ID"], "SELECT" => ["UF_DEPARTMENT"]]);
		while($user = $userList->GetNext())
			foreach($user["UF_DEPARTMENT"] as $departmentId)
				{
				$departmentObject = new SCompanyDepartment(["id" => $departmentId]);
				if($departmentObject->GetBoss() == CUser::GetID())
					{
					$this->subordinateDepartments[] = $departmentObject->GetId();
					foreach($departmentObject->GetDepartments() as $childDepartmentId)
						$this->subordinateDepartments[] = $childDepartmentId;
					}
				}

		return $this->subordinateDepartments;
		}
	/* ----------------------------------------------------------------- */
	/* ----------------- администрируемые подразделения ---------------- */
	/* ----------------------------------------------------------------- */
	public function GetAssistDepartments()
		{
		if($this->assistDepartments[0]) return $this->assistDepartments;

		foreach($this->GetResponsibles() as $departmentId => $userId)
			{
			$departmentObject = new SCompanyDepartment(["id" => $departmentId]);
			$this->assistDepartments[] = $departmentObject->GetId();
			foreach($departmentObject->GetDepartments() as $childDepartmentId)
				$this->assistDepartments[] = $childDepartmentId;
			}

		return $this->assistDepartments;
		}
	}
?>