<?
// получить очищенный массив
function SgetClearArray($valueArray)
	{
	if(!is_array($valueArray)) $valueArray = [$valueArray];
	$valueArray = array_diff($valueArray, [0, null, '']);
	return $valueArray;
	}
// получить очищенный относительны URL
function SgetClearUrl($link)
	{
	$link = str_replace(['http://', 'https://', $_SERVER["HTTP_HOST"]], '', $link);
	if(substr_count($link, '?'))
		{
		$explodeArray     = explode('?', $link);
		$link = $explodeArray[0];
		}
	return $link;
	}
// получить строку GET переменных
function SgetUrlVarsString($varsValues = [], $varsDelete = [])
	{
	foreach($_GET as $index => $value)
		if(!in_array($index, $varsDelete) && !$varsValues[$index])
			$implodeArray[] = $index.'='.$value;
	foreach($varsValues as $index => $value)
		$implodeArray[] = $index.'='.$value;
	return '?'.implode('&', $implodeArray);
	}
// получить список разделов
function SCreateSectionstsList($sectionsId, $depth)
	{
	$title = '';
	if($depth)
		for($i = 1;$i <= $depth;$i++)
			$title .= '.';

	$sectionList = CIBlockSection::GetList(["ID" => 'asc'], ["ID" => $sectionsId], false, ["ID", "NAME"], false);
	while($section = $sectionList->GetNext()) $title .= $section["NAME"];
	$GLOBALS["s_sections_list"][$sectionsId] = $title;

	$sectionList = CIBlockSection::GetList(["ID" => 'asc'], ["SECTION_ID" => $sectionsId], false, ["ID", "NAME", "CODE"], false);
	while($section = $sectionList->GetNext()) SCreateSectionstsList($section["ID"], $depth+1);
	}

function SGetSectionstsList($iblockId, array $sectionsArray = [])
	{
	$GLOBALS["s_sections_list"] = [];
	if(!count($sectionsArray))
		{
		$sectionList = CIBlockSection::GetList(["ID" => 'asc'], ["IBLOCK_ID" => $iblockId, "SECTION_ID" => false], false, ["ID"], false);
		while($section = $sectionList->GetNext()) $sectionsArray[] = $section["ID"];
		}

	foreach($sectionsArray as $sectionId) SCreateSectionstsList($sectionId);
	return $GLOBALS["s_sections_list"];
	}
/* -------------------------------------------------------------------- */
/* -------- функции постройки элементов формы настроек модулей -------- */
/* -------------------------------------------------------------------- */
// выпадающий список
function SFMSFSelect($inputName = '', $list = [], $valueArray = [], $size = '', $emptyValue = 'Y')
	{
	if(!$inputName || !count($list)) return false;
	if(!is_array($valueArray)) $valueArray = [$valueArray];

	$htmlList = '';
	if($emptyValue != 'N') $htmlList = '<option value="0">'.GetMessage("SF_MSF_EMPTY_LIST").'</option>';
	foreach($list as $listValue => $listTitle)
		{
		$selected = '';
		if(in_array($listValue, $valueArray)) $selected = 'selected';
		$htmlList .= '<option value="'.$listValue.'" '.$selected.'>'.$listTitle.'</option>';
		}

	$htmlSelectSize = '';
	$size           = (int) $size;
	if($size > 1)
		{
		$htmlSelectSize = 'size="'.$size.'" multiple';
		$inputName     .= '[]';
		}

	return '
		<select name="'.$inputName.'" '.$htmlSelectSize.'>
			'.$htmlList.'
		</select>';
	}
// множ.поле ввода
function SFMSFMultInputes($inputName = '', $valueArray = [], $placeholder)
	{
	if(!$inputName) return false;
	if(!is_array($valueArray)) $valueArray = [$valueArray];
	$htmlResult      = '';
	$htmlPlaceholder = '';
	if($placeholder) $htmlPlaceholder = 'placeholder="'.$placeholder.'"';

	foreach(SgetClearArray($valueArray) as $value) $htmlResult .= '<input type="text" name="'.$inputName.'[]" value="'.$value.'" '.$htmlPlaceholder.'><br>';
	for($i = 1;$i <= 3;$i++)                       $htmlResult .= '<input type="text" name="'.$inputName.'[]" '.$htmlPlaceholder.'><br>';

	return $htmlResult;
	}
// множ.чекбоксы
function SFMSFMultCheckbox($inputName = '', $valueArray = [], $listArray = [])
	{
	if(!is_array($valueArray)) $valueArray = [$valueArray];
	$htmlResult = '';

	foreach($listArray as $value => $title)
		{
		$checked = '';
		if(in_array($value, $valueArray)) $checked = 'checked';
		$htmlResult .= '<input type="checkbox" name="'.$inputName.'[]" '.$checked.' value="'.$value.'">'.$title.'<br>';
		}

	return $htmlResult;
	}
?>