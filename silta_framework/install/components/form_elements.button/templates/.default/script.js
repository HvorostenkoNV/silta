$(function()
	{
	/* -------------------------------------------------------------------- */
	/* --------------------------- кнопка SUBMIT -------------------------- */
	/* -------------------------------------------------------------------- */
	$('body').on('click', '.silta-form-button[silta-form-submit-button]:not([form-validation-check], [reask-action])', function()
		{
		WaitingScreenOn();
		});
	/* -------------------------------------------------------------------- */
	/* -------------------- проверка формы на пустоту --------------------- */
	/* -------------------------------------------------------------------- */
	$('body').on('click', '.silta-form-button[form-validation-check]:not([reask-action])', function()
		{
		var
			$form      = $(this).closest('form'),
			textsArray = $(this).attr("form-validation-check").split("|");
		if(!$form.length) return true;
		// форма не заполнена - вывод сообщения
		if(!$form.checkFormFielded())
			{
			CallAlertWindow({"text": textsArray[0], "closeButtonText": textsArray[1]});
			return false;
			}
		// удаление не рабочих свойств формы
		WaitingScreenOn();
		$form.getFormRow().each(function()
			{
			if($(this).getPropSaving() == 'off')
				$(this).remove();
			});
		// submit
		return true;
		});
	/* -------------------------------------------------------------------- */
	/* ----------------------- кнопка подтверждения ----------------------- */
	/* -------------------------------------------------------------------- */
	$('body').on('click', '.silta-form-button[reask-action]', function()
		{
		var
			name       = $(this).attr("name"),
			textsArray = $(this).attr("reask-action").split("|");
		if(!name) return;

		CallAlertWindow
			({
			"text"           : textsArray[0],
			"applyButtonText": textsArray[1],
			"closeButtonText": textsArray[2],
			"applyButtonAttr": 'silta-reask-button-apply="'+name+'"'
			});
		return false;
		});

	$('body').on('click', '[silta-reask-button-apply]', function()
		{
		var $button = $('[name="'+$(this).attr("silta-reask-button-apply")+'"]');
		if(!$button) return;

		AlertWindowOff();
		WaitingScreenOn();
		$button
			.removeAttr("form-validation-check")
			.removeAttr("reask-action")
			.click();
		});
	});