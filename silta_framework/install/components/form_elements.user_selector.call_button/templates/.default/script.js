/*
ОПИСАНИЕ ОБЯЗАТЕЛЬНЫХ JS-МЕТОДОВ

SuserSelectorGetValue     - получить значение селктора. Возвращает асоциотивный массив с ключами users/department.
SuserSelectorSetValue     - установить значение селктора. Принимает аргумент "value"
SuserSelectorAlert        - установить/снять визуальную пометку селктора к заполнению. Принимает аргумент "value" = on/off
SuserSelectorCheckFielded - проверяет, заполнен ли селктор. Возвращает значение = true/false
SuserSelectorGetName      - получить имя селктора.
SuserSelectorSetName      - задать имя селктора. Принимает аргумент "value"
*/

SUserSelectorQuery        = '#silta-form-user-selector-form';
SUserSelectorElementQuery = '[silta-form-user-selector-checked-element]';
// поиск кнопки вызова по селектору
function SuserSelectorGetCallButton()
	{
	return $('#'+$(SUserSelectorQuery).find('[selector-params] input[name="call_button_id"]').val());
	}
/* ============================================================================================= */
/* =========================================== МЕТОДЫ ========================================== */
/* ============================================================================================= */
(function($)
	{
	/* -------------------------------------------------------------------- */
	/* -------------- проверить кнопку вызова на валидность --------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SuserSelectorValidation = function()
		{
		if(this.getInputType() != 'user-selector') return false;
		return true;
		};
	/* -------------------------------------------------------------------- */
	/* --------------------- поиск элементов селектора -------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SuserSelectorGetElements = function()
		{
		if(!this.SuserSelectorValidation()) return false;
		var
			$form  = this.closest('form'),
			result = $();

		if(!$form.length) $form = this.closest('table');
		if(!$form.length) $form = this.parent();

		$form.find('[name="'+this.SuserSelectorGetName()+'"]').each(function()
			{
			var $element = $(this).closest(SUserSelectorElementQuery);
			if(!result) result = $element;
			else        result = $(result).add($element);
			});
		return result;
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------ получить значение ------------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SuserSelectorGetValue = function()
		{
		if(!this.SuserSelectorValidation()) return false;
		var result = {"users": [], "departments": []};

		this.SuserSelectorGetElements().each(function()
			{
			var value = $(this).find('input').val().split('|'), arrayIndex;
			     if(value[0] == 'user')       arrayIndex = 'users';
			else if(value[0] == 'department') arrayIndex = 'departments';
			if(value[1] && arrayIndex) result[arrayIndex].push(value[1]);
			});
		return result;
		};
	/* -------------------------------------------------------------------- */
	/* ----------------------- установить значение ------------------------ */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SuserSelectorSetValue = function(value)
		{
		if(!this.SuserSelectorValidation()) return;
		var
			$callButton      = this,
			$checkedElements = $callButton.SuserSelectorGetElements(),
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
			$callButton.SuserSelectorCheckElements();
			return;
			}
		// добавление нового элемента
		WaitingScreenOn();
		$.ajax
			({
			type    : 'POST',
			url     : SUserSelectorFolder+'/ajax/add_element.php',
			data    : 
				{
				"value"     : value,
				"input_name": $callButton.attr("input-name")
				},
			success : function(resalt)
				{
				$callButton.parent().append(resalt);
				$callButton.SuserSelectorCheckElements();
				},
			complete: function() {WaitingScreenOff()}
			});
		};
	/* -------------------------------------------------------------------- */
	/* ------------------- установить поле к заполнению ------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SuserSelectorAlert = function(value)
		{
		if(!this.SuserSelectorValidation()) return;
		if($.inArray(value, ["on", "off"]) == -1) value = 'on';

		if(value == "on")  this.attr("alert-input", true);
		if(value == "off") this.removeAttr("alert-input");
		};
	/* -------------------------------------------------------------------- */
	/* ------------------ проверить поле на заполненность ----------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SuserSelectorCheckFielded = function()
		{
		if(!this.SuserSelectorValidation())        return false;
		if(this.SuserSelectorGetElements().length) return true;
		return false;
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------- получить имя поля ------------------------ */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SuserSelectorGetName = function()
		{
		if(!this.SuserSelectorValidation()) return false;
		return this.attr("input-name");
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------- задать имя поля -------------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SuserSelectorSetName = function(value)
		{
		if(!this.SuserSelectorValidation()) return;
		$(this)
			.attr("input-name", value)
			.attr("id", $(this).attr('id').replace(/\d/, Math.floor(Math.random()*(99999))));
		};
	/* -------------------------------------------------------------------- */
	/* ------------------------- вызвать селектор ------------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SuserSelectorCallForm = function()
		{
		if(!this.SuserSelectorValidation()) return;
		WaitingScreenOn();
		// переменные
		var
			$callButton = this,
			$selector   = $(SUserSelectorQuery),
			cordinateX, cordinateY;

		if($selector.length)
			{
			cordinateX = $selector.offset().left;
			cordinateY = $selector.offset().top;
			}
		// ajax
		$.ajax
			({
			type    : 'POST',
			url     : SUserSelectorFolder+'/ajax/selector_form.php',
			data    :
				{
				"call_button_id": $callButton.attr("id"),
				"start_roots"   : $callButton.attr("start-roots"),
				"users"         : $callButton.attr("users"),
				"departments"   : $callButton.attr("departments")
				},
			success : function(resalt)
				{
				$(SUserSelectorQuery).remove();
				$("body").append(resalt);
				var $selector = $(SUserSelectorQuery);

				if($selector.length)
					{
					$selector.draggable({handle:'[selector-hat]', containment:'parent'});
					if(cordinateX  && cordinateY) $selector.offset({left: cordinateX, top: cordinateY});
					else                          $selector.setFormPosition({"target": $callButton});
					$callButton.SuserSelectorCheckElements();
					}
				},
			complete: function() {WaitingScreenOff()}
			});
		};
	/* -------------------------------------------------------------------- */
	/* ------------- пометить добавленные элементы в селекторе ------------ */
	/* -------------------------------------------------------------------- */
	jQuery.fn.SuserSelectorCheckElements = function()
		{
		var $selector = $(SUserSelectorQuery);
		if(!this.SuserSelectorValidation() || !$selector.length) return;

		$selector.find('h3[checked], li[checked]').removeAttr('checked');
		$selector.find('[selector-content] ul').hide();
		this.SuserSelectorGetElements().each(function()
			{
			$selector
				.find('[item-value="'+$(this).find('input').attr('value')+'"]')
					.attr('checked', true)
				.parents('ul').show()
				.prev('h3').attr('checked', true);
			});
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
			$clickedElement.closest(SUserSelectorQuery).length
			||
			$clickedElement.closest(SUserSelectorElementQuery).length
			) return;

		var $selector = $(SUserSelectorQuery);
		$selector.fadeOut(300, function() {$selector.remove()});
		event.stopPropagation();
		});
	/* -------------------------------------------------------------------- */
	/* ------------------------- вызов селектора -------------------------- */
	/* -------------------------------------------------------------------- */
	$('body').on('click', '[silta-form-element="user-selector"]', function()
		{
		$(this).SuserSelectorCallForm();
		});
	/* -------------------------------------------------------------------- */
	/* ----------------------- развертывание отделов ---------------------- */
	/* -------------------------------------------------------------------- */
	$('body').on('click', SUserSelectorQuery+' [selector-content] h3', function()
		{
		if($(this).attr("checked"))
			{
			$(this).next().slideUp();
			$(this).removeAttr("checked");
			}
		else
			{
			$(this).next().slideDown();
			$(this).attr("checked", true);
			}
		});
	/* -------------------------------------------------------------------- */
	/* ----------------- добавления элемента из селектора ----------------- */
	/* -------------------------------------------------------------------- */
	$('body').on('click', SUserSelectorQuery+' [selector-content] li', function()
		{
		var
			$callButton = SuserSelectorGetCallButton(),
			value       = $(this).attr("item-value");
		if($callButton && value) $callButton.SuserSelectorSetValue(value);
		});
	/* -------------------------------------------------------------------- */
	/* ------------- добавления элемента из результата поиска ------------- */
	/* -------------------------------------------------------------------- */
	$('body').on('click', SUserSelectorQuery+' [search-item]', function()
		{
		var
			$callButton = SuserSelectorGetCallButton(),
			value       = $(this).attr("search-item");
		if($callButton && value) $callButton.SuserSelectorSetValue(value);
		});
	/* -------------------------------------------------------------------- */
	/* ------------------------- удаление элемента ------------------------ */
	/* -------------------------------------------------------------------- */
	$('body').on('click', SUserSelectorElementQuery+' [delete-element]', function()
		{
		var $callButton = SuserSelectorGetCallButton();
		$(this).closest(SUserSelectorElementQuery).remove();
		if($callButton) $callButton.SuserSelectorCheckElements();
		});
	/* -------------------------------------------------------------------- */
	/* -------------------------- поиск элемента -------------------------- */
	/* -------------------------------------------------------------------- */
	$('body').on('keyup', SUserSelectorQuery+' [selector-foot] input', function() {setTimeout(function()
		{
		var
			$selector    = $(SUserSelectorQuery),
			$searchField = $selector.find('[selector-foot] input'),
			$resultCell  = $selector.find('[selector-foot]'),
			valueData    =
				{
				"start_roots": $selector.find('[selector-params] input[name="start_roots"]').val(),
				"search"     : $searchField.val()
				};

		if(!valueData["search"]) $resultCell.find('[search-item]').remove();
		if($searchField.attr("searching") == valueData["search"] || !valueData["search"]) return false;

		WaitingScreenOn();
		$searchField.attr("searching", valueData["search"]);
		$.ajax
			({
			type    : 'POST',
			url     : SUserSelectorFolder+'/ajax/search_element.php',
			data    : valueData,
			success : function(resalt)
				{
				$resultCell.find('[search-item]').remove();
				$resultCell.append(resalt);
				},
			complete: function() {WaitingScreenOff()}
			});
		}, 1500)});
	});