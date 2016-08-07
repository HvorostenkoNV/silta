/*
ОПИСАНИЕ ОБЯЗАТЕЛЬНЫХ JS-МЕТОДОВ

SinputFileGetValue     - получить значение input
SinputFileSetValue     - установить значение input. Принимает аргумент "value"
SinputFileAlert        - установить/снять визуальную пометку input к заполнению. Принимает аргумент "value" = on/off
SinputFileCheckFielded - проверяет, заполнен ли input/другие input с таким же именем. Возвращает значение = true/false
SinputFileGetName      - получить имя input.
SinputFileSetName      - задать имя input. Принимает аргументы "name" и "nameUploaded"
*/

(function($)
	{
	/* -------------------------------------------------------------------- */
	/* ------------------- проверить поле на валидность ------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputFileValidation = function()
		{
		if(this.getInputType() != 'input-file') return false;
		return true;
		};
	/* -------------------------------------------------------------------- */
	/* --------------------- поиск элементов селектора -------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputFileGetElements = function()
		{
		if(!this.SinputFileValidation()) return false;
		var
			$form  = this.closest('form'),
			result = $();

		if(!$form.length) $form = this.closest('table');
		if(!$form.length) $form = this.parent();

		$form.find('[name="'+this.attr("input-name-uploaded")+'"]').each(function()
			{
			var $element = $(this).parent();
			if(!result) result = $element;
			else        result = $(result).add($element);
			});
		return result;
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------ получить значение ------------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputFileGetValue = function()
		{
		if(!this.SinputFileValidation()) return false;
		var result = [];

		this.SinputFileGetElements().each(function()
			{
			result.push($(this).find('input').val());
			});
		return result;
		};
	/* -------------------------------------------------------------------- */
	/* ----------------------- установить значение ------------------------ */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputFileSetValue = function(value)
		{
		if(!this.SinputFileValidation()) return;
		if(!value) this.SinputFileGetElements().remove();
		};
	/* -------------------------------------------------------------------- */
	/* ------------------- установить поле к заполнению ------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputFileAlert = function(value)
		{
		if(!this.SinputFileValidation()) return;
		if($.inArray(value, ["on", "off"]) == -1) value = 'on';

		if(value == "on")  this.attr("alert-input", true);
		if(value == "off") this.removeAttr("alert-input");
		};
	/* -------------------------------------------------------------------- */
	/* ------------------ проверить поле на заполненность ----------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputFileCheckFielded = function()
		{
		if(!this.SinputFileValidation())        return false;
		if(this.SinputFileGetElements().length) return true;

		var
			$form  = this.closest('form'),
			result = false;

		if(!$form.length) $form = this.closest('table');
		if(!$form.length) $form = this.parent();

		$form.find('[name="'+this.attr("name")+'"]').each(function()
			{
			if($(this).val().length)
				result = true;
			});
		return result;
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------- получить имя поля ------------------------ */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputFileGetName = function()
		{
		if(!this.SinputFileValidation()) return false;
		return this.attr("name");
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------- задать имя поля -------------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SinputFileSetName = function(name, nameUploaded)
		{
		if(!this.SinputFileValidation()) return;
		this
			.attr("name", name)
			.attr("input-name-uploaded", nameUploaded)
			.SinputFileGetElements().each(function()
				{
				this.find('input').attr("name", nameUploaded);
				});
		};
	})(jQuery);