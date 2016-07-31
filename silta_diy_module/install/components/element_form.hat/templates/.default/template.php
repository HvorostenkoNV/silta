<?
// титул шапки
$title = GetMessage('SDM_EFH_ELEMENT_'.$arResult["table"]);
if(!$title) $title = GetMessage("SDM_EFH_ELEMENT_DEFAULT");
$title .= ' - ';
if($arResult["new_element"]) $title .= GetMessage("SDM_EFH_NEW_ELEMENT");
else                         $title .= $arResult["element_name"];
?>

<table id="silta-diy-module-element-hat">
	<tr>
		<th>
			<div title><?=$title?></div>
			<div slide-up-button></div>
		</th>
	</tr>
	<tr>
		<td>
			<ul tabs>
				<?foreach($arResult["tabs"] as $infoArray):?>
				<li <?if($infoArray["checked"]):?>checked<?endif?>>
					<a href="<?=$infoArray["link"]?>">
						<?=$infoArray["title"]?>
					</a>
				</li>
				<?endforeach?>
			</ul>
		</td>
	</tr>
	<tr>
		<td buttons-cell>
		<?
		$APPLICATION->IncludeComponent
			(
			"silta_framework:form_elements.button", '',
				[
				"IMG"   => $templateFolder.'/images/home.png',
				"TITLE" => GetMessage("SDM_EFH_HOME_LINK"),
				"LINK"  => $arResult["home_link"]
				]
			);
		?>
		</td>
	</tr>
</table>