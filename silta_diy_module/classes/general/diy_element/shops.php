<?
class SDiyModuleElementShops extends SDiyModuleElement
	{
	protected function AccessCalculating()
		{
		$DiyModule = SDiyModule::GetInstance();
		foreach($this->GetPropertyList() as $property => $propertyObject)
			if(!in_array($property, $DiyModule->GetModuleOption("shops_props_to_change")))
				$propertyObject->SetAccess("write", false);
		}
	}
?>