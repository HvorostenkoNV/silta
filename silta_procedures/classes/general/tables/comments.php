<?
class SProceduresFAWCommentsTable extends SIBlockTable
	{
	protected $elementsClass = 'SProceduresFAWCommentsElement';
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject(array $params = [])
		{
		parent::ConstructObject($params);
		foreach($this->GetAvailableProps() as $property) $this->SetProperty($property);
		$this->GetProperty("created_date")->SetAttributes(["time" => 'Y']);
		/* ----------------------------------------- */
		/* --- проверка инфоблока на корректность -- */
		/* ----------------------------------------- */
		if(!$this->CheckTableValidation(
			[
			"props_existence"    => ["text", "files", "application"],
			"props_types"        => ["text" => 'text', "files" => 'file', "application" => 'string'],
			"props_multiply"     => ["files"],
			"props_not_multiply" => ["text", "application"]
			]))
			return;
		}
	}
?>