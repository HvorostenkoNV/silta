<?if(count($arResult["status_array"])):?>
	<ul
		class="sp-faw-provision-application-status-bar"
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

			<?if($infoArray["type"] == 'agreement' && $arResult["stage_description"] && count($infoArray["sign_users"])):?>
			<div text>
				<?=GetMessage("SP_FAW_PROV_APPLC_STATUS_DESC_AGREEMENT")?>:
				<ul sign-users>
					<?foreach($infoArray["sign_users"] as $userId => $value):?>
						<?
						$condition = 'unactive';
						if($value == 'signed') $condition = 'signed';
						if($value == 'active') $condition = 'active';
						?>
						<li condition="<?=$condition?>">
							<?$APPLICATION->IncludeComponent('bitrix:main.user.link', '', ["ID" => $userId, "USE_THUMBNAIL_LIST" => "N"])?>
						</li>
					<?endforeach?>
				</ul>
			</div>
			<?endif?>

			<?if($infoArray["type"] == 'responsible' && $arResult["stage_description"] && count($infoArray["responsibles"])):?>
			<div text>
				<?=GetMessage("SP_FAW_PROV_APPLC_STATUS_DESC_RESPONSIBLE")?>:
				<ul responsibles>
					<?foreach($infoArray["responsibles"] as $userId):?>
					<li><?$APPLICATION->IncludeComponent('bitrix:main.user.link', '', ["ID" => $userId, "USE_THUMBNAIL_LIST" => "N"])?></li>
					<?endforeach?>
				</ul>
			</div>
			<?endif?>
		</li>
	<?endforeach?>
	</ul>
<?endif?>