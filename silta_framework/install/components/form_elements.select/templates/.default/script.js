/*
ОПИСАНИЕ ОБЯЗАТЕЛЬНЫХ JS-МЕТОДОВ

SselectGetValue     - получить значение select
SselectSetValue     - установить значение select. Принимает аргумент "value"
SselectAlert        - установить/снять визуальную пометку select к заполнению. Принимает аргумент "value" = on/off
SselectCheckFielded - проверяет, заполнен ли input/другие select с таким же именем. Возвращает значение = true/false
SselectGetName      - получить имя select.
SselectSetName      - задать имя select. Принимает аргумент "value"
*/

(function($)
	{
	/* -------------------------------------------------------------------- */
	/* ------------------- проверить поле на валидность ------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SselectValidation = function()
		{
		if(this.getInputType() != 'select') return false;
		return true;
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------ получить значение ------------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SselectGetValue = function()
		{
		if(!this.SselectValidation()) return false;
		var value = this.find('option:selected').attr('value');
		if(value == 0 || !value) value = false;
		return value;
		};
	/* -------------------------------------------------------------------- */
	/* ----------------------- установить значение ------------------------ */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SselectSetValue = function(value)
		{
		if(!this.SselectValidation()) return;
		this.find('option').attr('selected', false);
		if(value) this.find('option[value="'+value+'"]').attr('selected', true);
		};
	/* -------------------------------------------------------------------- */
	/* ------------------- установить поле к заполнению ------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SselectAlert = function(value)
		{
		if(!this.SselectValidation()) return;
		if($.inArray(value, ["on", "off"]) == -1) value = 'on';

		if(value == "on")  this.parent().attr("alert-input", true);
		if(value == "off") this.parent().removeAttr("alert-input");
		};
	/* -------------------------------------------------------------------- */
	/* ------------------ проверить поле на заполненность ----------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SselectCheckFielded = function()
		{
		if(!this.SselectValidation())                 return false;
		if(this.find("option:selected").val() != '0') return true;
		return false;
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------- получить имя поля ------------------------ */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SselectGetName = function()
		{
		if(!this.SselectValidation()) return false;
		return this.attr("name");
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------- задать имя поля -------------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SselectSetName = function(value)
		{
		if(!this.SselectValidation()) return;
		this.attr("name", value);
		};
	})(jQuery);