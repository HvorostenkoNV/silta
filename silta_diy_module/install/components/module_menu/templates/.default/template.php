<table id="silta-diy-module-hat">
	<col style="width:60%"><col style="width:40%">
	<tr>
		<td cell="menu">
			<ul main-menu>
				<?foreach($arResult["menu"]["main_list"] as $infoArray):?>
				<li type="<?=$infoArray["type"]?>" <?if($infoArray["checked"]):?>checked<?endif?>>
					<a href="<?=$infoArray["link"]?>">
						<div image></div>
						<?=$infoArray["title"]?>
					</a>
				</li>
				<?endforeach?>
			</ul>

			<?foreach($arResult["menu"]["sublists"] as $menu => $menuLists):?>
			<ul sub-menu="<?=$menu?>">
				<?foreach($menuLists as $infoArray):?>
				<li>
					<a href="<?=$infoArray["link"]?>">
						<?=$infoArray["title"]?>
					</a>
				</li>
				<?endforeach?>
			</ul>
			<?endforeach?>
		</td>
		<td></td>
	</tr>
</table>