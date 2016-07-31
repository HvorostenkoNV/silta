<?
IncludeModuleLangFile(__FILE__);
class SProceduresFAWDisplacementApplicationElement extends SIBlockElement
	{
	/* ----------------------------------------------------------------- */
	/* ------------------------- уровеь доступа ------------------------ */
	/* ----------------------------------------------------------------- */
	protected function AccessCalculating()
		{
		if($this->GetElementId() == 'new') return;
		// полностью закрытый доступ к элементу/свойствам
		foreach($this->GetPropertyList() as $propertyObject) $propertyObject->SetAccess("write", false);
		foreach(["write", "delete"] as $type)                $this          ->SetAccess($type,   false);
		// доступ на редактирование
		if($this->GetProperty("active")->GetValue() == 'Y')
			$this->SetAccess("write", true);
		if(in_array($this->GetProperty("stage")->GetValue(), ["start", "send_to_1c"]))
			{
			$this->SetAccess("delete", true);
			foreach(["fixed_asset", "new_user", "text"] as $property) $this->GetProperty($property)->SetAccess("write", true);
			}
		// обязательные свойства, открытые на запись
		if($this->GetAccess("write"))
			foreach(["active", "stage"] as $property)
				$this->GetProperty($property)->SetAccess("write", true);
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- отправить уведомление --------------------- */
	/* ----------------------------------------------------------------- */
	final public function SendAlert($alertType = '')
		{
		if($this->GetElementId() == 'new' || !$alertType) return;
		// переменные
		$senderId        = false;
		$senderEmail     = [];
		$getersId        = [];
		$getersEmail     = [];
		$alertText       = '';
		$alertTitle      = '';
		$applicationLink = 'http://'.$_SERVER["HTTP_HOST"].SProceduresFixedAssetsWork::GetInstance()->GetComponentUrl().'displacement_application/'.$this->GetElementId().'/';
		// типы оповещений
		if($alertType == 'work_in_1c')
			{
			$alertText  = GetMessage("SP_FAW_DISPL_APPLIC_ALERT_TEXT_WORK_IN_1C");
			$alertTitle = GetMessage("SP_FAW_DISPL_APPLIC_ALERT_TITLE_WORK_IN_1C");
			$senderId   = CUser::GetId();
			$getersId[] = $this->GetProperty("created_by")->GetValue();
			}
		if($alertType == 'closed')
			{
			$alertText  = GetMessage("SP_FAW_DISPL_APPLIC_ALERT_TEXT_CLOSED");
			$alertTitle = GetMessage("SP_FAW_DISPL_APPLIC_ALERT_TITLE_CLOSED");
			$senderId   = CUser::GetId();
			$getersId[] = $this->GetProperty("created_by")->GetValue();
			}
		$getersId = [566];
		if(!$senderId || !count($getersId) || !$alertText || !$alertTitle) return;
		// emails
		$usersList = CUser::GetList($by = "ID", $order = "desc", ["ID" => implode('|', array_merge($getersId, [$senderId]))], ["FIELDS" => ["ID", "EMAIL"]]);
		while($userInfo = $usersList->GetNext())
			{
			if($userInfo["ID"] == $senderId)         $senderEmail   = $userInfo["EMAIL"];
			if(in_array($userInfo["ID"], $getersId)) $getersEmail[] = $userInfo["EMAIL"];
			}
		// отправка письма
		if($senderEmail && count($getersEmail))
			CEvent::Send
				(
				"SP_FAW", "s1",
					[
					"EMAIL_FROM"       => $senderEmail,
					"EMAIL_TO"         => implode(',', $getersEmail),
					"TITLE"            => $alertTitle,
					"TEXT"             => $alertText,
					"APPLICATION_LINK" => $applicationLink
					]
				);
		// отправка уведомлений
		foreach($getersId as $userId)
			CIMNotify::Add
				([
				"TO_USER_ID"     => $userId,
				"FROM_USER_ID"   => $senderId,
				"NOTIFY_TYPE"    => IM_NOTIFY_SYSTEM,
				"NOTIFY_MESSAGE" =>
					$alertText.
					"\n".
					'<a href="'.$applicationLink.'">'.GetMessage("SP_FAW_DISPL_APPLIC_ALERT_TEXT_LINK_NAME").'</a>'
				]);
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- изменить стадию заявки -------------------- */
	/* ----------------------------------------------------------------- */
	final public function ChangeStage($stage = '')
		{
		if($stage == 'send_to_1c')
			{
			$this->GetProperty("stage")->SetValue("send_to_1c");
			$this->SaveElement(["stage"]);
			}
		if($stage == 'work_in_1c')
			{
			$this->GetProperty("stage")->SetValue("work_in_1c");
			$this->SaveElement(["stage"]);
			$this->SendAlert("work_in_1c");
			}
		if($stage == 'end')
			{
			$this->GetProperty("active")->SetValue("N");
			$this->GetProperty("stage") ->SetValue("end");
			$this->SaveElement(["active", "stage"]);
			$this->SendAlert("closed");
			}
		}
	}
?>