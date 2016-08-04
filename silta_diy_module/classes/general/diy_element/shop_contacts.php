<?
class SDiyModuleElementShopContacts extends SDiyModuleElement
	{
	protected function AccessCalculating()
		{
		if(CUser::IsAdmin()) return;
		$DiyModule = SDiyModule::GetInstance();
		foreach($this->GetPropertyList() as $property => $propertyObject)
			if(!in_array($property, $DiyModule->GetModuleOption("shop_contacts_props_to_change")))
				$propertyObject->SetAccess("write", false);
		}
	}
?>