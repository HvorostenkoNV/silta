<?
IncludeModuleLangFile(__FILE__);
class SProceduresBusinessTripElement extends SIBlockElement
	{
	protected
		$signBoss         = '',
		$assistUser       = '',
		$departmentObject = false;
	/* ----------------------------------------------------------------- */
	/* ------------------------- уровеь доступа ------------------------ */
	/* ----------------------------------------------------------------- */
	protected function AccessCalculating()
		{
		if($this->GetElementId() == 'new') return;
		// полностью закрытый доступ к элементу/свойствам
		foreach($this->GetPropertyList() as $propertyObject) $propertyObject->SetAccess("write", false);
		foreach(["write", "delete"] as $type)                $this          ->SetAccess($type,   false);
		if($this->GetProperty("active")->GetValue() == 'N')  return;
		// группы свойств
		$propsGroups =
			[
			"author"            => ["trip_start_date", "trip_end_date", "trip_description", "path_description", "wishes_description", "hotel_need", "hotel_start_date", "hotel_end_date"],
			"responsible"       => ["trip_day_cost", "hotel_day_cost", "hotel_comments", "trip_files", "ticket_name", "ticket_date", "ticket_cost"],
			"required_to_write" => ["active", "stage", "returned", "returned_text", "returned_files"]
			];
		// админ
		if(CUser::IsAdmin())
			{
			foreach(["write", "delete"]         as $type)     $this->SetAccess($type, true);
			foreach($propsGroups["author"]      as $property) $this->GetProperty($property)->SetAccess("write", true);
			foreach($propsGroups["responsible"] as $property) $this->GetProperty($property)->SetAccess("write", true);
			}
		// создание заявки
		if($this->GetProperty("stage")->GetValue() == 'creating' && $this->GetProperty("created_by")->GetValue() == CUser::GetID())
			{
			foreach(["write", "delete"]    as $type)     $this->SetAccess($type, true);
			foreach($propsGroups["author"] as $property) $this->GetProperty($property)->SetAccess("write", true);
			}
		// согласование с руководством
		if($this->GetProperty("stage")->GetValue() == 'boss_confirm' && CUser::GetID() == $this->GetSignBoss())
			$this->SetAccess("write", true);
		// участие ответственного
		if($this->GetProperty("stage")->GetValue() == 'manager_confirm' && CUser::IsAdmin() == $this->GetAssistUser())
			{
			$this->SetAccess("write", true);
			foreach($propsGroups["responsible"] as $property) $this->GetProperty($property)->SetAccess("write", true);
			}
		// обязательные свойства, открытые на запись
		if($this->GetAccess("write"))
			foreach($propsGroups["required_to_write"] as $property)
				$this->GetProperty($property)->SetAccess("write", true);
		}
	/* ----------------------------------------------------------------- */
	/* ---------------------- объект подразделения --------------------- */
	/* ----------------------------------------------------------------- */
	protected function GetDepartmentObject()
		{
		if(!$this->departmentObject && $this->GetElementId() != 'new') $this->departmentObject = new SCompanyDepartment(["id" => $this->GetProperty("user_department")->GetValue()]);
		return $this->departmentObject;
		}
	/* ----------------------------------------------------------------- */
	/* ----------------- получить рук-теля-подписанта ------------------ */
	/* ----------------------------------------------------------------- */
	final public function GetSignBoss()
		{
		if($this->GetElementId() == 'new') return false;
		if($this->signBoss)                return $this->signBoss;

		$departmentObject = $this->GetDepartmentObject();
		while(!$this->signBoss && $departmentObject)
			{
			$this->signBoss = $departmentObject->GetBoss();
			if(!$this->signBoss) $departmentObject = $departmentObject->GetParent();
			}

		return $this->signBoss;
		}
	/* ----------------------------------------------------------------- */
	/* -------------- получить ответственных по процедуре -------------- */
	/* ----------------------------------------------------------------- */
	final public function GetAssistUser()
		{
		if($this->GetElementId() == 'new') return false;
		if($this->assistUser)              return $this->assistUser;

		$departmentObject = $this->GetDepartmentObject();
		while(!$this->assistUser && $departmentObject)
			{
			$this->assistUser = SProceduresBusinessTrip::GetInstance()->GetResponsibles()[$departmentObject->GetId()];
			if(!$this->assistUser) $departmentObject = $departmentObject->GetParent();
			}

		return $this->assistUser;
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- отправить уведомление --------------------- */
	/* ----------------------------------------------------------------- */
	final public function SendAlert($alertType = '')
		{
		/*
		if($this->GetElementId() == 'new' || !$alertType) return;
		// переменные
		$senderId        = false;
		$senderEmail     = [];
		$getersId        = [];
		$getersEmail     = [];
		$alertText       = '';
		$alertTitle      = '';
		$applicationLink = 'http://'.$_SERVER["HTTP_HOST"].SProceduresFixedAssetsWork::GetInstance()->GetComponentUrl().'provision_application/'.$this->GetElementId().'/';
		// типы оповещений
		if($alertType == 'sign_user_alert')
			{
			$alertText  = GetMessage("SP_FAW_PROV_APPLIC_SIGN_USER_ALERT_TEXT");
			$alertTitle = GetMessage("SP_FAW_PROV_APPLIC_SIGN_USER_ALERT_TITLE");
			$senderId   = $this->GetProperty("created_by")->GetValue();
			$getersId[] = $this->GetCurrentAgreementUser();
			}
		if($alertType == 'returned_to_author')
			{
			$alertText  = GetMessage("SP_FAW_PROV_APPLIC_RETURNED_TO_AUTHOR_TEXT");
			$alertTitle = GetMessage("SP_FAW_PROV_APPLIC_RETURNED_TO_AUTHOR_TITLE");
			$senderId   = CUser::GetID();
			$getersId[] = $this->GetProperty("created_by")->GetValue();
			}
		if($alertType == 'responsibles_alert')
			{
			$alertText  = GetMessage("SP_FAW_PROV_APPLIC_RESPONSIBLES_ALERT_TEXT");
			$alertTitle = GetMessage("SP_FAW_PROV_APPLIC_RESPONSIBLES_ALERT_TITLE");
			$senderId   = $this->GetProperty("created_by")->GetValue();
			$getersId   = $this->GetResponsibles();
			}
		if($alertType == 'closed')
			{
			$alertText  = GetMessage("SP_FAW_PROV_APPLIC_CLOSED_TEXT");
			$alertTitle = GetMessage("SP_FAW_PROV_APPLIC_CLOSED_TITLE");
			$senderId   = CUser::GetID();
			$getersId   = $this->GetProperty("created_by")->GetValue();
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
					'<a href="'.$applicationLink.'">'.GetMessage("SP_FAW_PROV_APPLIC_ALERT_TEXT_LINK_NAME").'</a>'
				]);
		*/
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- изменить стадию заявки -------------------- */
	/* ----------------------------------------------------------------- */
	final public function ChangeStage($stage = '')
		{
		/*
		if($stage == 'start')
			{
			$this->GetProperty("stage")->SetValue("start");
			$this->GetProperty("user_signed")->UnsetValue();
			$this->SaveElement(["user_signed", "stage"]);
			$this->SendAlert("returned_to_author");
			}
		if($stage == 'agreement')
			{
			$this->GetProperty("stage")->SetValue("agreement");
			if($this->GetCurrentAgreementUser())
				$this->SendAlert("sign_user_alert");
			else
				{
				$this->GetProperty("stage")->SetValue("responsible");
				$this->SendAlert("responsibles_alert");
				}
			$this->SaveElement(["stage"]);
			}
		if($stage == 'end')
			{
			$this->GetProperty("active")->SetValue("N");
			$this->GetProperty("stage") ->SetValue("end");
			$this->SaveElement(["active", "stage"]);
			$this->SendAlert("closed");
			}
		if($stage == 'close')
			{
			$this->GetProperty("active")->SetValue("N");
			$this->SaveElement(["active"]);
			$this->SendAlert("closed");
			}
		*/
		}
	}
?>