(function($)
	{
	/* -------------------------------------------------------------------- */
	/* --------------------- копирование DOM элемента --------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.getCopy = function()
		{
		return this.clone().wrap('<div>').parent().html();
		};
	/* -------------------------------------------------------------------- */
	/* ---------------------- позиционирование формы ---------------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.setFormPosition = function(options)
		{
		return this.each(function()
			{
			var
				settings = $.extend(
					{
					'target': ''
					}, options),
				screenWidth  = document.body.clientWidth,
				screenHeight = document.body.clientHeight,
				scrollTop    = $(window).scrollTop(),
				scrollLeft   = $(window).scrollLeft(),
				formWidth    = $(this).width(),
				formHeight   = $(this).height(),
				cordinateX, cordinateY;
			/* ----------------------------------------- */
			/* ------------ рядом с объектом ----------- */
			/* ----------------------------------------- */
			if(settings["target"])
				{
				var
					targetCordinateX = settings["target"].offset().left,
					targetCordinateY = settings["target"].offset().top,
					targetWidth      = settings["target"].width();

				if((scrollLeft + screenWidth - targetCordinateX - targetWidth - 25) > formWidth)
					cordinateX = targetCordinateX + targetWidth + 25;
				else
					{
					if((targetCordinateX - 25) > formWidth) cordinateX = targetCordinateX - formWidth - 25;
					else                                    cordinateX = scrollLeft + 25;
					}

				if((scrollTop + screenHeight - targetCordinateY - 25) > formHeight)
					cordinateY = targetCordinateY;
				else
					{
					if((formHeight + 25) < screenHeight) cordinateY = scrollTop + screenHeight - formHeight - 25;
					else                                 cordinateY = scrollTop + 25;
					}
				}
			/* ----------------------------------------- */
			/* --------------- по центру --------------- */
			/* ----------------------------------------- */
			if(!settings["target"])
				{
				cordinateX = scrollLeft + (screenWidth - formWidth)/2;

				if(formHeight < screenHeight) cordinateY = scrollTop + (screenHeight - formHeight)/2;
				else                          cordinateY = scrollTop + 150;
				}

			$(this).offset({left: cordinateX, top: cordinateY});
			});
		};
	/* -------------------------------------------------------------------- */
	/* ------------------ получить все аттрибуты элемнета ----------------- */
	/* -------------------------------------------------------------------- */
	jQuery.fn.getElementAttributes = function(resultType)
		{
		var
			attributes            = this.get(0).attributes,
			attributesArray       = {},
			attributesStringArray = [];
		if($.inArray(resultType, ["string", "array"]) == -1) resultType = 'string';

		for(var key in attributes)
			if(attributes[key].nodeName != undefined)
				attributesArray[attributes[key].name] = attributes[key].value;

		if(resultType == 'array')
			return attributesArray;
		if(resultType == 'string')
			{
			$.each(attributesArray, function(index, value)
				{
				var string = index;
				if(value) string += '='+value;
				attributesStringArray.push(string);
				});
			return attributesStringArray.join(' ');
			}
		};
	})(jQuery);