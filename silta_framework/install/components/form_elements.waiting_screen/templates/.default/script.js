function WaitingScreenOn()
	{
	$('#silta_waiting-screen').remove();
	$('<div id="silta_waiting-screen"></div>').appendTo('body');
	}
function WaitingScreenOff() {$('#silta_waiting-screen').remove()}