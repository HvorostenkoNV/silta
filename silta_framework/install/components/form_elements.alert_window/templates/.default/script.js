/* ============================================================================================== */
/* ========================================== ФУНКЦИЯ =========================================== */
/* ============================================================================================== */
function CallAlertWindow(params)
	{
	if(!params) params = {};
	params =
		{
		"text"       : params["text"],
		"closeButton":
			{
			"button": '',
			"text"  : params["closeButtonText"],
			"tag"   : 'span',
			"attr"  : 'name="alert-window-close-window"'
			},
		"applyButton":
			{
			"button": '',
			"text"  : params["applyButtonText"],
			"tag"   : params["applyButtonTag"],
			"attr"  : params["applyButtonAttr"]
			}
		};
	if(!params["applyButton"]["tag"]) params["applyButton"]["tag"] = 'span';
	// постройка кнопок
	$.each(["applyButton", "closeButton"], function(index, value)
		{
		if(params[value]["text"] && params[value]["tag"])
			params[value]["button"] =
				'<'+params[value]["tag"]+' button '+params[value]["attr"]+'>'+
					params[value]["text"]+
				'</'+params[value]["tag"]+'>';
		});
	// вывод формы
	AlertWindowOff();
	$
		(
		'<div id="silta-alert-window">'+
			'<form method="post" enctype="multipart/form-data">'+
				'<p>'+params["text"]+'</p>'+
				params["applyButton"]["button"]+
				params["closeButton"]["button"]+
			'</form>'+
		'</div>'
		)
		.appendTo('body');
	}

function AlertWindowOff()
	{
	$('#silta-alert-window').remove();
	}
/* ============================================================================================== */
/* ========================================= ОБРАБОТЧИК ========================================= */
/* ============================================================================================== */
$(function()
	{
	$('body').on('click', '#silta-alert-window [name="alert-window-close-window"]', function()
		{
		AlertWindowOff();
		});
	});