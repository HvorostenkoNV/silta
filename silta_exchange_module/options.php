<?
if(!$USER->IsAdmin()) return;
$moduleId = GetModuleID(__FILE__);
CModule::IncludeModule($moduleId);
IncludeModuleLangFile(__FILE__);
/* -------------------------------------------------------------------- */
/* -------------------------------- css ------------------------------- */
/* -------------------------------------------------------------------- */
?>
<style>
	[sem-procedure-title] {cursor: pointer}
</style>
<?
/* -------------------------------------------------------------------- */
/* -------------------------------- js -------------------------------- */
/* -------------------------------------------------------------------- */
?>
<script>
(function($)
	{
	// метод разворачивания/сворачивания блока процедуры
	jQuery.fn.SemToggleProcedureBlock = function(functionType)
		{
		var procedureTitleType;
		if($.inArray(functionType, ["open", "close"]) == -1) return this;
		if(functionType == 'open')  procedureTitleType = 'opened';
		if(functionType == 'close') procedureTitleType = 'closed';

		return this.each(function()
			{
			$(this).children('tr').each(function()
				{
				if($(this).attr("class") == 'heading')
					$(this).find('td').attr("sem-procedure-title", procedureTitleType);
				else
					{
					if(functionType == 'open')  $(this).show();
					if(functionType == 'close') $(this).hide();
					}
				});
			});
		};
	})(jQuery);
	// обработчики
	$(function()
		{
		$('body').find('[sem-procedure-block]').SemToggleProcedureBlock("close");
		$('[sem-procedure-title]').click(function()
			{
			var
				blockCondition = $(this).attr('sem-procedure-title'),
				functionType;

			if(blockCondition == 'closed') functionType = 'open';
			if(blockCondition == 'opened') functionType = 'close';
			$(this).closest('[sem-procedure-block]').SemToggleProcedureBlock(functionType);
			})
		});
</script>
<?
/* -------------------------------------------------------------------- */
/* ---------------------------- переменные ---------------------------- */
/* -------------------------------------------------------------------- */
$submitButtonName = $moduleId.'-settings-submit';
$dirs = // пути к папкам
	[
	"module"            => '/bitrix/modules/'.$moduleId,
	"import_procedures" => 'classes/general/procedures_import',
	"export_procedures" => 'classes/general/procedures_export',
	"import_elements"   => 'classes/general/elements_import',
	"export_elements"   => 'classes/general/elements_export'
	];
/* -------------------------------------------------------------------- */
/* ---------------------------- табы формы ---------------------------- */
/* -------------------------------------------------------------------- */
$tabControl = new CAdminTabControl
	(
	$moduleId,
		[
		["DIV" => "main",              "TAB" => GetMessage("SEM_SETTINGS_TAB_TITLE_MAIN"),   "TITLE" => GetMessage("SEM_SETTINGS_TAB_TEXT_MAIN")],
		["DIV" => "import_procedures", "TAB" => GetMessage("SEM_SETTINGS_TAB_TITLE_IMPORT"), "TITLE" => GetMessage("SEM_SETTINGS_TAB_TEXT_IMPORT")],
		["DIV" => "export_procedures", "TAB" => GetMessage("SEM_SETTINGS_TAB_TITLE_EXPORT"), "TITLE" => GetMessage("SEM_SETTINGS_TAB_TEXT_EXPORT")],
		["DIV" => "new_procedures",    "TAB" => GetMessage("SEM_SETTINGS_TAB_TITLE_NEW_PR"), "TITLE" => GetMessage("SEM_SETTINGS_TAB_TEXT_NEW_PR")]
		]
	);
