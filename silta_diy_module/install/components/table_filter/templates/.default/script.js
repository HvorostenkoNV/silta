$(function()
	{
	$('body').on('click', '[name="silta-diy-module-table-filter-open"], [name="silta-diy-module-table-filter-close"]', function()
		{
		var
			functionType      = $(this).attr("name"),
			$filterOpenButton = $('[name="silta-diy-module-table-filter-open"]'),
			$filterApply      = $('#silta-diy-module-table-filter-read'),
			$filter           = $('#silta-diy-module-table-filter-write');

		if(functionType == 'silta-diy-module-table-filter-open')
			{
			$filterOpenButton.add($filterApply).hide();
			$filter.slideDown();
			}
		if(functionType == 'silta-diy-module-table-filter-close')
			{
			$filterOpenButton.add($filterApply).show();
			$filter.slideUp();
			}
		});
	});