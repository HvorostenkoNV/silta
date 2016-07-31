$(function()
	{
	/* -------------------------------------------------------------------- */
	/* -------------------------- показать форму -------------------------- */
	/* -------------------------------------------------------------------- */
	$('body').on('click', '[name="silta-diy-module-element-comment-form-open"]', function()
		{
		$('#silta-diy-module-element-comment-form')
			.show()
			.draggable({handle:'[form-title]', containment:'body'})
			.setFormPosition();
		});
	/* -------------------------------------------------------------------- */
	/* --------------------------- скрыть формы --------------------------- */
	/* -------------------------------------------------------------------- */
	$(document).click(function(event)
		{
		var $clickedElement = $(event.target);
		if
			(
			$clickedElement.closest('#silta-diy-module-element-comment-form').length
			||
			$clickedElement.closest('[name="silta-diy-module-element-comment-form-open"]').length
			) return;

		var $selector = $('#silta-diy-module-element-comment-form');
		$selector.fadeOut(300, function() {$selector.hide()});
		event.stopPropagation();
		});

	$('body').on('click', '[name="silta-diy-module-element-comment-form-close"]', function()
		{
		var $selector = $('#silta-diy-module-element-comment-form');
		$selector.fadeOut(300, function() {$selector.hide()});
		event.stopPropagation();
		});
	});