/* -------------------------------------------------------------------- */
/* ---------------------------- обработчик ---------------------------- */
/* -------------------------------------------------------------------- */
if(isset($_POST[$submitButtonName]))
	{
	COption::RemoveOption($moduleId); // очистка всех ранее сохраненных параметров модуля
	$inludingClasses  = [];           // массив подключений классов (класс => путь)
	$proceduresInfo   =               // массив инфы ранее зарегестрированных процедур
		[
		"import_procedures" => $_POST["import_procedures"],
		"export_procedures" => $_POST["export_procedures"]
		];
	/* ------------------------------------------ */
	/* ---------- очистка массва инфы ----------- */
	/* ------------------------------------------ */
	foreach($proceduresInfo as $type => $arrayInfo)
		foreach($arrayInfo as $procedure => $procedureInfo)
			{
			if($procedureInfo["delete"])
				unset($proceduresInfo[$type][$procedure]);
			else
				foreach($procedureInfo as $index => $value)
					if(is_array($value))
						$proceduresInfo[$type][$procedure][$index] = SgetClearArray($value);
			}
	/* ------------------------------------------ */
	/* ------------ новая процедура ------------- */
	/* ------------------------------------------ */
	if($_POST["new_procedure"]["name"] && $_POST["new_procedure"]["code"])
		foreach($proceduresInfo as $type => $arrayInfo)
			if($_POST["new_procedure"]["type"] == $type)
				{
				if($arrayInfo[$_POST["new_procedure"]["code"]]) $_SESSION["SEM_SETTINGS_ALERT"][$USER->GetId()] = str_replace('#PROCEDUREC_CODE#', $_POST["new_procedure"]["code"], GetMessage("SEM_SETTINGS_SUBMIT_ERROR_PROCEDURE_SETED"));
				else                                      $proceduresInfo[$type][$_POST["new_procedure"]["code"]] = ["name" => $_POST["new_procedure"]["name"]];
				}
	/* ------------------------------------------ */
	/* ---------- проход по процедурам ---------- */
	/* ------------------------------------------ */
	foreach($proceduresInfo as $type => $arrayInfo)
		foreach($arrayInfo as $procedure => $procedureInfo)
			{	
			$filesNotSeted = [];
			$procedureName = str_replace(['_', '-'], '', $procedure);
			// настройки
			switch($type)
				{
				case "import_procedures":
					$classNameProcedure = 'SexchangeIP'.$procedureName;
					$classNameElement   = 'SexchangeIE'.$procedureName;
					$procedureClassFile = $dirs["import_procedures"].'/'.$classNameProcedure.'.php';
					$elementClassFile   = $dirs["import_elements"].'/'.$classNameElement.'.php';

					if(!is_file(__DIR__.'/'.$procedureClassFile))
						$filesNotSeted[__DIR__.'/'.$procedureClassFile] = '
							<?
							final class '.$classNameProcedure.' extends SexchangeImportProcedure
								{
								protected $procedureName = "'.$procedure.'";
								/* -------------------------------------------------------------------- */
								/* ----------- преобразовать полученные параметры процедуры ----------- */
								/* -------------------------------------------------------------------- */
								protected function ConvertParams(array $valueArray = [])
									{
									return $valueArray;
									}
								}
							?>';
					if(!is_file(__DIR__.'/'.$elementClassFile))
						$filesNotSeted[__DIR__.'/'.$elementClassFile] = '
							<?
							final class '.$classNameElement.' extends SexchangeImportElement
								{
								/* -------------------------------------------------------------------- */
								/* ------------------ преобразовать полученные данные ----------------- */
								/* -------------------------------------------------------------------- */
								protected function ConvertValue(array $params = [], array $valueArray = [])
									{
									return $valueArray;
									}
								/* -------------------------------------------------------------------- */
								/* ------------------- работа с полученными данными ------------------- */
								/* -------------------------------------------------------------------- */
								protected function ExchangeOperation(array $params = [], array $valueArray = [])
									{

									}
								}
							?>';
					break;
				case "export_procedures":
					$classNameProcedure = 'SexchangeEP'.$procedureName;
					$classNameElement   = 'SexchangeEE'.$procedureName;
					$procedureClassFile = $dirs["export_procedures"].'/'.$classNameProcedure.'.php';
					$elementClassFile   = $dirs["export_elements"].'/'.$classNameElement.'.php';

					if(!is_file(__DIR__.'/'.$procedureClassFile))
						$filesNotSeted[__DIR__.'/'.$procedureClassFile] = '
							<?
							final class '.$classNameProcedure.' extends SexchangeExportProcedure
								{
								protected $procedureName = "'.$procedure.'";
								/* -------------------------------------------------------------------- */
								/* ----------------- приготовить параметры процедуры ------------------ */
								/* -------------------------------------------------------------------- */
								protected function PrepareParams()
									{

									}
								/* -------------------------------------------------------------------- */
								/* --------------- приготовить массив данных для обмена --------------- */
								/* -------------------------------------------------------------------- */
								protected function PrepareElementsInfo()
									{

									}
								}
							?>';
					if(!is_file(__DIR__.'/'.$elementClassFile))
						$filesNotSeted[__DIR__.'/'.$elementClassFile] = '
							<?
							final class '.$classNameElement.' extends SexchangeExportElement
								{
								/* -------------------------------------------------------------------- */
								/* ------------------ приготовить данные по элементу ------------------ */
								/* -------------------------------------------------------------------- */
								protected function PrepareValue(array $params = [], array $valueArray = [])
									{

									}
								}
							?>';
					break;
				}
			// создание файлов классов
			foreach($filesNotSeted as $filePath => $fileText)
				{
				$fileOpen = fopen($filePath, "w");
				fwrite($fileOpen, str_replace(chr(9), '', $fileText));
				fclose($fileOpen);
				}
			// имена классов
			$proceduresInfo[$type][$procedure]["procedure_class_name"] = $classNameProcedure;
			$proceduresInfo[$type][$procedure]["element_class_name"]   = $classNameElement;
			// заполнение массива путей к классам
			$inludingClasses[$classNameProcedure] = $procedureClassFile;
			$inludingClasses[$classNameElement]   = $elementClassFile;
			}
	/* ------------------------------------------ */
	/* -- обновление файла подключения классов -- */
	/* ------------------------------------------ */
	$fileText = [];
	foreach($inludingClasses as $class => $path)
		$fileText[] = '$includeProcedures["'.$class.'"] = "'.$path.'";';
	$fileText = '
		<?
		'.implode("\n", $fileText).'
		?>';
	$fileOpen = fopen(__DIR__.'/include_procedures.php', "w");
	fwrite($fileOpen, str_replace(chr(9), '', $fileText));
	fclose($fileOpen);
	/* ------------------------------------------ */
	/* ----- сохранение параметров процедуры ---- */
	/* ------------------------------------------ */
	COption::SetOptionString($moduleId, "import_settings", serialize($_POST["import_settings"]));
	COption::SetOptionString($moduleId, "export_settings", serialize($_POST["export_settings"]));

	foreach($proceduresInfo as $type => $arrayInfo)
		{
		$proceduresArray = [];
		if($type == 'import_procedures') $procedurePrefix = 'procedure_import';
		if($type == 'export_procedures') $procedurePrefix = 'procedure_export';

		ksort($arrayInfo);
		foreach($arrayInfo as $procedure => $procedureInfo)
			{
			$proceduresArray[] = $procedure;
			COption::SetOptionString($moduleId, $procedurePrefix.'_'.$procedure, serialize($procedureInfo));
			}
		COption::SetOptionString($moduleId, $type, implode('|', $proceduresArray));
		}
	/* ------------------------------------------ */
	/* ---------------- редирект ---------------- */
	/* ------------------------------------------ */
	$currentTabInfo = explode('=', $tabControl->ActiveTabParam());
	LocalRedirect($APPLICATION->GetCurPage().SgetUrlVarsString([$currentTabInfo[0] => $currentTabInfo[1]]));
	}
