<?
class silta_exchange_module extends CModule
	{
	public
		$MODULE_ID           = 'silta_exchange_module',
		$MODULE_VERSION      = '1.0.0',
		$MODULE_VERSION_DATE = '2016-03-21',
		$MODULE_NAME         = 'SILTA модуль обмена',
		$MODULE_DESCRIPTION  = 'SILTA модуль обмена';
	/* ----------------------------------------------------------------- */
	/* --------------------------- инсталяция -------------------------- */
	/* ----------------------------------------------------------------- */
	function DoInstall()
		{
		if(IsModuleInstalled($this->MODULE_ID))   return false;
		if(!IsModuleInstalled("silta_framework")) exit('silta_framework not instaled');

		$this->InstallFiles();
		$this->InstallDB();

		$GLOBALS["errors"] = $this->errors;
		}
	// инсталяция файлов
	function InstallFiles()
		{
		$directoryFrom = $_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/'.$this->MODULE_ID.'/install/';
		$directoryTo   = $_SERVER["DOCUMENT_ROOT"].'/bitrix/';

		CopyDirFiles($directoryFrom.'components', $directoryTo.'components/'.$this->MODULE_ID, true, true);
		return true;
		}
	// инсталяция в БД
	function InstallDB()
		{
		RegisterModule($this->MODULE_ID);
		return true;
		}
	/* ----------------------------------------------------------------- */
	/* ------------------------- деинсталяция -------------------------- */
	/* ----------------------------------------------------------------- */
	function DoUninstall()
		{
		if(!CUser::IsAdmin()) return false;

		$this->UnInstallFiles();
		$this->UnInstallDB();

		$GLOBALS["errors"] = $this->errors;
		}
	// деинсталяция файлов
	function UnInstallFiles()
		{
		DeleteDirFilesEx('/bitrix/components/'.$this->MODULE_ID.'/');
		return true;
		}
	// деинсталяция в БД
	function UnInstallDB()
		{
		UnRegisterModule($this->MODULE_ID);
		return true;
		}
	}
?>