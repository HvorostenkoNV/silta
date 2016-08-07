/*
ОПИСАНИЕ ОБЯЗАТЕЛЬНЫХ JS-МЕТОДОВ

StextareaGetValue     - получить значение textarea
StextareaSetValue     - установить значение input. Принимает аргумент "value"
StextareaAlert        - установить/снять визуальную пометку textarea к заполнению. Принимает аргумент "value" = on/off
StextareaCheckFielded - проверяет, заполнен ли input/другие textarea с таким же именем. Возвращает значение = true/false
StextareaGetName      - получить имя textarea.
StextareaSetName      - задать имя textarea. Принимает аргумент "value"
*/

(function($)
	{
	/* -------------------------------------------------------------------- */
	/* ------------------- проверить поле на валидность ------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.StextareaValidation = function()
		{
		if(this.getInputType() != 'textarea') return false;
		return true;
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------ получить значение ------------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.StextareaGetValue = function()
		{
		if(!this.StextareaValidation()) return false;
		return this.val();
		};
	/* -------------------------------------------------------------------- */
	/* ----------------------- установить значение ------------------------ */
	/* -------------------------------------------------------------------- */
	jQuery.fn.StextareaSetValue = function(value)
		{
		if(!this.StextareaValidation()) return;
		if(!value) value = '';
		this.text(value).val(value);
		};
	/* -------------------------------------------------------------------- */
	/* ------------------- установить поле к заполнению ------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.StextareaAlert = function(value)
		{
		if(!this.StextareaValidation()) return;
		if($.inArray(value, ["on", "off"]) == -1) value = 'on';

		if(value == "on")  this.attr("alert-input", true);
		if(value == "off") this.removeAttr("alert-input");
		};
	/* -------------------------------------------------------------------- */
	/* ------------------ проверить поле на заполненность ----------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.StextareaCheckFielded = function()
		{
		if(!this.StextareaValidation()) return false;
		var
			$form  = this.closest('form'),
			result = false;

		if(!$form.length) $form = this.closest('table');
		if(!$form.length) $form = this.parent();

		$form.find('[name="'+this.attr("name")+'"]').each(function()
			{
			if($(this).StextareaValidation() && $(this).val().length)
				result = true;
			});
		return result;
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------- получить имя поля ------------------------ */
	/* -------------------------------------------------------------------- */
	jQuery.fn.StextareaGetName = function()
		{
		if(!this.StextareaValidation()) return false;
		return this.attr("name");
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------- задать имя поля -------------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.StextareaSetName = function(value)
		{
		if(!this.StextareaValidation()) return;
		this.attr("name", value);
		};
	})(jQuery);