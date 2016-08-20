<?
IncludeModuleLangFile(__FILE__);
class SProceduresBusinessTripElement extends SIBlockElement
	{
	protected
		$departmentObject = false,
		$signBoss         = '',
		$assistUser       = '',
		$datesInterval    =
			[
			"trip"  => [],
			"hotel" => []
			],
		$fullCost         = '';
	/* ----------------------------------------------------------------- */
	/* ------------------------ уровень доступа ------------------------ */
	/* ----------------------------------------------------------------- */
	protected function AccessCalculating()
		{
		$openProps      = [];
		$procedureStage = $this->GetStage();
		$propsGroups    =
			[
			"start"            => ["active", "stage", "name", "user_department", "trip_start_date", "trip_end_date", "trip_description", "path_description", "wishes_description", "hotel_need", "hotel_start_date", "hotel_end_date"],
			"boss_agreement"   => ["active", "stage", "returned_text", "returned_files"],
			"assist_user_work" => ["active", "stage", "trip_day_cost", "hotel_day_cost", "hotel_comments", "trip_files", "ticket_name", "ticket_date", "ticket_cost"],
			"closed"           => ["active", "stage"]
			];
		if($this->GetProperty("active")->GetValue() == 'N') $procedureStage = 'end';
		/* ----------------------------------------- */
		/* ----------------- автор ----------------- */
		/* ----------------------------------------- */
		if($this->GetElementId() == 'new' || CUser::GetID() == $this->GetProperty("created_by")->GetValue())
			switch($procedureStage)
				{
				case "start":
					$openProps = $propsGroups["start"];
					break;
				default:
					foreach(["write", "delete"] as $type) $this->SetAccess($type, false);
				}
		/* ----------------------------------------- */
		/* ----------------- босс ------------------ */
		/* ----------------------------------------- */
		if(CUser::GetID() == $this->GetSignBoss())
			{
			$this->SetAccess("delete", false);
			switch($procedureStage)
				{
				case "boss_agreement":
					$openProps = $propsGroups["boss_agreement"];
					break;
				default:
					$this->SetAccess("write", false);
				}
			}
		/* ----------------------------------------- */
		/* ------------- ответственный ------------- */
		/* ----------------------------------------- */
		if(CUser::GetID() == $this->GetAssistUser())
			switch($procedureStage)
				{
				case "start":
					foreach(["write", "delete"] as $type) $this->SetAccess($type, false);
					break;
				case "boss_agreement":
					$openProps = $propsGroups["start"];
					break;
				case "assist_user_work":
					$openProps = array_merge($propsGroups["start"], $propsGroups["assist_user_work"]);
					$this->SetAccess("delete", false);
					break;
				case "end":
					$openProps = $propsGroups["closed"];
					$this->SetAccess("delete", false);
				}
		/* ----------------------------------------- */
		/* ----------------- админ ----------------- */
		/* ----------------------------------------- */
		if(CUser::IsAdmin())
			switch($procedureStage)
				{
				case "start":
					$openProps = $propsGroups["start"];
					break;
				case "boss_agreement":
					$openProps = $propsGroups["start"];
					break;
				case "assist_user_work":
					$openProps = array_merge($propsGroups["start"], $propsGroups["assist_user_work"]);
					break;
				case "end":
					$openProps = $propsGroups["closed"];
					$this->SetAccess("delete", false);
				}
		/* ----------------------------------------- */
		/* ------- закрытие свойств на запись ------ */
		/* ----------------------------------------- */
		foreach($this->GetPropertyList() as $property => $propertyObject)
			if(!in_array($property, $openProps))
				$propertyObject->SetAccess("write", false);
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- после записи элемента --------------------- */
	/* ----------------------------------------------------------------- */
	protected function AfterElementSaving()
		{
		$this->departmentObject = false;
		$this->signBoss         = '';
		$this->assistUser       = '';
		$this->datesInterval    =
			[
			"trip"  => [],
			"hotel" => []
			];
		$this->fullCost         = '';
		}
	/* ----------------------------------------------------------------- */
	/* ---------------------- объект подразделения --------------------- */
	/* ----------------------------------------------------------------- */
	final protected function GetDepartmentObject()
		{
		if(!$this->departmentObject && $this->GetElementId() != 'new') $this->departmentObject = new SCompanyDepartment(["id" => $this->GetProperty("user_department")->GetValue()]);
		return $this->departmentObject;
		}
	/* ----------------------------------------------------------------- */
	/* ----------------- получить рук-теля-подписанта ------------------ */
	/* ----------------------------------------------------------------- */
	final public function GetSignBoss()
		{
		if($this->signBoss) return $this->signBoss;

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
		if($this->assistUser) return $this->assistUser;

		$departmentObject = $this->GetDepartmentObject();
		while(!$this->assistUser && $departmentObject)
			{
			$this->assistUser = SProceduresBusinessTrip::GetInstance()->GetResponsibles()[$departmentObject->GetId()];
			if(!$this->assistUser) $departmentObject = $departmentObject->GetParent();
			}

		return $this->assistUser;
		}
	/* ----------------------------------------------------------------- */
	/* -------------------- получить интервалы дат --------------------- */
	/* ----------------------------------------------------------------- */
	final public function GetDatesInterval($type = '')
		{
		if(!in_array($type, ["trip", "hotel"])) return [];
		if(count($this->datesInterval[$type]))  return $this->datesInterval[$type];

		foreach(["trip" => ["trip_start_date", "trip_end_date"], "hotel" => ["hotel_start_date", "hotel_end_date"]] as $propsType => $propsArray)
			if($type == $propsType)
				{
				$startDatesValue = SgetClearArray($this->GetProperty($propsArray[0])->GetValue());
				$endDatesValue   = SgetClearArray($this->GetProperty($propsArray[1])->GetValue());
				}

		foreach($startDatesValue as $index => $value)
			if($endDatesValue[$index])
				{
				$count = round((strtotime($endDatesValue[$index]) - strtotime($value))/86400);
				if(!$count || $type == 'trip') $count++;
				$this->datesInterval[$type][] =
					[
					"start" => $value,
					"end"   => $endDatesValue[$index],
					"count" => $count
					];
				}

		return $this->datesInterval[$type];
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- получить стадию заявки -------------------- */
	/* ----------------------------------------------------------------- */
	final public function GetStage()
		{
		if($this->GetElementId() == 'new') return "start";
		switch($this->GetProperty("stage")->GetValue())
			{
			case"creating":        return "start";
			case"boss_confirm":    return "boss_agreement";
			case"manager_confirm": return "assist_user_work";
			case"finished":        return "end";
			}
		}
	/* ----------------------------------------------------------------- */
	/* ----------------- получить список стадий заявки ----------------- */
	/* ----------------------------------------------------------------- */
	final public function GetStageList()
		{
		$listArray = $this->GetProperty("stage")->GetAttributes()["list"];
		return
			[
			"start"            => $listArray["creating"]["title"],
			"boss_agreement"   => $listArray["boss_confirm"]["title"],
			"assist_user_work" => $listArray["manager_confirm"]["title"],
			"end"              => $listArray["finished"]["title"]
			];
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- изменить стадию заявки -------------------- */
	/* ----------------------------------------------------------------- */
	final public function ChangeStage($stage = '', $applicationLink)
		{
		if($stage == 'start')
			{
			$this->GetProperty("stage")->SetValue("creating");
			$this->SaveElement(["stage"]);
			$this->SendAlert("returned_to_author", $applicationLink);
			}
		if($stage == 'boss_agreement')
			{
			$this->GetProperty("stage")->SetValue("boss_confirm");
			foreach(["returned_text", "returned_files"] as $property) $this->GetProperty($property)->UnsetValue();
			$this->SaveElement(["stage", "returned_text", "returned_files"]);
			$this->SendAlert("sign_boss_alert", $applicationLink);
			}
		if($stage == 'assist_user_work')
			{
			$this->GetProperty("stage")->SetValue("manager_confirm");
			$this->SaveElement(["stage"]);
			$this->SendAlert("assist_user_alert", $applicationLink);
			}
		if($stage == 'end')
			{
			$this->GetProperty("stage")->SetValue("finished");
			$this->GetProperty("active")->SetValue("N");
			$this->SaveElement(["active", "stage"]);
			$this->SendAlert("closed", $applicationLink);

			$datesArray =
				[
				"start" => SgetClearArray($this->GetProperty("trip_start_date")->GetValue()),
				"end"   => SgetClearArray($this->GetProperty("trip_end_date")  ->GetValue())
				];
			foreach($datesArray["start"] as $index => $value)
				{
				$startDate = $value;
				$endDate   = $datesArray["end"][$index];
				if(!$startDate || !$endDate) continue;

				$absenceElement = SCompanyTables::GetInstance()->GetTable("absence")->GetElement("new");
				$absenceElement->GetProperty("USER")        ->SetValue($this->GetProperty("created_by")->GetValue());
				$absenceElement->GetProperty("ABSENCE_TYPE")->SetValue("ASSIGNMENT");
				$absenceElement->GetProperty("name")        ->SetValue($absenceElement->GetProperty("ABSENCE_TYPE")->GetAttributes()["list"]["ASSIGNMENT"]["title"]);
				$absenceElement->GetProperty("active_from") ->SetValue($startDate);
				$absenceElement->GetProperty("active_to")   ->SetValue($endDate);
				$absenceElement->SaveElement([]);
				}
			}
		if($stage == 'close')
			{
			$this->GetProperty("active")->SetValue("N");
			$this->SaveElement(["active"]);
			$this->SendAlert("closed", $applicationLink);
			}
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- отправить уведомление --------------------- */
	/* ----------------------------------------------------------------- */
	final public function SendAlert($alertType = '', $applicationLink)
		{
		if($this->GetElementId() == 'new' || !$alertType) return;
		// переменные
		$senderId        = false;
		$senderEmail     = false;
		$getersId        = [];
		$getersEmail     = [];
		$alertText       = '';
		$alertTitle      = '';
		$applicationLink = 'http://'.$_SERVER["HTTP_HOST"].$applicationLink;
		// типы оповещений
		if($alertType == 'returned_to_author')
			{
			$alertText  = GetMessage("SP_BTR_RETURNED_TO_AUTHOR_TEXT");
			$alertTitle = GetMessage("SP_BTR_RETURNED_TO_AUTHOR_TITLE");
			$senderId   = CUser::GetID();
			$getersId[] = $this->GetProperty("created_by")->GetValue();
			}
		if($alertType == 'sign_boss_alert')
			{
			$alertText  = GetMessage("SP_BTR_SIGN_BOSS_ALERT_TEXT");
			$alertTitle = GetMessage("SP_BTR_SIGN_BOSS_ALERT_TITLE");
			$senderId   = $this->GetProperty("created_by")->GetValue();
			$getersId[] = $this->GetSignBoss();
			}
		if($alertType == 'assist_user_alert')
			{
			$alertText  = GetMessage("SP_BTR_ASSIST_USER_ALERT_TEXT");
			$alertTitle = GetMessage("SP_BTR_ASSIST_USER_ALERT_TITLE");
			$senderId   = $this->GetProperty("created_by")->GetValue();
			$getersId[] = $this->GetAssistUser();
			}
		if($alertType == 'closed')
			{
			$alertText  = GetMessage("SP_BTR_CLOSED_TEXT");
			$alertTitle = GetMessage("SP_BTR_CLOSED_TITLE");
			$senderId   = CUser::GetID();
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
				"SP_BTR", "s1",
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
					'<a href="'.$applicationLink.'">'.GetMessage("SP_BTR_ALERT_TEXT_LINK_NAME").'</a>'
				]);
		}
	}
?>