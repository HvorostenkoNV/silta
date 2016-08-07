/*
ОПИСАНИЕ ОБЯЗАТЕЛЬНЫХ JS-МЕТОДОВ

SinputDateGetValue     - получить значение input
SinputDateSetValue     - установить значение input. Принимает аргумент "value"
SinputDateAlert        - установить/снять визуальную пометку input к заполнению. Принимает аргумент "value" = on/off
SinputDateCheckFielded - проверяет, заполнен ли input/другие input с таким же именем. Возвращает значение = true/false
SinputDateGetName      - получить имя input.
SinputDateSetName      - задать имя input. Принимает аргумент "value"
*/

(function($)
	{
	/* -------------------------------------------------------------------- */
	/* ------------------- проверить поле на валидность ------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputDateValidation = function()
		{
		if(this.getInputType() != 'input-date') return false;
		return true;
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------ получить значение ------------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputDateGetValue = function()
		{
		if(!this.SinputDateValidation()) return false;
		return this.val();
		};
	/* -------------------------------------------------------------------- */
	/* ----------------------- установить значение ------------------------ */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputDateSetValue = function(value)
		{
		if(!this.SinputDateValidation()) return;
		if(!value) value = '';
		this.attr("value", value).val(value);
		};
	/* -------------------------------------------------------------------- */
	/* ------------------- установить поле к заполнению ------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputDateAlert = function(value)
		{
		if(!this.SinputDateValidation()) return;
		if($.inArray(value, ["on", "off"]) == -1) value = 'on';

		if(value == "on")  this.attr("alert-input", true);
		if(value == "off") this.removeAttr("alert-input");
		};
	/* -------------------------------------------------------------------- */
	/* ------------------ проверить поле на заполненность ----------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputDateCheckFielded = function()
		{
		if(!this.SinputDateValidation()) return false;
		var
			$form  = this.closest('form'),
			result = false;

		if(!$form.length) $form = this.closest('table');
		if(!$form.length) $form = this.parent();

		$form.find('[name="'+this.attr("name")+'"]').each(function()
			{
			if($(this).SinputDateValidation() && $(this).val().length)
				result = true;
			});
		return result;
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------- получить имя поля ------------------------ */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputDateGetName = function()
		{
		if(!this.SinputDateValidation()) return false;
		return this.attr("name");
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------- задать имя поля -------------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputDateSetName = function(value)
		{
		if(!this.SinputDateValidation()) return;
		this.attr("name", value);
		};
	})(jQuery);
/* -------------------------------------------------------------------- */
/* ---------------------------- ОБРАБОТЧИК ---------------------------- */
/* -------------------------------------------------------------------- */
$(function()
	{
	$('body').on('focus', '[silta-form-element="input-date"][set-date-picker]', function()
		{
		var
			needTime   = $(this).attr('time'),
			needDate   = $(this).attr('date'),
			startDate  = $(this).attr('start-date'),
			dateParams =
				{
				"format"    : 'd.m.Y',
				"datepicker": true,
				"timepicker": false,
				"minDate"   : false
				};

		if(needDate == 'Y' && needTime == 'Y') dateParams["format"]     = 'd.m.Y H:i';
		if(needDate == 'N' && needTime == 'Y') dateParams["format"]     = 'H:i';
		if(needTime == 'Y')                    dateParams["timepicker"] = true;
		if(needDate == 'N')                    dateParams["datepicker"] = false;
		if(startDate)                          dateParams["minDate"]    = startDate;

		$(this)
			.removeAttr("set-date-picker")
			.datetimepicker(dateParams);
		});
	});