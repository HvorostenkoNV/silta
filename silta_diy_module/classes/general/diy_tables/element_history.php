<?
class SDiyModuleTableHistory extends SIBlockTable
	{
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected function ConstructObject(array $params = [])
		{
		parent::ConstructObject($params);
		$DiyModule = SDiyModule::GetInstance();
		foreach($this->GetAvailableProps() as $property) $this->SetProperty($property);
		// необходимые свойства
		$propsNeed = ["element", "text", "files", "changing", "operation_type"];
		foreach($propsNeed as $property)
			if(!$this->GetProperty($property))
				exit(ShowError(__CLASS__.'::'.__FUNCTION__.' - props required: '.implode(', ', $propsNeed)));
		// настройки свойств
		$this->SetPropertyType
			(
			"diy_element_change_history",
				[
				"title"                  => 'история изменений',
				"property_class"         => 'SDMPropertyDiyHistory',
				"element_property_class" => 'SDMPropertyElementDiyHistory'
				]
			);
		$this->GetProperty("changing")    ->ChangeType("diy_element_change_history");
		$this->GetProperty("created_date")->SetAttributes(["title" => 'Дата', "time" => 'Y']);
		$this->GetProperty("created_by")  ->SetAttributes(["title" => 'Пользователь']);
		}
	}
?>