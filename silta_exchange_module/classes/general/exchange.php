<?
IncludeModuleLangFile(__FILE__);
abstract class Sexchange
	{
	use singltone;

	protected
		$moduleId    = '',    // ИД модуля
		$xmlDocument = false, // объект XML документа
		$procedures  = [],    // массив процедур
		$options     = [],    // параметры обмена
		$errors      = [];    // массив ошибок
	/* ----------------------------------------------------------------- */
	/* ------------------------ простые методы ------------------------- */
	/* ----------------------------------------------------------------- */
	final public function SetError($value) {if(!in_array($value, $this->errors)) $this->errors[] = $value;return false;}
	final public function GetErrors()      {return $this->errors;}
	// получить ИД модуля
	final public function GetModuleID()
		{
		if(!$this->moduleId) $this->moduleId = GetModuleID(__FILE__);
		return $this->moduleId;
		}
	// получить процедуры
	final public function GetProcedures()
		{
		if(!count($this->procedures))
			foreach($this->GetProceduresInfo() as $procedure => $procedureInfo)
				if(class_exists($procedureInfo["procedure_class_name"]))
					$this->procedures[$procedure] = new $procedureInfo["procedure_class_name"]($this, $procedureInfo);
		return $this->procedures;
		}
	// получить параметры
	final public function GetOptions()
		{
		if(!count($this->options)) $this->options = $this->GetExchangeOptions();
		return $this->options;
		}
	// получить объект XML документа
	final public function GetXmlDocument()
		{
		if(!$this->xmlDocument) $this->xmlDocument = $this->BuildXmlDocument();
		return $this->xmlDocument;
		}
	// сохранить XML файл
	final public function SaveXmlFile($fileContent = '')
		{
		if(!$fileContent) return false;
		$xmlFilePath = '/upload/sem_'.rand().'.xml';
		$fileOpen    = fopen($_SERVER["DOCUMENT_ROOT"].$xmlFilePath, "w");
		fwrite($fileOpen, $fileContent);
		fclose($fileOpen);
		return $xmlFilePath;
		}
	/* ----------------------------------------------------------------- */
	/* --------------------- методы для перегрузки --------------------- */
	/* ----------------------------------------------------------------- */
	abstract protected function GetProceduresInfo();
	abstract protected function GetExchangeOptions();
	abstract protected function BuildXmlDocument();
	}
?>