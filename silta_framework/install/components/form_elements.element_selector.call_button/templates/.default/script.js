/*
ОПИСАНИЕ ОБЯЗАТЕЛЬНЫХ JS-МЕТОДОВ

SelementSelectorGetValue     - получить значение селктора
SelementSelectorSetValue     - установить значение селктора. Принимает аргумент "value"
SelementSelectorAlert        - установить/снять визуальную пометку селктора к заполнению. Принимает аргумент "value" = on/off
SelementSelectorCheckFielded - проверяет, заполнен ли селктор. Возвращает значение = true/false
SelementSelectorGetName      - получить имя селктора.
SelementSelectorSetName      - задать имя селктора. Принимает аргумент "value"
*/

SElementSelectorQuery        = '#silta-form-element-selector-form';
SElementSelectorElementQuery = '[silta-form-element-selector-checked-element]';
// поиск кнопки вызова по селектору
function SelementSelectorGetCallButton()
	{
	return $('#'+$(SElementSelectorQuery).find('[selector-params] input[name="call_button_id"]').val());
	}
/* ============================================================================================= */
/* =========================================== МЕТОДЫ ========================================== */
/* ============================================================================================= */
(function($)
	{
	/* -------------------------------------------------------------------- */
	/* -------------- проверить кнопку вызова на валидность --------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SelementSelectorValidation = function()
		{
		if(this.getInputType() != 'element-selector') return false;
		return true;
		};
	/* -------------------------------------------------------------------- */
	/* --------------------- поиск элементов селектора -------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SelementSelectorGetElements = function()
		{
		if(!this.SelementSelectorValidation()) return false;
		var
			$form  = this.closest('form'),
			result = $();

		if(!$form.length) $form = this.closest('table');
		if(!$form.length) $form = this.parent();

		$form.find('[name="'+this.SelementSelectorGetName()+'"]').each(function()
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
	jQuery.fn.SelementSelectorGetValue = function()
		{
		if(!this.SelementSelectorValidation()) return false;
		var result = [];

		this.SelementSelectorGetElements().each(function()
			{
			var value = $(this).find('input').val();
			if(value) result.push(value);
			});
		return result;
		};
	/* -------------------------------------------------------------------- */
	/* ----------------------- установить значение ------------------------ */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SelementSelectorSetValue = function(value)
		{
		if(!this.SelementSelectorValidation()) return;
		var
			$callButton      = this,
			$checkedElements = $callButton.SelementSelectorGetElements(),
			needToAddElement = true;
		// поиск такого элемента
		if(value)
			$checkedElements.each(function()
				{
				if(value == $(this).find('input').val())
					{
					$(this).remove();
					needToAddElement = false;
					}
				});
		// моно-селектор
		if(!value || $callButton.attr('multiply') == 'N')
			$checkedElements.remove();
		// элемент удален - завершение
		if(!value || !needToAddElement)
			{
			$callButton.SelementSelectorCheckElements();
			return;
			}
		// добавление нового элемента
		WaitingScreenOn();
		$.ajax
			({
			type    : 'POST',
			url     : SElementSelectorFolder+'/ajax/add_element.php',
			data    : 
				{
				"value"     : value,
				"input_name": $callButton.attr("input-name"),
				"table"     : $callButton.attr("table"),
				"props"     : $callButton.attr("props")
				},
			success : function(result)
				{
				$callButton.parent().append(result);
				$callButton.SelementSelectorCheckElements()
				},
			complete: function() {WaitingScreenOff()}
			});
		};
	/* -------------------------------------------------------------------- */
	/* ------------------- установить поле к заполнению ------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SelementSelectorAlert = function(value)
		{
		if(!this.SelementSelectorValidation()) return;
		if($.inArray(value, ["on", "off"]) == -1) value = 'on';

		if(value == "on")  this.attr("alert-input", true);
		if(value == "off") this.removeAttr("alert-input");
		};
	/* -------------------------------------------------------------------- */
	/* ------------------ проверить поле на заполненность ----------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SelementSelectorCheckFielded = function()
		{
		if(!this.SelementSelectorValidation())        return false;
		if(this.SelementSelectorGetElements().length) return true;
		return false;
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------- получить имя поля ------------------------ */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SelementSelectorGetName = function()
		{
		if(!this.SelementSelectorValidation()) return false;
		return this.attr("input-name");
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------- задать имя поля -------------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SelementSelectorSetName = function(value)
		{
		if(!this.SelementSelectorValidation()) return;
		this
			.attr("input-name", value)
			.attr("id", this.attr('id').replace(/\d/, Math.floor(Math.random()*(99999))));
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------- вызвать селектор ------------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SelementSelectorCallForm = function(params)
		{
		if(!this.SelementSelectorValidation()) return;
		WaitingScreenOn();
		var
			$callButton   = this,
			$selector     = $(SElementSelectorQuery),
			defaultParams =
				{
				"sorter"        : '',
				"navigator"     : '',
				"call_button_id": $callButton.attr("id"),
				"table"         : $callButton.attr("table"),
				"props"         : $callButton.attr("props"),
				"filter"        : $callButton.attr("filter")
				},
			cordinateX, cordinateY;
		// параметры
		params = $.extend(defaultParams, params);
		if($selector.length)
			{
			cordinateX = $selector.offset().left;
			cordinateY = $selector.offset().top;
			$selector.find('[selector-params] input').each(function()
				{
				var formOption = $(this).attr("name");
				if(!params[formOption]) params[formOption] = $(this).val();
				});
			}
		// ajax
		$.ajax
			({
			type    : 'POST',
			url     : SElementSelectorFolder+'/ajax/selector_form.php',
			data    : params,
			success : function(result)
				{
				$(SElementSelectorQuery).remove();
				$("body").append(result);
				var $selector = $(SElementSelectorQuery);

				if($selector.length)
					{
					$selector.draggable({handle:'[selector-hat]', containment:'parent'});
					if(cordinateX  && cordinateY) $selector.offset({left: cordinateX, top: cordinateY});
					else                          $selector.setFormPosition({"target": $callButton});
					$callButton.SelementSelectorCheckElements();
					}
				},
			complete: function() {WaitingScreenOff()}
			});
		};
	/* -------------------------------------------------------------------- */
	/* ------------- пометить добавленные элементы в селекторе ------------ */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SelementSelectorCheckElements = function()
		{
		var $selector = $(SElementSelectorQuery);
		if(!this.SelementSelectorValidation() || !$selector.length) return;

		$selector.find('[selector-content] [element-row]').removeAttr('checked');
		this.SelementSelectorGetElements().each(function()
			{
			$selector
				.find('[selector-content] [element-row="'+$(this).find('input').val()+'"]')
				.attr('checked', true);
			});
		};
	/* -------------------------------------------------------------------- */
	/* -------------------- изменить фильтр селектора --------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SelementSelectorSetFilter = function(index, value, clearFilter)
		{
		if(!this.SelementSelectorValidation()) return;
		var selectorFilter = {}, selectorFilterString = [];
		// сбор имеющегося фильтра
		if(clearFilter != 'Y')
			$.each(this.attr('filter').split(';'), function(filterIndex, filterValue)
				{
				filterValue = filterValue.split('=');
				selectorFilter[filterValue[0]] = filterValue[1];
				});
		// изменения в фильтре
		if(value)                      selectorFilter[index] = value;
		else if(selectorFilter[index]) delete selectorFilter[index];
		// запись нового фильтра
		$.each(selectorFilter, function(filterIndex, filterValue)
			{
			selectorFilterString.push(filterIndex+'='+filterValue);
			});
		this.attr('filter', selectorFilterString.join(';'));
		};
	})(jQuery);
/* ============================================================================================= */
/* ======================================== ОБРАБОТЧИКИ ======================================== */
/* ============================================================================================= */
$(function()
	{
	/* -------------------------------------------------------------------- */
	/* ------------------------ закрытие селектора ------------------------ */
	/* -------------------------------------------------------------------- */
	$(document).click(function(event)
		{
		var $clickedElement = $(event.target);
		if
			(
			$clickedElement.closest(SElementSelectorQuery).length
			||
			$clickedElement.closest(SElementSelectorElementQuery).length
			) return;

		var $selector = $(SElementSelectorQuery);
		$selector.fadeOut(300, function() {$selector.remove()});
		event.stopPropagation();
		});
	/* -------------------------------------------------------------------- */
	/* ------------------------- вызов селектора -------------------------- */
	/* -------------------------------------------------------------------- */
	$('body').on('click', '[silta-form-element="element-selector"]', function()
		{
		$(this).SelementSelectorCallForm();
		});
	/* -------------------------------------------------------------------- */
	/* ----------------- добавления элемента из селектора ----------------- */
	/* -------------------------------------------------------------------- */
	$('body').on('click', SElementSelectorQuery+' [element-row]', function()
		{
		var
			$callButton = SelementSelectorGetCallButton(),
			value       = $(this).attr("element-row");
		if($callButton && value) $callButton.SelementSelectorSetValue(value);
		});
	/* -------------------------------------------------------------------- */
	/* ------------- добавления элемента из результата поиска ------------- */
	/* -------------------------------------------------------------------- */
	$('body').on('click', SElementSelectorQuery+' [search-item]', function()
		{
		var
			$callButton = SelementSelectorGetCallButton(),
			value       = $(this).attr("search-item");
		if($callButton && value) $callButton.SelementSelectorSetValue(value);
		});
	/* -------------------------------------------------------------------- */
	/* ------------------------- удаление элемента ------------------------ */
	/* -------------------------------------------------------------------- */
	$('body').on('click', SElementSelectorElementQuery+' [delete-element]', function()
		{
		var $callButton = SelementSelectorGetCallButton();
		$(this).closest(SElementSelectorElementQuery).remove();
		if($callButton) $callButton.SelementSelectorCheckElements();
		});
	/* -------------------------------------------------------------------- */
	/* ---------------------------- сортировка ---------------------------- */
	/* -------------------------------------------------------------------- */
	$('body').on('click', SElementSelectorQuery+' [table-sorter]', function()
		{
		var $callButton = SelementSelectorGetCallButton();
		if($callButton) $callButton.SelementSelectorCallForm({"sorter": $(this).attr('table-sorter')});
		});
	/* -------------------------------------------------------------------- */
	/* ---------------------------- навигация ----------------------------- */
	/* -------------------------------------------------------------------- */
	$('body').on('click', SElementSelectorQuery+' [navigation-page="unactive"]', function()
		{
		var $callButton = SelementSelectorGetCallButton();
		if($callButton) $callButton.SelementSelectorCallForm({"navigator": $(this).html()});
		});
	/* -------------------------------------------------------------------- */
	/* -------------------------- поиск элемента -------------------------- */
	/* -------------------------------------------------------------------- */
	$('body').on('keyup', SElementSelectorQuery+' [search-cell] input', function() {setTimeout(function()
		{
		var
			$selector    = $(SElementSelectorQuery),
			$searchField = $selector.find('[search-cell] input'),
			$resultCell  = $selector.find('[search-cell]'),
			valueData    =
				{
				"table" : $selector.find('[selector-params] input[name="table"]') .val(),
				"filter": $selector.find('[selector-params] input[name="filter"]').val(),
				"props" : $selector.find('[selector-params] input[name="props"]') .val(),
				"search": $searchField.val()
				};

		if(!valueData["search"]) $resultCell.find('[search-item]').remove();
		if($searchField.attr("searching") == valueData["search"] || !valueData["search"]) return false;

		WaitingScreenOn();
		$searchField.attr("searching", valueData["search"]);
		$.ajax
			({
			type    : 'POST',
			url     : SElementSelectorFolder+'/ajax/search_element.php',
			data    : valueData,
			success : function(result)
				{
				$resultCell.find('[search-item]').remove();
				$resultCell.append(result);
				},
			complete: function() {WaitingScreenOff()}
			});
		}, 1500)});
	});