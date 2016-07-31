(function($)
	{
	jQuery.fn.tableElementsFixed = function(params)
		{
		/* -------------------------------------------------------------------- */
		/* ---------------------------- параметры ----------------------------- */
		/* -------------------------------------------------------------------- */
		var defaults =
			{
			head  : false,
			foot  : false,
			left  : 0,
			right : 0,
			zIndex: 10
			};
		params = $.extend(defaults, params);
		/* -------------------------------------------------------------------- */
		/* ------------------------ установка изменений ----------------------- */
		/* -------------------------------------------------------------------- */
		return this.each(function()
			{
			params.workTable   = $(this);
			params.tableParent = params.workTable.parent();

			params.workTable.width(params.workTable.width());
			if(params.head) createGhostHead();
			if(params.foot) createGhostFoot();
			/* ------------------------------------------- */
			/* ----- прикрепление события на скролл ------ */
			/* ------------------------------------------- */
			$(window).on('scroll resize', function()
				{
				var
					$tableHat    = params.tableParent.find('#'+params.workTable.attr("use-ghost-hat")),
					$tableFoot   = params.tableParent.find('#'+params.workTable.attr("use-ghost-foot")),

					scrollTop    = $(window).scrollTop(),
					scrollBottom = scrollTop + document.body.clientHeight,
					tableTop     = params.workTable.offset().top,
					tableBottom  = tableTop + params.workTable.height();

				if($tableHat.length)
					{
					if(scrollTop > tableTop && scrollTop < tableBottom) $tableHat.show();
					else                                                $tableHat.hide();
					}
				if($tableFoot.length)
					{
					if(scrollBottom < tableBottom && scrollBottom > tableTop) $tableFoot.show();
					else                                                      $tableFoot.hide();
					}
				});
			});
		/* -------------------------------------------------------------------- */
		/* ------------------- создать дубль шапки таблицы -------------------- */
		/* -------------------------------------------------------------------- */
		function createGhostHead()
			{
			var
				$tableHat  = params.workTable.children("thead"),
				tableWidth = params.workTable.width(),
				ghostHatId = 'fix_table_hat_'+Math.floor(Math.random()*(99999));

			params.tableParent.find('[ghost-hat]').remove();
			params.workTable.attr('use-ghost-hat', ghostHatId);
			$
				(
				'<table ghost-hat id="'+ghostHatId+'" class="'+params.workTable.attr("class")+'">'+
					$tableHat.getCopy()+
				'</table>'
				)
				.appendTo(params.tableParent)
				.css
					({
					"display" : 'none',
					"margin"  : 0,
					"padding" : 0,
					"position": 'fixed',
					"top"     : 0,
					"width"   : tableWidth,
					"z-index" : params.zIndex
					})
				.find('thead > tr > th').each(function()
					{
					var $sameCell = $tableHat.find('tr > th:eq('+$(this).index()+')');
					$(this).attr("width", $sameCell.outerWidth()*100/tableWidth+'%');
					});
			}
		/* -------------------------------------------------------------------- */
		/* ------------------ создать дубль футтера таблицы ------------------- */
		/* -------------------------------------------------------------------- */
		function createGhostFoot()
			{
			var
				$tableFoot  = params.workTable.children("tfoot"),
				tableWidth  = params.workTable.width(),
				ghostFootId = 'fix_table_foot_'+Math.floor(Math.random()*(99999));

			params.tableParent.find('[ghost-foot]').remove();
			params.workTable.attr('use-ghost-foot', ghostFootId);
			$
				(
				'<table ghost-foot id="'+ghostFootId+'" class="'+params.workTable.attr("class")+'">'+
					$tableFoot.getCopy()+
				'</table>'
				)
				.appendTo(params.tableParent)
				.css
					({
					"display" : 'none',
					"margin"  : 0,
					"padding" : 0,
					"position": 'fixed',
					"bottom"  : 0,
					"width"   : tableWidth,
					"z-index" : params.zIndex
					})
				.find('tfoot > tr > td').each(function()
					{
					var $sameCell = $tableFoot.find('td > th:eq('+$(this).index()+')');
					$(this).attr("width", $sameCell.outerWidth()*100/tableWidth+'%');
					});
			}
		};
	})(jQuery);