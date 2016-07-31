<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_exchange_module"))            return;
// переменные
$proceduresList["all"] = GetMessage("SEM_E_EMPTY_LIST");
foreach(SexchangeExport::GetInstance()->GetProcedures() as $procedure => $procedureObject)
	$proceduresList[$procedure] = $procedureObject->GetOptions()["name"];
// готовый массив
$arComponentParameters =
	[
	"GROUPS" =>
		[
		"MAIN" => ["NAME" => GetMessage("SEM_E_GROUPS_MAIN")]
		],
	"PARAMETERS" =>
		[
		"PROCEDURES" =>
			[
			"PARENT"   => 'MAIN',
			"NAME"     => GetMessage("SEM_E_PROCEDURES"),
			"TYPE"     => 'LIST',
			"SIZE"     => 5,
			"MULTIPLE" => 'Y',
			"VALUES"   => $proceduresList
			]
		]
	];
?>