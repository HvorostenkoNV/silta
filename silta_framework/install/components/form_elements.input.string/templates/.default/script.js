/*
ОПИСАНИЕ ОБЯЗАТЕЛЬНЫХ JS-МЕТОДОВ

SinputStringGetValue     - получить значение input
SinputStringSetValue     - установить значение input. Принимает аргумент "value"
SinputStringAlert        - установить/снять визуальную пометку input к заполнению. Принимает аргумент "value" = on/off
SinputStringCheckFielded - проверяет, заполнен ли input/другие input с таким же именем. Возвращает значение = true/false
SinputStringGetName      - получить имя input.
SinputStringSetName      - задать имя input. Принимает аргумент "value"
*/

(function($)
	{
	/* -------------------------------------------------------------------- */
	/* ------------------- проверить поле на валидность ------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputStringValidation = function()
		{
		if(this.getInputType() != 'input-string') return false;
		return true;
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------ получить значение ------------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputStringGetValue = function()
		{
		if(!this.SinputStringValidation()) return false;
		return this.val();
		};
	/* -------------------------------------------------------------------- */
	/* ----------------------- установить значение ------------------------ */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputStringSetValue = function(value)
		{
		if(!this.SinputStringValidation()) return;
		if(!value) value = '';
		this.attr("value", value).val(value);
		};
	/* -------------------------------------------------------------------- */
	/* ------------------- установить поле к заполнению ------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputStringAlert = function(value)
		{
		if(!this.SinputStringValidation()) return;
		if($.inArray(value, ["on", "off"]) == -1) value = 'on';

		if(value == "on")  this.attr("alert-input", true);
		if(value == "off") this.removeAttr("alert-input");
		};
	/* -------------------------------------------------------------------- */
	/* ------------------ проверить поле на заполненность ----------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputStringCheckFielded = function()
		{
		if(!this.SinputStringValidation()) return false;
		var
			$form  = this.closest('form'),
			result = false;

		if(!$form.length) $form = this.closest('table');
		if(!$form.length) $form = this.parent();

		$form.find('[name="'+this.attr("name")+'"]').each(function()
			{
			if($(this).SinputStringValidation() && $(this).val().length)
				result = true;
			});
		return result;
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------- получить имя поля ------------------------ */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputStringGetName = function()
		{
		if(!this.SinputStringValidation()) return false;
		return this.attr("name");
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------- задать имя поля -------------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputStringSetName = function(value)
		{
		if(!this.SinputStringValidation()) return;
		this.attr("name", value);
		};
	})(jQuery);
/* -------------------------------------------------------------------- */
/* ---------------------------- ОБРАБОТЧИК ---------------------------- */
/* -------------------------------------------------------------------- */
$(function()
	{
	$('body').on('focus', '[silta-form-element="input-string"][set-mask]', function()
		{
		$(this).mask($(this).attr("set-mask"), {placeholder:"*"});
		});
	});