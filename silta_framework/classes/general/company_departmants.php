<?
final class SCompanyDepartment
	{
	protected $departmentId   = false; // ИД отдела
	protected $departmentName = '';    // Название отдела
	protected $departmentCode = '';    // код отдела
	protected $bossId         = false; // ИД рук-ля отдела

	protected $departmentParent   = false; // объект SCompanyDepartment родителя отдела
	protected $departmentChildren = [];    // массив объектов SCompanyDepartment потомков отдела

	protected static $structureRootId   = false; // ИД инфоблока структуры компании
	protected static $structureChildren = [];    // массив объектов SCompanyDepartment корневых отделов структуры компании
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	public function __construct($params = [])
		{
		if(!$params["id"] && !$params["code"]) ShowError(__CLASS__.'::'.__FUNCTION__.' - необходимо передать $params["id"] либо $params["code"]');
		if(!$params["id"])
			{
			$sectionList = CIBlockSection::GetList(["SORT" => 'asc'], ["CODE" => $params["code"]], false, ["ID"], false);
			while($section = $sectionList->GetNext()) $params["id"] = $section["ID"];
			}
		$this->departmentId = (int) $params["id"];
		}
	/* ----------------------------------------------------------------- */
	/* ----------------- получить корень орг.структуры ----------------- */
	/* ----------------------------------------------------------------- */
	public static function GetRootId()
		{
		if(self::$structureRootId) return self::$structureRootId;
		$iblockList = CIBlock::GetList([], ["CODE" => 'departments'], false, false, ["ID"]);
		while($iblock = $iblockList->Fetch()) self::$structureRootId = (int) $iblock["ID"];
		return self::$structureRootId;
		}
	/* ----------------------------------------------------------------- */
	/* ------------------------ простые методы ------------------------- */
	/* ----------------------------------------------------------------- */
	public function GetId()   {return $this->departmentId;}
	public function GetName() {$this->SetMainInfo();return $this->departmentName;}
	public function GetCode() {$this->SetMainInfo();return $this->departmentCode;}

	public function SetName($departmentName = '') {$this->departmentName = $departmentName;}
	public function SetCode($departmentCode = '') {$this->departmentCode = $departmentCode;}

	protected function SetMainInfo()
		{
		if($this->departmentName && $this->departmentCode) return;
		$sectionList = CIBlockSection::GetList(["SORT" => 'asc'], ["ID" => $this->GetId()], false, ["ID", "NAME", "CODE"], false);
		while($section = $sectionList->GetNext())
			{
			$this->departmentName = $section["NAME"];
			$this->departmentCode = $section["CODE"];
			}
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- получить руководителя --------------------- */
	/* ----------------------------------------------------------------- */
	public function GetBoss()
		{
		if(!$this->bossId) $this->bossId = CIntranetUtils::GetDepartmentManagerID($this->GetId());
		return $this->bossId;
		}
	/* ----------------------------------------------------------------- */
	/* ----------------------- получить родителя ----------------------- */
	/* ----------------------------------------------------------------- */
	public function GetParent()
		{
		if($this->departmentParent) return $this->departmentParent;

		$sectionList = CIBlockSection::GetList
			(
			["SORT" => 'asc'],
			["IBLOCK_ID" => self::GetRootId(), "ID" => $this->GetId()],
			false, ["ID", "IBLOCK_SECTION_ID", "NAME", "CODE"], false
			);
		while($section = $sectionList->GetNext())
			if($section["IBLOCK_SECTION_ID"])
				$this->departmentParent = new self(["id" => $section["IBLOCK_SECTION_ID"]]);

		return $this->departmentParent;
		}
	/* ----------------------------------------------------------------- */
	/* ----------------------- получить потомков ----------------------- */
	/* ----------------------------------------------------------------- */
	public function GetChildren()
		{
		if($this->departmentChildren[0]) return $this->departmentChildren;

		$sectionList = CIBlockSection::GetList
			(
			["SORT" => 'asc'],
			["IBLOCK_ID" => self::GetRootId(), "SECTION_ID" => $this->GetId()],
			false, ["ID", "NAME", "CODE"], false
			);
		while($section = $sectionList->GetNext())
			{
			$departmentObject = new self(["id" => $section["ID"]]);
			$departmentObject->SetName($section["NAME"]);
			$departmentObject->SetCode($section["CODE"]);
			$this->departmentChildren[] = $departmentObject;
			}

		return $this->departmentChildren;
		}
	/* ----------------------------------------------------------------- */
	/* -------------------- получить потомков корня -------------------- */
	/* ----------------------------------------------------------------- */
	public static function GetRootChildren()
		{
		if(self::$structureChildren[0]) return self::$structureChildren;

		$sectionList = CIBlockSection::GetList
			(
			["SORT" => 'asc'],
			["IBLOCK_ID" => self::GetRootId(), "SECTION_ID" => false],
			false, ["ID", "NAME", "CODE"], false
			);
		while($section = $sectionList->GetNext())
			{
			$departmentObject = new self(["id" => $section["ID"]]);
			$departmentObject->SetName($section["NAME"]);
			$departmentObject->SetCode($section["CODE"]);
			self::$structureChildren[] = $departmentObject;
			}

		return self::$structureChildren;
		}
	/* ----------------------------------------------------------------- */
	/* ---------------- получить массив ИД юзеров отдела --------------- */
	/* ----------------------------------------------------------------- */
	public function GetUsers($functionType = '')
		{
		$usersList = CUser::GetList($by = 'ID', $order = 'asc', ["UF_DEPARTMENT" => $this->GetId()], ["FIELDS" => ["ID"]]);
		while($user = $usersList->GetNext()) $RESULT[] = $user["ID"];

		if($functionType == 'full')
			{
			$usersList = CUser::GetList($by = 'ID', $order = 'asc', ["UF_DEPARTMENT" => $this->GetDepartments()], ["FIELDS" => ["ID"]]);
			while($user = $usersList->GetNext()) $RESULT[] = $user["ID"];
			}

		return $RESULT;
		}
	/* ----------------------------------------------------------------- */
	/* -------------- получить массив ИД дочерних отделов -------------- */
	/* ----------------------------------------------------------------- */
	public function GetDepartments()
		{
		$sectionList = CIBlockSection::GetByID($this->GetId());
		if($section = $sectionList->GetNext())
			{
			$subSectionList = CIBlockSection::GetList
				(
				[],
					[
					"IBLOCK_ID"     => $section["IBLOCK_ID"],
					">LEFT_MARGIN"  => $section["LEFT_MARGIN"],
					"<RIGHT_MARGIN" => $section["RIGHT_MARGIN"],
					">DEPTH_LEVEL"  => $section["DEPTH_LEVEL"]
					]
				);
			while($subSection = $subSectionList->GetNext()) $RESULT[] = $subSection["ID"];
			}
		return $RESULT;
		}
	}
?>