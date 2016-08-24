<?
class SProceduresBusinessTripTable extends SIBlockTable
	{
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject(array $params = [])
		{
		parent::ConstructObject($params);
		$this->SetElementsClassName("SProceduresBusinessTripElement");
		foreach($this->GetAvailableProps() as $property) $this->SetProperty($property);
		$BusinessTrip = SProceduresBusinessTrip::GetInstance();
		/* ----------------------------------------- */
		/* --- проверка инфоблока на корректность -- */
		/* ----------------------------------------- */
		if(!$this->CheckTableValidation(
			[
			"props_existence" =>
				[
				"stage", "user_department", "trip_start_date", "trip_end_date",
				"trip_description", "path_description", "wishes_description",
				"hotel_need", "hotel_start_date", "hotel_end_date",
				"trip_day_cost", "hotel_day_cost", "hotel_comments", "trip_files",
				"ticket_name", "ticket_date", "ticket_cost",
				"returned_text", "returned_files"
				],
			"props_types"     =>
				[
				"stage" => 'list', "user_department" => 'section', "trip_start_date" => 'date', "trip_end_date" => 'date',
				"trip_description" => 'text', "path_description" => 'text', "wishes_description" => 'text',
				"hotel_need" => 'string', "hotel_start_date" => 'date', "hotel_end_date" => 'date',
				"trip_day_cost" => 'number', "hotel_day_cost" => 'number', "hotel_comments" => 'text', "trip_files" => 'file',
				"ticket_name" => 'string', "ticket_date" => 'string', "ticket_cost" => 'string',
				"returned_text" => 'text', "returned_files" => 'file'
				],
			"props_required"  => ["stage", "user_department", "trip_start_date", "trip_end_date", "trip_description", "path_description"],
			"props_multiply"  => ["trip_start_date", "trip_end_date", "hotel_start_date", "hotel_end_date", "trip_files", "ticket_name", "ticket_date", "ticket_cost"]
			]))
			return;
		/* ----------------------------------------- */
		/* ----------- настройки свойств ----------- */
		/* ----------------------------------------- */
		$this->GetProperty("hotel_need")->ChangeType("boolean");
		/* ----------------------------------------- */
		/* ----------- настройки доступа ----------- */
		/* ----------------------------------------- */
		if(CUser::IsAdmin() || in_array(CUser::GetId(), $BusinessTrip->GetProcedureOptions()["full_access"])) return;

		$availableDepartments = $BusinessTrip->GetSubordinateDepartments();
		foreach($BusinessTrip->GetAssistDepartments() as $departmentId) $availableDepartments[] = $departmentId;
		if(count($availableDepartments)) $this->SetQueryAccess(["user_department" => $availableDepartments]);
		else                             $this->SetQueryAccess(["created_by" => CUser::GetId()]);
		}
	}
?>