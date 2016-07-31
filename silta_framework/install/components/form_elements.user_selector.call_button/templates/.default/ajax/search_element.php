<?
define('STOP_STATISTICS', true);
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';
$APPLICATION->RestartBuffer();

if(!CModule::IncludeModule("silta_framework") || !$_POST["search"]) exit();
// фильтр
$FILTER =
	[
	"ACTIVE" => 'Y',
	"NAME"   => '%'.$_POST['search'].'%'
	];
// стартовые подразделения
foreach(SgetClearArray(explode('|', $_POST["start_roots"])) as $departmentId)
	if($departmentId)
		foreach((new SCompanyDepartment(["id" => $departmentId]))->GetUsers("full") as $userId)
			$FILTER["ID"][] = $userId;
if($FILTER["ID"][0]) $FILTER["ID"] = implode('|', $FILTER["ID"]);
// выборка
$userList = CUser::GetList($by = 'ID', $order = 'asc', $FILTER, ["FIELDS" => ["ID", "NAME", "LAST_NAME"]]);
while($user = $userList->GetNext()) $RESULT[$user["ID"]] = $user["LAST_NAME"].' '.$user["NAME"];
?>

<?foreach($RESULT as $userId => $userTitle):?>
<div search-item="user|<?=$userId?>"><?=$userTitle?></div>
<?endforeach?>