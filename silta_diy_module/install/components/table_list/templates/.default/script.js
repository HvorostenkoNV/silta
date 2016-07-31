/* ============================================================================================= */
/* ============================ групповое изменение: функция очистки =========================== */
/* ============================================================================================= */
function SDMtableGroupEditionClear()
	{
	var
		$table       = $('.silta-diy-module-table-list'),
		$multiEditor = $table.find('[multi-editor]');

	$table      .find('[multi-checker]').add('[element-checker]').setInputValue("off");
	$multiEditor.find('[multi-changer]')                         .setInputValue();
	$multiEditor.add($multiEditor.find('[multi-edition-submit-button]')).hide();
	}
/* ============================================================================================= */
/* ============================ групповое изменение: функция выбора ============================ */
/* ============================================================================================= */
function SDMtableGroupEditionCheck()
	{
	var
		$table               = $('.silta-diy-module-table-list'),
		$multiEditor         = $table.find('[multi-editor]'),
		checkedElementsCount = 0;

	$table.find('[element-checker]').each(function()
		{
		if($(this).getInputValue() == 'on')
			checkedElementsCount += 1;
		});

	$multiEditor.find('[elements-count]').html(checkedElementsCount);
	if(checkedElementsCount) $multiEditor.show();
	else                     SDMtableGroupEditionClear();
	}
/* ============================================================================================= */
/* ======================================== обработчики ======================================== */
/* ============================================================================================= */
$(function()
	{
	$('.silta-diy-module-table-list').tableElementsFixed({"head": true, "foot": true});
	/* -------------------------------------------------------------------- */
	/* ----------------------- групповое изменение ------------------------ */
	/* -------------------------------------------------------------------- */
	// выбор элемента
	$('.silta-diy-module-table-list').on('change', '[element-checker]', function()
		{
		SDMtableGroupEditionCheck();
		});
	// мультиселектор (выбрать всех)
	$('.silta-diy-module-table-list').on('change', '[multi-checker]', function()
		{
		$('.silta-diy-module-table-list')
			.find('[multi-checker], [element-checker]')
			.setInputValue($(this).getInputValue());
		SDMtableGroupEditionCheck();
		});
	// кнопка "Отмена"
	$('.silta-diy-module-table-list').on('click', '[name="silta-diy-module-table-list-multi-change-cancel"]', function()
		{
		SDMtableGroupEditionClear();
		});
	// выбор типа изменения
	$('.silta-diy-module-table-list').on('change', '[multi-changer]', function()
		{
		var
			$table        = $('.silta-diy-module-table-list'),
			$multiChanger = $table.find('[multi-changer]'),
			$saveButton   = $table.find('[multi-edition-submit-button]'),
			value         = $(this).getInputValue();

		if(value) $saveButton.show();
		else      $saveButton.hide();
		$multiChanger.setInputValue(value);
		});
	});