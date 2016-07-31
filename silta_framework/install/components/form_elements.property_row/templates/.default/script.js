/*
ОПИСАНИЕ ОБЯЗАТЕЛЬНЫХ JS-МЕТОДОВ

getPropSaving    - получить состояние сохранения свойства при submit. Возвращает значение = on/off
setPropSaving    - установить состояние сохранения свойства при submit. Принимает аргумент "value" = on/off
getRequiredValue - получить состояние триггера "обязательно к заполнению". Возвращает значение = on/off
setRequiredValue - установить состояние триггера "обязательно к заполнению". Принимает аргумент "value" = on/off
*/

(function($)
	{
	/* -------------------------------------------------------------------- */
	/* ------------------ проверить строку на валидность ------------------ */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SpropertyRowValidation = function()
		{
		if(!this.attr("silta-form-property-row")) return false;
		return true;
		};
	/* -------------------------------------------------------------------- */
	/* -------- получить состояние сохранения свойства при submit --------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.getPropSaving = function()
		{
		if(!this.SpropertyRowValidation()) return false;
		return this.attr("form-saving");
		};
	/* -------------------------------------------------------------------- */
	/* -------- установить состояние сохранения свойства при submit ------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.setPropSaving = function(value)
		{
		if($.inArray(value, ["on", "off"]) == -1) value = 'on';
		return this.each(function()
			{
			if(!$(this).SpropertyRowValidation()) return true;
			$(this).attr("form-saving", value);
			});
		};
	/* -------------------------------------------------------------------- */
	/* ------ получить состояние триггера "обязательно к заполнению" ------ */
	/* -------------------------------------------------------------------- */
	jQuery.fn.getRequiredValue = function()
		{
		if(!this.SpropertyRowValidation()) return false;
		var $trigger = this.find('[form-required-trigger]');
		if(!$trigger.length) return false;
		return $trigger.attr("form-required-trigger");
		};
	/* -------------------------------------------------------------------- */
	/* ----- установить состояние триггера "обязательно к заполнению" ----- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.setRequiredValue = function(value)
		{
		if($.inArray(value, ["on", "off"]) == -1) value = 'on';
		return this.each(function()
			{
			if(!$(this).SpropertyRowValidation()) return true;
			var $trigger = $(this).find('[form-required-trigger]');
			if($trigger.length) $trigger.attr("form-required-trigger", value);
			});
		};
	})(jQuery);