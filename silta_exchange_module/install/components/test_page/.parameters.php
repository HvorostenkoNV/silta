<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_exchange_module"))            return;
// переменные
$exchangeTypes =
	[
	"0"      => GetMessage("SEM_TP_EMPTY_LIST"),
	"import" => GetMessage("SEM_TP_EXCHANGE_IMPORT"),
	"export" => GetMessage("SEM_TP_EXCHANGE_EXPORT")
	];

if($arCurrentValues["EXCHANGE_TYPE"] == 'import') $exchangeObject = SexchangeImport::GetInstance();
if($arCurrentValues["EXCHANGE_TYPE"] == 'export') $exchangeObject = SexchangeExport::GetInstance();

$proceduresList[0] = GetMessage("SEM_TP_EMPTY_LIST");
if($exchangeObject)
	foreach($exchangeObject->GetProcedures() as $procedure => $procedureObject)
		$proceduresList[$procedure] = $procedureObject->GetOptions()["name"];
// готовый массив
$arComponentParameters =
	[
	"GROUPS" =>
		[
		"MAIN" => ["NAME" => GetMessage("SEM_TP_GROUPS_MAIN")]
		],
	"PARAMETERS" =>
		[
		"EXCHANGE_TYPE" =>
			[
			"PARENT"   => 'MAIN',
			"NAME"     => GetMessage("SEM_TP_EXCHANGE_TYPE"),
			"TYPE"     => 'LIST',
			"VALUES"   => $exchangeTypes,
			"REFRESH"  => 'Y'
			],
		"PROCEDURE" =>
			[
			"PARENT"   => 'MAIN',
			"NAME"     => GetMessage("SEM_TP_PROCEDURE"),
			"TYPE"     => 'LIST',
			"VALUES"   => $proceduresList
			]
		]
	];
?>