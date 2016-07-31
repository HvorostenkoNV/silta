<?
define('STOP_STATISTICS', true);
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';
$APPLICATION->RestartBuffer();

if(!CModule::IncludeModule("silta_framework") || !$_POST["search"] || !$_POST["table"]) exit();
$tableObject = new SIBlockTable(["id" => $_POST["table"]]);
if(!$tableObject) exit();
// свойства
$_POST["props"] = explode('|', $_POST["props"]);
if(!$_POST["props"][0]) $_POST["props"] = ["name"];
foreach($_POST["props"] as $property) $tableObject->SetProperty($property);
// фильтр
foreach(SgetClearArray(explode(';', $_POST["filter"])) as $string)
	{
	$explodeArray = explode(':', $string);
	if(substr_count($explodeArray[1], '/')) $explodeArray[1] = explode('/', $explodeArray[1]);
	$FILTER[$explodeArray[0]] = SgetClearArray($explodeArray[1]);
	}
$FILTER["name"] = $_POST["search"];
// выборка
$findedElements = $tableObject->GetQuery(["NAME" => 'asc'], $FILTER, ["limit" => 7]);
?>

<?foreach($findedElements as $elementId):?>
<div search-item="<?=$elementId?>">
	<?
	$APPLICATION->IncludeComponent
		(
		'silta_framework:form_elements.element_selector.element', '',
			[
			"TABLE"      => $_POST["table"],
			"PROPS"      => $_POST["props"],
			"ELEMENT_ID" => $elementId
			]
		)
	?>
</div>
<?endforeach?>