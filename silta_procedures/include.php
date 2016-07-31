<?
if(!CModule::IncludeModule("silta_framework")) return ShowError('silta_framework not instaled');
IncludeModuleLangFile(__FILE__);

$class_folder = 'classes/general/';
CModule::AddAutoloadClasses
	(
	"silta_procedures",
		[
		"SCompanyProcedures"         => $class_folder.'company_procedures.php',
		"SProceduresFixedAssetsWork" => $class_folder.'fixed_assets_work.php',
		"SProceduresBusinessTrip"    => $class_folder.'business_trip.php',

		"SProceduresFAWProvisionApplicationTable"    => $class_folder.'tables/provision_application.php',
		"SProceduresFAWDisplacementApplicationTable" => $class_folder.'tables/displacement_application.php',
		"SProceduresFAWPurchaseApplicationTable"     => $class_folder.'tables/purchase_application.php',
		"SProceduresFAWWriteOffApplicationTable"     => $class_folder.'tables/write_off_application.php',
		"SProceduresFAWCommentsTable"                => $class_folder.'tables/comments.php',

		"SProceduresFAWProvisionApplicationElement"    => $class_folder.'elements/provision_application.php',
		"SProceduresFAWDisplacementApplicationElement" => $class_folder.'elements/displacement_application.php',
		"SProceduresFAWPurchaseApplicationElement"     => $class_folder.'elements/purchase_application.php',
		"SProceduresFAWWriteOffApplicationElement"     => $class_folder.'elements/write_off_application.php',
		"SProceduresFAWCommentsElement"                => $class_folder.'elements/comments.php',

		"SProceduresBusinessTripTable"   => $class_folder.'tables/business_trip.php',
		"SProceduresBusinessTripElement" => $class_folder.'elements/business_trip.php',
		]
	);
?>