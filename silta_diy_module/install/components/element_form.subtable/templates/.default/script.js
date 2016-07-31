$(function()
	{
	// добавить форму
	$('body').on('click', '[name="silta-diy-module-subtable-add-button"]', function()
		{
		var $newElementForm = $('.silta-diy-module-subtable-element[new-element]');

		$(this).hide();
		$newElementForm.show();
		$('html, body').animate({"scrollTop": $newElementForm.offset().top - document.body.clientHeight/2}, 800);
		});
	// скрыть добавление нового элемента
	$('body').on('click', '.silta-diy-module-subtable-element [hide-form]', function()
		{
		var $form = $('.silta-diy-module-subtable-element[new-element]');
		$form.fadeOut(300, function()
			{
			$form.hide();
			$('[name="silta-diy-module-subtable-add-button"]').show();
			});
		});
	});