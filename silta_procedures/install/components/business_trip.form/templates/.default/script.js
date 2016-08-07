$(function()
	{
	// форма чтения/запись
	$('body').on
		(
		'click',
			'.sp-btr-form [edit-button],'+
			'.sp-btr-form [cancel-button]',
		function()
		{
		var
			$form          = $(this).closest('form'),
			$elementsRead  = $form.find('[edit-button], [form-type="read"]'),
			$elementsWrite = $form.find('[cancel-button], [submit-button], [form-type="write"]');

		if($(this).is('[edit-button]'))   {$elementsWrite.show();$elementsRead.hide()}
		if($(this).is('[cancel-button]')) {$elementsRead.show();$elementsWrite.hide()}
		});
	// тригер проживание Да/Нет
	$('body').on('change', '[hotel-need-triger]', function()
		{
		var
			$form             = $(this).closest("form"),
			$hotelIntervalRow = $form.getFormRow("hotel_interval"),
			value             = $form.getFormRow("hotel_need").getFormInput().getInputValue();

		if(value == 'Y') $hotelIntervalRow.show();
		if(value == 'N') $hotelIntervalRow.hide();
		});
	});