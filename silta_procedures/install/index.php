<?
class silta_procedures extends CModule
	{
	public
		$MODULE_ID           = 'silta_procedures',
		$MODULE_VERSION      = '1.0.0',
		$MODULE_VERSION_DATE = '2016-03-21',
		$MODULE_NAME         = 'SILTA процедуры',
		$MODULE_DESCRIPTION  = 'SILTA процедуры';
	/* ----------------------------------------------------------------- */
	/* -------------------- INSTAL - изменения в БД -------------------- */
	/* ----------------------------------------------------------------- */
	function InstallDB()
		{
		RegisterModule($this->MODULE_ID);
		return true;
		}
	/* ----------------------------------------------------------------- */
	/* ------------------------ INSTAL - файлы ------------------------- */
	/* ----------------------------------------------------------------- */
	function InstallFiles()
		{
		$directory_from = $_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/'.$this->MODULE_ID.'/install/';
		$directory_to   = $_SERVER["DOCUMENT_ROOT"].'/bitrix/';

		CopyDirFiles($directory_from.'components', $directory_to.'components/'.$this->MODULE_ID, true, true);
		return true;
		}
	/* ----------------------------------------------------------------- */
	/* ------------------- INSTAL - полная инсталяция ------------------ */
	/* ----------------------------------------------------------------- */
	function DoInstall()
		{
		if(IsModuleInstalled($this->MODULE_ID))   return false;
		if(!IsModuleInstalled("silta_framework")) exit('silta_framework not instaled');

		$this->InstallFiles();
		$this->InstallDB();

		$GLOBALS["errors"] = $this->errors;
		}
	/* ----------------------------------------------------------------- */
	/* ------------------- UNINSTAL - изменения в БД ------------------- */
	/* ----------------------------------------------------------------- */
	function UnInstallDB()
		{
		UnRegisterModule($this->MODULE_ID);
		return true;
		}
	/* ----------------------------------------------------------------- */
	/* ----------------------- UNINSTAL - файлы ------------------------ */
	/* ----------------------------------------------------------------- */
	function UnInstallFiles()
		{
		DeleteDirFilesEx('/bitrix/components/'.$this->MODULE_ID.'/');
		return true;
		}
	/* ----------------------------------------------------------------- */
	/* ----------------- UNINSTAL - полная деинсталяция ---------------- */
	/* ----------------------------------------------------------------- */
	function DoUninstall()
		{
		if(!CUser::IsAdmin()) return false;

		$this->UnInstallFiles();
		$this->UnInstallDB();

		$GLOBALS["errors"] = $this->errors;
		}
	}
?>