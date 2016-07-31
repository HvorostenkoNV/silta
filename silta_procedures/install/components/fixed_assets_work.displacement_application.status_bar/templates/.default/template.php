<?if(count($arResult["status_array"])):?>
	<ul
		class="sp-faw-displacement-application-status-bar"
		<?if($arResult["procedure_closed"]):?>procedure-closed<?endif?>
	>
	<?foreach($arResult["status_array"] as $infoArray):?>
		<?
		$activity = 'N';
		if($infoArray["checked"]) $activity = 'Y';
		?>
		<li>
			<div stage-activity="<?=$activity?>"></div>
			<div title><?=$infoArray["title"]?></div>

			<?if($infoArray["type"] == '1c_exchange' && $arResult["stage_description"]):?>
			<div text><?=GetMessage("SP_FAW_DISPL_APPLC_STATUS_DESC_EXCHANGE")?></div>
			<?endif?>
		</li>
	<?endforeach?>
	</ul>
<?endif?>