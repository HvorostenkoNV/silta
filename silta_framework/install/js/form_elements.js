/*
каждый элемент формы из silta_module имеет аттрибут silta-form-element, который равен типу элемента формы
строки формы, построенные компонентами silta_module имеют аттрибут silta-form-property-row
строчные значения свойств(на чтение) имеют аттрибут silta-form-value-read
*/

(function($)
	{
	/* -------------------------------------------------------------------- */
	/* ----------------------- получить строку формы ---------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.getFormRow = function(value)
		{
		if(!value) return this.find('[silta-form-property-row]');
		return this.find('[silta-form-property-row="'+value+'"]');
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------ получить поле формы ----------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.getFormInput = function()
		{
		return this.find('[silta-form-element]');
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------- получить тип поля ------------------------ */
	/* -------------------------------------------------------------------- */
	jQuery.fn.getInputType = function()
		{
		return this.attr("silta-form-element");
		};
	/* -------------------------------------------------------------------- */
	/* ---------------------- получить значение поля ---------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.getInputValue = function()
		{
		var
			$input    = this,
			inputType = $input.getInputType();
		if(!inputType) return false;

		if(inputType == 'input-string')     return $input.SinputStringGetValue();
		if(inputType == 'input-number')     return $input.SinputNumberGetValue();
		if(inputType == 'input-date')       return $input.SinputDateGetValue();
		if(inputType == 'input-file')       return $input.SinputFileGetValue();
		if(inputType == 'textarea')         return $input.StextareaGetValue();
		if(inputType == 'checkbox')         return $input.ScheckboxGetValue();
		if(inputType == 'select')           return $input.SselectGetValue();
		if(inputType == 'element-selector') return $input.SelementSelectorGetValue();
		if(inputType == 'user-selector')    return $input.SuserSelectorGetValue();
		};
	/* -------------------------------------------------------------------- */
	/* ----------------------- задать значение поля ----------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.setInputValue = function(value)
		{
		return this.each(function()
			{
			var
				$input    = $(this),
				inputType = $input.getInputType();
			if(!inputType) return true;

			if(inputType == 'input-string')     $input.SinputStringSetValue(value);
			if(inputType == 'input-number')     $input.SinputNumberSetValue(value);
			if(inputType == 'input-date')       $input.SinputDateSetValue(value);
			if(inputType == 'input-file')       $input.SinputFileSetValue(value);
			if(inputType == 'textarea')         $input.StextareaSetValue(value);
			if(inputType == 'checkbox')         $input.ScheckboxSetValue(value);
			if(inputType == 'select')           $input.SselectSetValue(value);
			if(inputType == 'element-selector') $input.SelementSelectorSetValue(value);
			if(inputType == 'user-selector')    $input.SuserSelectorSetValue(value);
			});
		};
	/* -------------------------------------------------------------------- */
	/* -------------------- пометить поле к заполнению -------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.setInputAlert = function(value)
		{
		return this.each(function()
			{
			var
				$input    = $(this),
				inputType = $input.getInputType();
			if(!inputType) return true;

			if(inputType == 'input-string')     $input.SinputStringAlert(value);
			if(inputType == 'input-number')     $input.SinputNumberAlert(value);
			if(inputType == 'input-date')       $input.SinputDateAlert(value);
			if(inputType == 'input-file')       $input.SinputFileAlert(value);
			if(inputType == 'textarea')         $input.StextareaAlert(value);
			if(inputType == 'checkbox')         $input.ScheckboxAlert(value);
			if(inputType == 'select')           $input.SselectAlert(value);
			if(inputType == 'element-selector') $input.SelementSelectorAlert(value);
			if(inputType == 'user-selector')    $input.SuserSelectorAlert(value);
			});
		};
	/* -------------------------------------------------------------------- */
	/* ------------------- проверить поле на заполнение ------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.checkInputFielded = function()
		{
		var
			$input    = this,
			inputType = $input.getInputType();
		if(!inputType) return false;

		if(inputType == 'input-string')     return $input.SinputStringCheckFielded();
		if(inputType == 'input-number')     return $input.SinputNumberCheckFielded();
		if(inputType == 'input-date')       return $input.SinputDateCheckFielded();
		if(inputType == 'input-file')       return $input.SinputFileCheckFielded();
		if(inputType == 'textarea')         return $input.StextareaCheckFielded();
		if(inputType == 'checkbox')         return $input.ScheckboxCheckFielded();
		if(inputType == 'select')           return $input.SselectCheckFielded();
		if(inputType == 'element-selector') return $input.SelementSelectorCheckFielded();
		if(inputType == 'user-selector')    return $input.SuserSelectorCheckFielded();
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------ получить имя поля ------------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.getInputName = function()
		{
		var
			$input    = this,
			inputType = $input.getInputType();
		if(!inputType) return false;

		if(inputType == 'input-string')     return $input.SinputStringGetName();
		if(inputType == 'input-number')     return $input.SinputNumberGetName();
		if(inputType == 'input-date')       return $input.SinputDateGetName();
		if(inputType == 'input-file')       return $input.SinputFileGetName();
		if(inputType == 'textarea')         return $input.StextareaGetName();
		if(inputType == 'checkbox')         return $input.ScheckboxGetName();
		if(inputType == 'select')           return $input.SselectGetName();
		if(inputType == 'element-selector') return $input.SelementSelectorGetName();
		if(inputType == 'user-selector')    return $input.SuserSelectorGetName();
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------- задать имя поля -------------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.setInputName = function(value)
		{
		return this.each(function()
			{
			var
				$input    = $(this),
				inputType = $input.getInputType();
			if(!inputType) return true;

			if(inputType == 'input-string')     $input.SinputStringSetName(value);
			if(inputType == 'input-number')     $input.SinputNumberSetName(value);
			if(inputType == 'input-date')       $input.SinputDateSetName(value);
			if(inputType == 'input-file')       $input.SinputFileSetName(value+'[new][]', value+'[uploaded][]');
			if(inputType == 'textarea')         $input.StextareaSetName(value);
			if(inputType == 'checkbox')         $input.ScheckboxSetName(value);
			if(inputType == 'select')           $input.SselectSetName(value);
			if(inputType == 'element-selector') $input.SelementSelectorSetName(value);
			if(inputType == 'user-selector')    $input.SuserSelectorSetName(value);
			});
		};
	/* -------------------------------------------------------------------- */
	/* -------------------------- очистка формы --------------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.clearForm = function()
		{
		this.getFormInput().each(function()
			{
			var inputValue = false;
			if($(this).getInputType() == 'checkbox') inputValue = 'off';
			$(this).setInputValue(inputValue).setInputAlert("off");
			});

		this.getFormRow().each(function()
			{
			$(this)
				.setPropSaving("on")
				.setRequiredValue("on")
				.find('[silta-form-value-read]').remove();
			});
		};
	/* -------------------------------------------------------------------- */
	/* --------------------- проверка формы на пустоту -------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.checkFormFielded = function()
		{
		var result = true;
		this.getFormInput().setInputAlert("off");
		this.getFormRow().each(function()
			{
			if($(this).getRequiredValue() == 'on')
				$(this).getFormInput().each(function()
					{
					if(!$(this).checkInputFielded())
						{
						$(this).setInputAlert("on");
						result = false;
						}
					});
			});
		return result;
		};
	})(jQuery);