<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_exchange_module"))            return;
// переменные
$proceduresList["all"] = GetMessage("SEM_I_EMPTY_LIST");
foreach(SexchangeImport::GetInstance()->GetProcedures() as $procedure => $procedureObject)
	$proceduresList[$procedure] = $procedureObject->GetOptions()["name"];
// готовый массив
$arComponentParameters =
	[
	"GROUPS" =>
		[
		"MAIN" => ["NAME" => GetMessage("SEM_I_GROUPS_MAIN")]
		],
	"PARAMETERS" =>
		[
		"FILE_VAR_NAME" =>
			[
			"PARENT" => 'MAIN',
			"NAME"   => GetMessage("SEM_I_FILE_VAR_NAME"),
			"TYPE"   => 'STRING'
			],
		"PROCEDURES" =>
			[
			"PARENT"   => 'MAIN',
			"NAME"     => GetMessage("SEM_I_PROCEDURES"),
			"TYPE"     => 'LIST',
			"SIZE"     => 5,
			"MULTIPLE" => 'Y',
			"VALUES"   => $proceduresList
			]
		]
	];
?>