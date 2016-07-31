<?
class SProceduresBusinessTripTable extends SIBlockTable
	{
	protected $elementsClass = 'SProceduresBusinessTripElement';
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject(array $params = [])
		{
		parent::ConstructObject($params);
		foreach($this->GetAvailableProps() as $property) $this->SetProperty($property);
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
				"returned", "returned_text", "returned_files"
				],
			"props_types"     =>
				[
				"stage" => 'list', "user_department" => 'section', "trip_start_date" => 'date', "trip_end_date" => 'date',
				"trip_description" => 'text', "path_description" => 'text', "wishes_description" => 'text',
				"hotel_need" => 'string', "hotel_start_date" => 'date', "hotel_end_date" => 'date',
				"trip_day_cost" => 'number', "hotel_day_cost" => 'number', "hotel_comments" => 'text', "trip_files" => 'file',
				"ticket_name" => 'string', "ticket_date" => 'string', "ticket_cost" => 'string',
				"returned" => 'string', "returned_text" => 'text', "returned_files" => 'file'
				],
			"props_required"  => ["stage", "user_department", "trip_start_date", "trip_end_date", "trip_description", "path_description"],
			"props_multiply"  => ["trip_start_date", "trip_end_date", "hotel_start_date", "hotel_end_date", "trip_files", "ticket_name", "ticket_date", "ticket_cost"]
			]))
			return;
		/* ----------------------------------------- */
		/* ----------- настройки свойств ----------- */
		/* ----------------------------------------- */
		foreach(["hotel_need", "returned"] as $property) $this->GetProperty($property)->ChangeType("boolean");
		}
	}
?>