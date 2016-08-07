/*
ОПИСАНИЕ ОБЯЗАТЕЛЬНЫХ JS-МЕТОДОВ

ScheckboxGetValue     - получить состояние чекбокса. Возвращает значение = on/off
ScheckboxSetValue     - установить/снять чекбокс. Принимает аргумент "value" = on/off
ScheckboxAlert        - установить/снять визуальную пометку чекбокса к заполнению. Принимает аргумент "value" = on/off
ScheckboxCheckFielded - проверяет, заполнен ли чекбокс/другие чекбоксы с таким же именем. Возвращает значение = true/false
ScheckboxGetName      - получить имя чекбокса.
ScheckboxSetName      - задать имя чекбокса. Принимает аргумент "value"
*/
(function($)
	{
	/* -------------------------------------------------------------------- */
	/* ------------------- проверить поле на валидность ------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.ScheckboxValidation = function()
		{
		if(this.getInputType() != 'checkbox') return false;
		return true;
		};
	/* -------------------------------------------------------------------- */
	/* -------------------- получить стилевой элемент --------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.ScheckboxGetStyleElement = function()
		{
		return this.next('.silta-form-checkbox');
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------ получить значение ------------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.ScheckboxGetValue = function()
		{
		if(!this.ScheckboxValidation()) return false;
		if(this.prop("checked"))        return 'on';
		else                            return 'off';
		};
	/* -------------------------------------------------------------------- */
	/* ----------------------- установить значение ------------------------ */
	/* -------------------------------------------------------------------- */
	jQuery.fn.ScheckboxSetValue = function(value)
		{
		if(!this.ScheckboxValidation()) return;
		if($.inArray(value, ["on", "off"]) == -1) value = 'on';

		if(value == "on")  this.prop("checked", true) .add(this.ScheckboxGetStyleElement()).attr("checked", true);
		if(value == "off") this.prop("checked", false).add(this.ScheckboxGetStyleElement()).removeAttr("checked");
		};
	/* -------------------------------------------------------------------- */
	/* ------------------- установить поле к заполнению ------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.ScheckboxAlert = function(value)
		{
		if(!this.ScheckboxValidation()) return;
		if($.inArray(value, ["on", "off"]) == -1) value = 'on';

		var $styledCheckbox = this.ScheckboxGetStyleElement();
		if(value == "on")  $styledCheckbox.attr("alert-checkbox", true);
		if(value == "off") $styledCheckbox.removeAttr("alert-checkbox");
		};
	/* -------------------------------------------------------------------- */
	/* ------------------ проверить поле на заполненность ----------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.ScheckboxCheckFielded = function()
		{
		if(!this.ScheckboxValidation()) return false;
		              var $form = this.closest('form');
		if(!$form.length) $form = this.closest('table');
		if(!$form.length) $form = this.parent();

		if($form.find(':checkbox[name="'+this.attr("name")+'"]:checked').length) return true;
		return false;
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------- получить имя поля ------------------------ */
	/* -------------------------------------------------------------------- */
	jQuery.fn.ScheckboxGetName = function()
		{
		if(!this.ScheckboxValidation()) return false;
		return this.attr("name");
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------- задать имя поля -------------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.ScheckboxSetName = function(value)
		{
		if(!this.ScheckboxValidation()) return;
		this.attr("name", value);
		};
	})(jQuery);
/* -------------------------------------------------------------------- */
/* ---------------------------- ОБРАБОТЧИК ---------------------------- */
/* -------------------------------------------------------------------- */
$(function()
	{
	$('body').on('click', '.silta-form-checkbox', function()
		{
		var
			$checkbox = $(this).prev(':checkbox'),
			value     = 'on';

		if($checkbox.attr("checked")) value = 'off';
		$checkbox.ScheckboxSetValue(value);
		$checkbox.trigger("change");
		});
	});