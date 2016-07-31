$(function()
	{
	$('body').on
		(
		'click',
			'.silta-diy-module-form-edit [edit-button],'+
			'.silta-diy-module-form-edit [cancel-button]',
		function()
		{
		var
			$form          = $(this).closest('.silta-diy-module-form-edit'),
			$elementsRead  = $form.find('[edit-button], [form-type="read"]'),
			$elementsWrite = $form.find('[cancel-button], [submit-button], [form-type="write"]');

		if($(this).is('[edit-button]'))   {$elementsWrite.show();$elementsRead.hide()}
		if($(this).is('[cancel-button]')) {$elementsRead.show();$elementsWrite.hide()}
		});
	});