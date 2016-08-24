<?
// ошибки при вызове методов - ТАБЛИЦА
$MESS["SF_FUNCTION_ERROR_DBT_GET_ELEMENT"]          = '$elementId - ИД элемента, числовой код либо строка "new"';
$MESS["SF_FUNCTION_ERROR_DBT_SET_PROPERTY_TYPE"]    = '$type - тип свойства, $infoArray - массив с обязательными ключами title/property_class/element_property_class';
$MESS["SF_FUNCTION_ERROR_DBT_CHANGE_PROPERTY_TYPE"] = '$name - имя свойства, $type - новый тип свойства';
// ошибки в ходе выполнения - ТАБЛИЦА
$MESS["SF_TABLE_ERROR_ELEMENT_CLASS_NAME_NOT_SET"] = 'Имя класса "элемент таблицы" не задано';
$MESS["SF_TABLE_ERROR_PROP_NOT_SET"]               = 'Отсутствует свойство с кодом "#PROP_NAME#"';
$MESS["SF_TABLE_ERROR_PROP_NOT_REQUIRED"]          = 'Свойство с кодом "#PROP_NAME#" должно быть обязательным к заполнению';
$MESS["SF_TABLE_ERROR_PROP_NOT_MULTIPLY"]          = 'Свойство с кодом "#PROP_NAME#" должно быть множественным';
$MESS["SF_TABLE_ERROR_PROP_IS_MULTIPLY"]           = 'Свойство с кодом "#PROP_NAME#" должно быть НЕ множественным';
$MESS["SF_TABLE_ERROR_PROP_TYPE_WRONG"]            = 'Свойство с кодом "#PROP_NAME#" должно иметь тип "#PROP_TYPE#"';
$MESS["SF_TABLE_ERROR_PROP_LIST_ELEMENT"]          = 'Свойство с кодом "#PROP_NAME#" должно быть ссылкой на инфоблок с ИД равным #TABLE_ID#';
// ошибки в ходе выполнения - СВОЙСТВО ТАБЛИЦЫ
$MESS["SF_FUNCTION_ERROR_DBP_CONSTRUCTOR"] = '$tableObject - объект SDBTable(либо потомка), $propertyName - имя свойства, $attributes - массив аттрибутов';
$MESS["SF_PROPERTY_ERROR_TYPE_NOT_EXIST"]  = 'Тип свойства не задан';
// ошибки в ходе выполнения - ЕЛЕМЕНТ
$MESS["SF_FUNCTION_ERROR_DBE_CONSTRUCTOR"] = '$tableObject - объект SDBTable(либо потомка), $elementId - ИД элемента/new';
// ошибки в элементе
$MESS["SF_FUNCTION_ERROR_DBE_SE_ELEMENT_NO_ACCESS"]   = 'Нет доступа к записи элемента';
$MESS["SF_FUNCTION_ERROR_DBE_DE_ELEMENT_NO_ACCESS"]   = 'Нет доступа к удалению элемента';
$MESS["SF_FUNCTION_ERROR_DBE_SE_PROPS_NO_ACCESS"]     = 'Нет свойств, к которым имеется доступ на запись';
$MESS["SF_FUNCTION_ERROR_DBE_SE_REQUIERD_PROP_EMPTY"] = 'Обязательное к заполнению свойство #PROP_NAME# не заполнено';
?>