/* -------------------------------------------------------------------- */
/* ------------------------------ вывод ------------------------------- */
/* -------------------------------------------------------------------- */
?>
<?
if($_SESSION["SEM_SETTINGS_ALERT"][$USER->GetId()])
	{
	ShowError($_SESSION["SEM_SETTINGS_ALERT"][$USER->GetId()]);
	unset($_SESSION["SEM_SETTINGS_ALERT"][$USER->GetId()]);
	}
?>

<?$tabControl->Begin()?>
<form method="post">
	<?
	/* ------------------------------------------ */
	/* ----------- оновные настройки ------------ */
	/* ------------------------------------------ */
	$tabControl->BeginNextTab();
	?>
		<col style="width: 30%"><col>
		<?
		foreach(["import_procedures" => 'import_settings', "export_procedures" => 'export_settings'] as $procedureType => $baseInputName):
			switch($procedureType)
				{
				case "import_procedures":
					$headerTitle   = 'SEM_SETTINGS_IMPORT_HEADER';
					$moduleOptions = SexchangeImport::GetInstance()->GetOptions();
					$paramsArray   =
						[
						"SEM_SETTINGS_IMPORT_XML_ANSWER_VERSION"              => 'xml_version',
						"SEM_SETTINGS_IMPORT_XML_ANSWER_ENCODE"               => 'xml_encoding',
						"SEM_SETTINGS_IMPORT_XML_ANSWER_ROOT_TAG"             => 'xml_root_name',
						"SEM_SETTINGS_IMPORT_XML_ANSWER_EXCHANGE_ERRORS_TAG"  => 'exchange_errors_tag',
						"SEM_SETTINGS_IMPORT_XML_ANSWER_PROCEDURE_ERRORS_TAG" => 'procedure_errors_tag',
						"SEM_SETTINGS_IMPORT_XML_ANSWER_ELEMENTS_SUCCESS_TAG" => 'elements_success_tag',
						"SEM_SETTINGS_IMPORT_XML_ANSWER_ELEMENTS_ERRORS_TAG"  => 'elements_errors_tag'
						];
					break;
				case "export_procedures":
					$headerTitle   = 'SEM_SETTINGS_EXPORT_HEADER';
					$moduleOptions = SexchangeExport::GetInstance()->GetOptions();
					$paramsArray   =
						[
						"SEM_SETTINGS_EXPORT_XML_VERSION"  => 'xml_version',
						"SEM_SETTINGS_EXPORT_XML_ENCODE"   => 'xml_encoding',
						"SEM_SETTINGS_EXPORT_XML_ROOT_TAG" => 'xml_root_name'
						];
					break;
				}
		?>
			<tr class="heading"><td colspan="2"><?=GetMessage($headerTitle)?></td></tr>
			<?foreach($paramsArray as $title => $optionName):?>
			<tr>
				<td><?=GetMessage($title)?>:                                                                                 </td>
				<td><input type="text" name="<?=$baseInputName?>[<?=$optionName?>]" value="<?=$moduleOptions[$optionName]?>"></td>
			</tr>
			<?endforeach?>
		<?endforeach?>
	<?
	/* ------------------------------------------ */
	/* ------------ процедуры обмена ------------ */
	/* ------------------------------------------ */
	foreach(["import_procedures", "export_procedures"] as $procedureType):
		switch($procedureType)
			{
			case "import_procedures":
				$proceduresArray   = SexchangeImport::GetInstance()->GetProcedures();
				$procedureClassDir = $dirs["import_procedures"];
				$elementClassDir   = $dirs["import_elements"];
				break;
			case "export_procedures":
				$proceduresArray   = SexchangeExport::GetInstance()->GetProcedures();
				$procedureClassDir = $dirs["export_procedures"];
				$elementClassDir   = $dirs["export_elements"];
				break;
			}
		$tabControl->BeginNextTab();
	?>
		<col style="width: 30%"><col>
		<?foreach($proceduresArray as $procedure => $procedureObject):?>
			<tbody sem-procedure-block>
				<tr class="heading"><td colspan="2"><?=$procedureObject->GetOptions()["name"]?></td></tr>
				<tr>
					<td><?=GetMessage("SEM_SETTINGS_PROCEDURE_DELETE")?>:                          </td>
					<td><input type="checkbox" name="<?=$procedureType?>[<?=$procedure?>][delete]"></td>
				</tr>
				<tr>
					<td><?=GetMessage("SEM_SETTINGS_PROCEDURE_NAME")?>:                                                                          </td>
					<td><input type="text" name="<?=$procedureType?>[<?=$procedure?>][name]" value="<?=$procedureObject->GetOptions()["name"]?>"></td>
				</tr>
				<tr>
					<td><?=GetMessage("SEM_SETTINGS_PROCEDURE_CODE")?>:</td>
					<td><b><?=$procedure?></b>                         </td>
				</tr>
				<?/* --- ссылки на файлы классов --- */?>
				<tr><td colspan="2">&nbsp;</td></tr>
				<?
				foreach
					(
						[
						"SEM_SETTINGS_CLASS_FILE_PROCEDURE" => $procedureClassDir.'/'.$procedureObject->GetOptions()["procedure_class_name"].'.php',
						"SEM_SETTINGS_CLASS_FILE_ELEMENT"   => $elementClassDir.'/'.$procedureObject->GetOptions()["element_class_name"].'.php'
						]
					as $title => $classFile
					):
				?>
				<tr>
					<td><?=GetMessage($title)?>:                                                                                                                                              </td>
					<td><a target="_blank" href="/bitrix/admin/fileman_file_edit.php?full_src=Y&path=<?=$dirs["module"]?>/<?=$classFile?>"><?=GetMessage("SEM_SETTINGS_CLASS_FILE_LINK")?></a></td>
				</tr>
				<?endforeach?>
				<tr><td colspan="2">&nbsp;</td></tr>
				<?/* --- параметры процедуры --- */?>
				<tr>
					<td><?=GetMessage("SEM_SETTINGS_PROCEDURE_PARAMS_TAG_NAME")?>:                                                                                     </td>
					<td><input type="text" name="<?=$procedureType?>[<?=$procedure?>][params_tag_name]" value="<?=$procedureObject->GetOptions()["params_tag_name"]?>"></td>
				</tr>
				<?if($procedureObject->GetOptions()["params_tag_name"]):?>
				<tr>
					<td><?=GetMessage("SEM_SETTINGS_PROCEDURE_PARAMS")?>:                                                         </td>
					<td><?=SFMSFMultInputes($procedureType.'['.$procedure.'][params]', $procedureObject->GetOptions()["params"])?></td>
				</tr>
				<?endif?>
				<tr><td colspan="2">&nbsp;</td></tr>
				<?/* --- свойства процедуры --- */?>
				<tr>
					<td><?=GetMessage("SEM_SETTINGS_PROCEDURE_PROPS_TAG_NAME")?>:                                                                                          </td>
					<td><input type="text" name="<?=$procedureType?>[<?=$procedure?>][elements_tag_name]" value="<?=$procedureObject->GetOptions()["elements_tag_name"]?>"></td>
				</tr>
				<?if($procedureObject->GetOptions()["elements_tag_name"]):?>
				<tr>
					<td><?=GetMessage("SEM_SETTINGS_PROCEDURE_PROPS")?>:                                                        </td>
					<td><?=SFMSFMultInputes($procedureType.'['.$procedure.'][props]', $procedureObject->GetOptions()["props"])?></td>
				</tr>
				<?endif?>
				<tr><td colspan="2">&nbsp;</td></tr>
				<?/* --- обязательные свойства --- */?>
				<?if(count($procedureObject->GetOptions()["props"])):?>
				<tr>
					<td><?=GetMessage("SEM_SETTINGS_PROCEDURE_PROPS_REQUIRED")?>:</td>
					<td>
						<?foreach($procedureObject->GetOptions()["props"] as $property):?>
						<input
							type="checkbox"
							name="<?=$procedureType?>[<?=$procedure?>][props_required][]"
							<?if(in_array($property, $procedureObject->GetOptions()["props_required"])):?>checked<?endif?>
							value="<?=$property?>"
						>
						<?=$property?><br>
						<?endforeach?>
					</td>
				</tr>
				<?endif?>
				<?/* --- уникальное свойство (ИД) --- */?>
				<?if(count($procedureObject->GetOptions()["props"])):?>
				<tr>
					<td><?=GetMessage("SEM_SETTINGS_PROCEDURE_PROP_ID")?>:</td>
					<td>
						<?
						$listArray = [];
						foreach($procedureObject->GetOptions()["props"] as $property) $listArray[$property] = $property;
						?>
						<?=SFMSFSelect($procedureType.'['.$procedure.'][prop_id]', $listArray, $procedureObject->GetOptions()["prop_id"])?>
					</td>
				</tr>
				<?endif?>
			</tbody>
		<?endforeach?>
	<?endforeach?>
	<?
	/* ------------------------------------------ */
	/* ------- регистрация новых процедур ------- */
	/* ------------------------------------------ */
	?>
	<?$tabControl->BeginNextTab()?>
		<col style="width: 30%"><col>
		<tr>
			<td><?=GetMessage("SEM_SETTINGS_PROCEDURE_NAME")?>:</td>
			<td><input type="text" name="new_procedure[name]"> </td>
		</tr>
		<tr>
			<td><?=GetMessage("SEM_SETTINGS_PROCEDURE_CODE")?>:</td>
			<td><input type="text" name="new_procedure[code]"> </td>
		</tr>
		<tr>
			<td><?=GetMessage("SEM_SETTINGS_PROCEDURE_TYPE")?>:</td>
			<td>
				<select name="new_procedure[type]">
					<option value="import_procedures"><?=GetMessage("SEM_SETTINGS_LIST_IMPORT")?></option>
					<option value="export_procedures"><?=GetMessage("SEM_SETTINGS_LIST_EXPORT")?></option>
				</select>
			</td>
		</tr>
	<?
	/* ------------------------------------------ */
	/* ----------------- кнопки ----------------- */
	/* ------------------------------------------ */
	$tabControl->Buttons();
	?>
		<input type="submit" name="<?=$submitButtonName?>" value="<?=GetMessage("MAIN_SAVE")?>" class="adm-btn-save">
	<?$tabControl->End()?>
</form>