/*
ОПИСАНИЕ ОБЯЗАТЕЛЬНЫХ JS-МЕТОДОВ
ВАЖНО! input должен иметь аттрибут silta-form-element="input-number" !

SinputNumberGetValue     - получить значение input
SinputNumberSetValue     - установить значение input. Принимает аргумент "value"
SinputNumberAlert        - установить/снять визуальную пометку input к заполнению. Принимает аргумент "value" = on/off
SinputNumberCheckFielded - проверяет, заполнен ли input/другие input с таким же именем. Возвращает значение = true/false
SinputNumberGetName      - получить имя input.
SinputNumberSetName      - задать имя input. Принимает аргумент "value"
*/

(function($)
	{
	/* -------------------------------------------------------------------- */
	/* ------------------- проверить поле на валидность ------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputNumberValidation = function()
		{
		if(this.getInputType() != 'input-number') return false;
		return true;
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------ получить значение ------------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputNumberGetValue = function()
		{
		if(!this.SinputNumberValidation()) return false;
		return this.val();
		};
	/* -------------------------------------------------------------------- */
	/* ----------------------- установить значение ------------------------ */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputNumberSetValue = function(value)
		{
		if(!this.SinputNumberValidation()) return;
		if(!value) value = '';
		this.attr("value", value).val(value);
		};
	/* -------------------------------------------------------------------- */
	/* ------------------- установить поле к заполнению ------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputNumberAlert = function(value)
		{
		if(!this.SinputNumberValidation()) return;
		if($.inArray(value, ["on", "off"]) == -1) value = 'on';

		if(value == "on")  this.attr("alert-input", true);
		if(value == "off") this.removeAttr("alert-input");
		};
	/* -------------------------------------------------------------------- */
	/* ------------------ проверить поле на заполненность ----------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputNumberCheckFielded = function()
		{
		if(!this.SinputNumberValidation()) return false;
		var
			$form  = this.closest('form')
			result = false;

		if(!$form.length) $form = this.closest('table');
		if(!$form.length) $form = this.parent();

		$form.find('[name="'+this.attr("name")+'"]').each(function()
			{
			if($(this).SinputNumberValidation() && $(this).val().length)
				result = true;
			});
		return result;
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------- получить имя поля ------------------------ */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputNumberGetName = function()
		{
		if(!this.SinputNumberValidation()) return false;
		return this.attr("name");
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------- задать имя поля -------------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputNumberSetName = function(value)
		{
		if(!this.SinputNumberValidation()) return;
		this.attr("name", value);
		};
	})(jQuery);
/* -------------------------------------------------------------------- */
/* ---------------------------- ОБРАБОТЧИК ---------------------------- */
/* -------------------------------------------------------------------- */
$(function()
	{
	$('body').on('change keyup input click', '[silta-form-element="input-number"]', function()
		{
		var value = $(this).val().replace(/[^0-9]/g, '');
		$(this).attr("value", value).val(value);
		});
	});