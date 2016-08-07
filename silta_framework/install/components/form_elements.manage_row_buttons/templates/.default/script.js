$(function()
	{
	$('body').on('click', '.silta-form-manage-row-button', function()
		{
		var
			functionType = $(this).attr("type"),
			parentTag    = $(this).attr("parent");

		if(!parentTag) parentTag = $(this).parent().get(0).tagName;
		var $row = $(this).closest(parentTag);
		// удаление
		if(functionType == 'remove')
			$row.remove();
		// добавление
		if(functionType == 'add')
			{
			var
				$newRow      = $($row.getCopy()).appendTo($row.parent()),
				renameInpute = $(this).attr("rename-inputes");

			if($(this).is('[clear-form]'))
				$newRow.clearForm();
			if(renameInpute)
				{
				newName = Math.floor(Math.random()*(99999));
				$newRow.find('[rename-inputes="'+renameInpute+'"]').attr('rename-inputes', newName);
				$newRow.getFormInput().each(function()
					{
					$(this).setInputName($(this).getInputName().replace(renameInpute, newName));
					});
				}

			$(this).attr('type', "remove");
			}
		});
	});