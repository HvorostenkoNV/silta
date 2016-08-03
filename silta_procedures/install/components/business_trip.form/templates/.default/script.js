$(function()
	{
	$('body').on
		(
		'click',
			'sp-btr-form [edit-button],'+
			'sp-btr-form [cancel-button]',
		function()
		{
		var
			$form          = $(this).closest('form'),
			$elementsRead  = $form.find('[edit-button], [form-type="read"]'),
			$elementsWrite = $form.find('[cancel-button], [submit-button], [form-type="write"]');

		if($(this).is('[edit-button]'))   {$elementsWrite.show();$elementsRead.hide()}
		if($(this).is('[cancel-button]')) {$elementsRead.show();$elementsWrite.hide()}
		});
	});