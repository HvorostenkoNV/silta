<?
IncludeModuleLangFile(__FILE__);
class SProceduresFAWProvisionApplicationElement extends SIBlockElement
	{
	protected
		$procedureBosses       = [],
		$procedureResponsibles = [];
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
		// админ
		if(CUser::IsAdmin())
			{
			foreach(["write", "delete"] as $type)     $this->SetAccess($type, true);
			foreach(["text",  "files"]  as $property) $this->GetProperty($property)->SetAccess("write", true);
			}
		// создание заявки
		if($this->GetProperty("stage")->GetValue() == 'start' && $this->GetProperty("created_by")->GetValue() == CUser::GetID())
			{
			foreach(["write", "delete"] as $type)     $this->SetAccess($type, true);
			foreach(["text",  "files"]  as $property) $this->GetProperty($property)->SetAccess("write", true);
			}
		// согласование с руководством
		if(CUser::GetID() == $this->GetCurrentAgreementUser())
			$this->SetAccess("write", true);
		// участие ответственных лиц по обеспечению
		if($this->GetProperty("stage")->GetValue() == 'responsible' && in_array(CUser::IsAdmin(), $this->GetResponsibles()))
			$this->SetAccess("write", true);
		// обязательные свойства, открытые на запись
		if($this->GetAccess("write"))
			foreach(["active", "user_signed", "stage"] as $property)
				$this->GetProperty($property)->SetAccess("write", true);
		}
	/* ----------------------------------------------------------------- */
	/* ---------------- получить рук-телей по процедуре ---------------- */
	/* ----------------------------------------------------------------- */
	final public function GetBosses()
		{
		if($this->GetElementId() == 'new') return [];
		if($this->procedureBosses[0])      return $this->procedureBosses;

		$usersNotSigned = [$this->GetProperty("created_by")->GetValue()];
		foreach(SProceduresFixedAssetsWork::GetInstance()->GetProcedureOptions()["purchase_responsibles"] as $userId) $usersNotSigned[] = $userId;

		$sectionsQuery = CIBlockSection::GetNavChain(false, $this->GetProperty("department")->GetValue());
		while($section = $sectionsQuery->GetNext())
			{
			$bossId = (new SCompanyDepartment(["id" => $section["ID"]]))->GetBoss();
			if($bossId && !in_array($bossId, $usersNotSigned)) $this->procedureBosses[] = $bossId;
			}
		$this->procedureBosses = array_reverse($this->procedureBosses);

		return $this->procedureBosses;
		}
	/* ----------------------------------------------------------------- */
	/* ------------------ получить текущего подписанта ----------------- */
	/* ----------------------------------------------------------------- */
	final public function GetCurrentAgreementUser()
		{
		if($this->GetProperty("stage")->GetValue() != 'agreement' || $this->GetProperty("active")->GetValue() == 'N')
			return false;
		$userSigned = SgetClearArray($this->GetProperty("user_signed")->GetValue());
		foreach($this->GetBosses() as $userId)
			if(!in_array($userId, $userSigned))
				return $userId;
		}
	/* ----------------------------------------------------------------- */
	/* -------------- получить ответственных по процедуре -------------- */
	/* ----------------------------------------------------------------- */
	final public function GetResponsibles()
		{
		if($this->GetElementId() == 'new')  return [];
		if($this->procedureResponsibles[0]) return $this->procedureResponsibles;

		$fixedAssetsGroup = $this->GetProperty("fixed_assets_groups")->GetValue();
		if($fixedAssetsGroup) $FixedAssetsGroupsElement = SCompanyTables::GetInstance()->GetTable("fixed_assets_groups")->GetElement($fixedAssetsGroup);
		if($FixedAssetsGroupsElement) $this->procedureResponsibles = $FixedAssetsGroupsElement->GetProperty("responsibles")->GetUsersArray();

		return $this->procedureResponsibles;
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
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- изменить стадию заявки -------------------- */
	/* ----------------------------------------------------------------- */
	final public function ChangeStage($stage = '')
		{
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
		}
	}
?>