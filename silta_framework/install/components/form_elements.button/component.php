<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule("silta_framework"))                  return ShowError("module silta_framework required!");
/*
ПЕРЕДАВАЕМЫЕ ПАРАМЕТРЫ

NAME                - имя
VALUE               - значение
TITLE               - текст
TAG                 - тэг a/span/button

IMG                 - url картинки
IMG_POSITION        - позиция картинки left/right

ATTR                - аттрибуты
LINK                - ссылка (кнопка - ссылка)
HIDDEN              - кнопка скрыта Y/N

CONFIRM_TEXT        - текст для подтверждения действия
VALIDATE_FORM_ALERT - текст ошибки проверки формы на заполненность
*/
/* -------------------------------------------------------------------- */
/* -------------------------- корректировки --------------------------- */
/* -------------------------------------------------------------------- */
if(!in_array($arParams["IMG_POSITION"], ["left", "right"])) $arParams["IMG_POSITION"] = 'right';
// кнопка скрыта
$buttonHidden = false;
if($arParams["HIDDEN"] == 'Y') $buttonHidden = true;
// тэг кнопки
$buttonTag = $arParams["TAG"];
if(!in_array($buttonTag, ["span", "a", "button"])) $buttonTag = 'span';
if($arParams["CONFIRM_TEXT"] || $arParams["VALIDATE_FORM_ALERT"]) $buttonTag = 'button';
if($arParams["LINK"])                                             $buttonTag = 'a';
/* -------------------------------------------------------------------- */
/* ----------------------- параметры для шаблона ---------------------- */
/* -------------------------------------------------------------------- */
$arResult =
	[
	"name"                => $arParams["NAME"],               // имя
	"value"               => $arParams["VALUE"],              // значение
	"title"               => $arParams["TITLE"],              // текст
	"tag"                 => $buttonTag,                      // тэг

	"img"                 => $arParams["IMG"],                // url картинки
	"img_position"        => $arParams["IMG_POSITION"],       // позиция картинки

	"attr"                => $arParams["ATTR"],               // аттрибуты
	"link"                => $arParams["LINK"],               // ссылка (кнопка - ссылка)
	"hidden"              => $buttonHidden,                   // кнопка скрыта

	"confirm_text"        => $arParams["CONFIRM_TEXT"],       // текст для подтверждения действия
	"validate_form_alert" => $arParams["VALIDATE_FORM_ALERT"] // проверка обязательных полей формы
	];
/* -------------------------------------------------------------------- */
/* ------------------------------- вывод ------------------------------ */
/* -------------------------------------------------------------------- */
$this->IncludeComponentTemplate();
?>