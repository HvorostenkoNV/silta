<?if(count($arResult["status_array"])):?>
	<ul class="sp-btr-status-bar" <?if($arResult["procedure_closed"]):?>procedure-closed<?endif?>>
	<?foreach($arResult["status_array"] as $infoArray):?>
		<?
		$activity = 'N';
		if($infoArray["checked"]) $activity = 'Y';
		?>
		<li>
			<div stage-activity="<?=$activity?>"></div>
			<div title><?=$infoArray["title"]?></div>

			<?if($arResult["stage_description"]):?>
				<?if($infoArray["boss_id"]):?>
				<div text>
					<?=GetMessage("SP_BTR_STATUS_DESC_BOSS_CONFIRM")?>:<br>
					<?$APPLICATION->IncludeComponent('bitrix:main.user.link', '', ["ID" => $infoArray["boss_id"], "USE_THUMBNAIL_LIST" => "N"])?>
				</div>
				<?endif?>

				<?if($infoArray["manager_id"]):?>
				<div text>
					<?=GetMessage("SP_BTR_STATUS_DESC_MANAGER_CONFIRM")?>:<br>
					<?$APPLICATION->IncludeComponent('bitrix:main.user.link', '', ["ID" => $infoArray["manager_id"], "USE_THUMBNAIL_LIST" => "N"])?>
				</div>
				<?endif?>
			<?endif?>
		</li>
	<?endforeach?>
	</ul>
<?endif?>