$(function()
	{
	$('#silta-diy-module-element-hat')
		.css
			({
			"top"    : '0',
			"z-index": '20',
			"width"  : $('#silta-diy-module-element-hat').width()
			})
		.find('[slide-up-button]').css("visibility", 'hidden');
	/* -------------------------------------------------------------------- */
	/* ------------------------ фиксированная шапка ----------------------- */
	/* -------------------------------------------------------------------- */
	$(window).on('scroll resize', function()
		{
		var
			$hat           = $('#silta-diy-module-element-hat'),
			$slideUpButton = $hat.find('[slide-up-button]'),
			$buttonsCell   = $hat.find('[buttons-cell]');

		$hat          .css({"position": 'static'});
		$slideUpButton.css("visibility", 'hidden');
		$buttonsCell  .show();
		if($(window).scrollTop() > $hat.offset().top)
			{
			$hat          .css({"position": 'fixed'});
			$slideUpButton.css("visibility", 'visible');
			$buttonsCell  .hide();
			}
		});
	/* -------------------------------------------------------------------- */
	/* -------------------------- кнопка "вверх" -------------------------- */
	/* -------------------------------------------------------------------- */
	$('body').on('click', '#silta-diy-module-element-hat [slide-up-button]', function()
		{
		$('body, html').animate({scrollTop: 0}, 800);
		});
	});