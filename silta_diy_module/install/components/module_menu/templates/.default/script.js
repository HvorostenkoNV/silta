function SDIYModuleSubmenuPosition(menuType)
	{
	// обязательные значения
	var
		$mainMenu        = $('#silta-diy-module-hat [cell=menu]'),
		$mainMenuElement = $mainMenu.find('[main-menu] [type="'+menuType+'"]');
	if(!$mainMenu.length || !$mainMenuElement.length) return false;
	if(!menuType) menuType = $mainMenu.find('[main-menu] [checked]').attr("type");
	// переменные
	var
		$calledSubmanu = $mainMenu.find('[sub-menu="'+menuType+'"]'),
		$prevSubmanu   = $mainMenu.find('[sub-menu]:visible');
	// позиционирование
	if($prevSubmanu.length) $prevSubmanu.hide();
	$('#silta-diy-module-hat-menu-arrow').remove();
	if($calledSubmanu.length)
		{
		$calledSubmanu.show().offset({left: $mainMenuElement.offset().left});
		$('<div id="silta-diy-module-hat-menu-arrow"></div>').appendTo($mainMenu).offset
			({
			top : $calledSubmanu.offset().top - 30,
			left: $mainMenuElement.offset().left + $mainMenuElement.width()/2
			});
		}
	}

$(function()
	{
	SDIYModuleSubmenuPosition();
	$('#silta-diy-module-hat [main-menu] [type]').mouseover(function() {SDIYModuleSubmenuPosition($(this).attr("type"))});
	$(window).resize(function() {SDIYModuleSubmenuPosition()});
	});