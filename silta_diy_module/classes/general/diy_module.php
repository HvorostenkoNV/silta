<?
IncludeModuleLangFile(__FILE__);
final class SDiyModule
	{
	use singltone;

	protected
		$userAccessArray =                                     // массив доступа
			[
			"diy_admin"    => ["value" => false, "title" => ''],    // администратор DIY модуля
			"diy_boss"     => ["value" => false, "title" => ''],    // DIY руководитель
			"diy_customer" => ["value" => false, "title" => ''],    // DIY продавец
			"marketing"    => ["value" => false, "title" => ''],    // маркетинг
			"personal"     => ["value" => false, "title" => '']     // отдел персонала
			],

		$userDepartments = [],                                 // массив подразделений юзера
		$diyDepartments  = [],                                 // массив отделов DIY подразделения
		$diyUsers        = [],                                 // массив юзеров DIY подразделения
		$availableShops  = [],                                 // массив ИД магазинов, к которым имеется доступ

		$menuList      = [],                                   // список меню
		$tablesInfo    = [],                                   // инфа по таблицам
		$tablesObjects = [],                                   // массив объектов таблиц
		$tablesErrors  = [],                                   // массив ошибок объектов таблиц

		$diyDepartmentObject = false,                          // объект корня структуры DIY
		$moduleOptions       =                                 // параметры модуля
			[
			"diy_structure_root"       => ["value" => false],                              // Корень структуры DIY
			"marketing_structure_root" => ["value" => false],                              // Отдел маркетинга
			"personal_structure_root"  => ["value" => false],                              // Отдел персонала
			"diy_admins"               => ["value" => false, "type" => 'array'],           // Администраторы DIY модуля (ИД пользователей)

			"shops_iblock_id"            => ["value" => false],                            // ИД инфоблока - DIY магазины
			"shop_contacts_iblock_id"    => ["value" => false],                            // ИД инфоблока - Контакты магазинов
			"sales_iblock_id"            => ["value" => false],                            // ИД инфоблока - Продажи
			"plans_iblock_id"            => ["value" => false],                            // ИД инфоблока - Планы продаж
			"stockes_shops_iblock_id"    => ["value" => false],                            // ИД инфоблока - Остатки в магазинах
			"stockes_storages_iblock_id" => ["value" => false],                            // ИД инфоблока - Остатки на складах
			"element_history_iblock_id"  => ["value" => false],                            // ИД инфоблока - История изменений

			"shops_access_by_user"  => ["value" => false, "type" => 'serialized'],         // Доступ - только к магазинам своим/подчененных
			"shops_access_full"     => ["value" => false, "type" => 'serialized'],         // Доступ - ко всем
			"shops_access_write"    => ["value" => false, "type" => 'serialized'],         // Право на редактирование магазинов, к которым имеется доступ
			"shops_access_create"   => ["value" => false, "type" => 'serialized'],         // Право на создание новых магазинов
			"shops_access_delete"   => ["value" => false, "type" => 'serialized'],         // Право на удаление магазинов, к которым имеется доступ
			"shops_props_to_change" => ["value" => false, "type" => 'array'],              // свойства магазинов, доступные для редактирования

			"shop_contacts_access_by_user"  => ["value" => false, "type" => 'serialized'], // Доступ - только к контактам своим/подчененных
			"shop_contacts_access_full"     => ["value" => false, "type" => 'serialized'], // Доступ - ко всем
			"shop_contacts_access_write"    => ["value" => false, "type" => 'serialized'], // Право на редактирование контактов, к которым имеется доступ
			"shop_contacts_access_create"   => ["value" => false, "type" => 'serialized'], // Право на создание новых контактов
			"shop_contacts_access_delete"   => ["value" => false, "type" => 'serialized'], // Право на удаление контактов, к которым имеется доступ
			"shop_contacts_props_to_change" => ["value" => false, "type" => 'array'],      // свойства контактов, доступные для редактирования

			"sales_access_full"     => ["value" => false, "type" => 'serialized'],         // Доступ - ко всем
			"sales_access_write"    => ["value" => false, "type" => 'serialized'],         // Право на редактирование продаж, к которым имеется доступ
			"sales_access_create"   => ["value" => false, "type" => 'serialized'],         // Право на создание новых продаж
			"sales_access_delete"   => ["value" => false, "type" => 'serialized'],         // Право на удаление продаж, к которым имеется доступ
			"sales_props_to_change" => ["value" => false, "type" => 'array'],              // свойства продаж, доступные для редактирования
			];
	/* ----------------------------------------------------------------- */
	/* -------------------------- конструктор -------------------------- */
	/* ----------------------------------------------------------------- */
	protected static function PrepareObject($objectInstance = false)
		{
		$objectInstance->CalculateAccess();
		}
	/* ----------------------------------------------------------------- */
	/* ---------------------- ДОСТУП - определить ---------------------- */
	/* ----------------------------------------------------------------- */
	protected function CalculateAccess()
		{
		// DIY админ
		foreach(CUser::GetUserGroup(CUser::GetID()) as $groupId)
			{
			$groupsList = CGroup::GetByID($groupId);
			while($group = $groupsList->GetNext())
				if($group["STRING_ID"] == 'admin')
					$this->userAccessArray["diy_admin"]["value"] = true;
			}
		if(in_array(CUser::GetID(), $this->GetModuleOption("diy_admins")))
			$this->userAccessArray["diy_admin"]["value"] = true;
		// DIY продавец/босс
		foreach($this->GetUserDepartments() as $departmentId)
			if(in_array($departmentId, $this->GetDiyDepartments()))
				{
				if((new SCompanyDepartment(["id" => $departmentId]))->GetBoss() == CUser::GetID()) $this->userAccessArray["diy_boss"]    ["value"] = true;
				else                                                                               $this->userAccessArray["diy_customer"]["value"] = true;
				}
		// маркетинг/персонал
		foreach(["marketing_structure_root" => 'marketing', "personal_structure_root" => 'personal'] as $moduleOption => $accessType)
			{
			$departmentId = $this->GetModuleOption($moduleOption);
			if($departmentId)
				if(in_array(CUser::GetID(), (new SCompanyDepartment(["id" => $departmentId]))->GetUsers("full")))
					$this->userAccessArray[$accessType]["value"] = true;
			}
		// титулы
		foreach($this->userAccessArray as $type => $infoArray)
			$this->userAccessArray[$type]["title"] = GetMessage('SDM_ACCESS_TITLE_'.ToUpper($type));
		}
	/* ----------------------------------------------------------------- */
	/* ------------------------ ПРОСТЫЕ МЕТОДЫ ------------------------- */
	/* ----------------------------------------------------------------- */
	// методы по доступу
	public function GetAccess($accessType = '') {return $this->userAccessArray[$accessType]["value"];}
	public function GetAccessArray()            {return $this->userAccessArray;}
	// объект корня структуры DIY
	public function GetDiyDepartmentObject()
		{
		if($this->diyDepartmentObject) return $this->diyDepartmentObject;
		$departmentId = $this->GetModuleOption("diy_structure_root");
		if($departmentId) $this->diyDepartmentObject = new SCompanyDepartment(["id" => $departmentId]);
		return $this->diyDepartmentObject;
		}
	// подразделения юзера
	public function GetUserDepartments()
		{
		if($this->userDepartments[0]) return $this->userDepartments;
		$userList = CUser::GetList($by = "ID", $order = "asc" , ["ID" => CUser::GetID()], ["FIELDS" => ["ID"], "SELECT" => ["UF_DEPARTMENT"]]);
		while($user = $userList->GetNext()) $this->userDepartments = $user["UF_DEPARTMENT"];
		return $this->userDepartments;
		}
	// массив отделов DIY
	public function GetDiyDepartments()
		{
		if($this->diyDepartments[0]) return $this->diyDepartments;
		if(!$this->GetDiyDepartmentObject()) return [];
		$this->diyDepartments   = $this->GetDiyDepartmentObject()->GetDepartments();
		$this->diyDepartments[] = $this->GetDiyDepartmentObject()->GetId();
		return $this->diyDepartments;
		}
	// массив юзеров DIY
	public function GetDiyUsers()
		{
		if($this->diyUsers[0]) return $this->diyUsers;
		if(!$this->GetDiyDepartmentObject()) return [];
		$this->diyUsers = $this->GetDiyDepartmentObject()->GetUsers("full");
		return $this->diyUsers;
		}
	/* ----------------------------------------------------------------- */
	/* ------------------------ параметры модуля ----------------------- */
	/* ----------------------------------------------------------------- */
	public function GetModuleOption($option = '')
		{
		if(!is_set($this->moduleOptions[$option])) return false;
		if($this->moduleOptions[$option]["value"]) return $this->moduleOptions[$option]["value"];

		$value = COption::GetOptionString(GetModuleID(__FILE__), $option);
		if($this->moduleOptions[$option]["type"] == 'array')      $value = explode('|', $value);
		if($this->moduleOptions[$option]["type"] == 'serialized') $value = unserialize($value);
		$this->moduleOptions[$option]["value"] = $value;

		return $this->moduleOptions[$option]["value"];
		}
	/* ----------------------------------------------------------------- */
	/* -------------------------- список меню -------------------------- */
	/* ----------------------------------------------------------------- */
	public function GetMenuList()
		{
		if(count($this->menuList)) return $this->menuList;
		$this->menuList =
			[
			"shops"        => ["title" => GetMessage("SDM_MENU_TITLE_SHOPS")],
			"users"        => ["title" => GetMessage("SDM_MENU_TITLE_USERS")],
			"sales"        => ["title" => GetMessage("SDM_MENU_TITLE_SALES")],
			"sales_report" => ["title" => GetMessage("SDM_MENU_TITLE_SALES_REPORTS"), "parent" => 'sales'],
			"marketing"    => ["title" => GetMessage("SDM_MENU_TITLE_MARKETING")],
			"settings"     => ["title" => GetMessage("SDM_MENU_TITLE_SETTINGS")]
			];

		if(!$this->GetAccess("diy_admin")) unset($this->menuList["settings"]);
		return $this->menuList;
		}
	/* ----------------------------------------------------------------- */
	/* -------------------- массив инфы по таблицам -------------------- */
	/* ----------------------------------------------------------------- */
	public function GetTablesInfo()
		{
		if(count($this->tablesInfo)) return $this->tablesInfo;
		$this->tablesInfo =
			[
			"shops"         => ["module_option" => 'shops_iblock_id',         "table_class_name" => 'SDiyModuleTableShops'],
			"shop_contacts" => ["module_option" => 'shop_contacts_iblock_id', "table_class_name" => 'SDiyModuleTableShopContacts'],
			"sales"         => ["module_option" => 'sales_iblock_id',         "table_class_name" => 'SDiyModuleTableSales'],

			"plans"            => ["module_option" => 'plans_iblock_id'],
			"stockes_shops"    => ["module_option" => 'stockes_shops_iblock_id'],
			"stockes_storages" => ["module_option" => 'stockes_storages_iblock_id'],
			"element_history"  => ["module_option" => 'element_history_iblock_id', "table_class_name" => 'SDiyModuleTableHistory']
			];
		foreach($this->tablesInfo as $table => $arrayInfo)
			{
			$this->tablesInfo[$table]["id"]    = $this->GetModuleOption($arrayInfo["module_option"]);
			$this->tablesInfo[$table]["title"] = GetMessage('SDM_TABLE_TITLE_'.ToUpper($table));
			}
		return $this->tablesInfo;
		}
	/* ----------------------------------------------------------------- */
	/* ----------------------- получить таблицу ------------------------ */
	/* ----------------------------------------------------------------- */
	public function GetTable($table = '')
		{
		if($this->tablesObjects[$table])                        return $this->tablesObjects[$table];
		if($this->tablesErrors[$table])                         return false;
		if(!$this->GetTablesInfo()[$table]["id"])               return false;
		if(!$this->GetTablesInfo()[$table]["table_class_name"]) return false;

		$className   = $this->GetTablesInfo()[$table]["table_class_name"];
		$tableObject = new $className(["id" => $this->GetTablesInfo()[$table]["id"]]);

		if(!count($tableObject->GetErrors())) $this->tablesObjects[$table] = $tableObject;
		elseif(!$this->tablesErrors[$table])  $this->tablesErrors[$table]  = $tableObject->GetErrors();

		return $this->tablesObjects[$table];
		}
	public function GetTableError($table = '') {return $this->tablesErrors[$table];}
	}
?>