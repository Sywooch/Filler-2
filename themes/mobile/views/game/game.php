<?php

	use yii\helpers\Url;
	use yii\helpers\Html;
	
	use app\assets\ThemesAsset;
	use app\assets\GameAsset;

	use app\components\FooterMenu\FooterMenuWidget;
	use app\components\GameBoard\GameBoardWidget;

	// 
	GameAsset::register($this);
	// 
	$bundle = ThemesAsset::register($this);

?>

<div class="row">
	<!-- LobbyMode -->
	<div class="Lobby-Mode col-xs-24" id="Mode-1">
		<!-- PlayersBlock -->
		<div class="col-xs-24 text-right text-48 Subtitle"><?= Yii::t('Dictionary', 'ACTIVE LOBBIES'); ?></div>
		<div class="col-xs-24 text-left BlueLight-Box" id="LobbiesList"></div>

		<div class="col-xs-24 text-right" id="LobbiesQuantity-xs"></div>

		<div class="col-xs-24">
			<br>
			<button type="button" class="btn btn-primary btn-block text-48" style="height:140px;" data-toggle="modal" id="LobbyCreate-xs"><?= Yii::t('Dictionary', 'Add lobby'); ?></button>
			<br><br>
		</div>
		<!-- LobbiesBlock -->
		<div class="col-xs-24 text-right text-48 Subtitle"><?= Yii::t('Dictionary', 'ACTIVE PLAYERS'); ?></div>
		<div class="col-xs-24 text-left BlueLight-Box" id="PlayersList"></div>

		<div class="col-xs-24 text-right" id="PlayersQuantity"></div>
	</div>



	<!-- GameMode -->
	<div class="col-xs-24" id="Mode-2">
		<div class="col-xs-24 text-center text-14 color-yellow" id="TopString"></div>
		<!-- GameBoardBlock -->
		<div class="col-xs-24 text-center indent-bottom-sm" id="GameBoardDiv">
			<?=
				// Виджет игрового поля.
				GameBoardWidget::widget([
					// Размер игрового поля.
					'Size' => [30, 20],
					// Количество игровых цветов.
					'ColorsNumber' => 1,
					// Список игровых цветов.
					// 'ColorsList' => array()
				]);
			?>
		</div>
		<!-- GamePlayersBlock -->
		<div class="col-xs-24" id="PlayersList-1"></div>
		<!-- GamePlayersBlock -->
		<div class="col-xs-24" id="PlayersList-2"></div>
	</div>
</div>



<!-- FooterMenu -->
<div class="row">
	<div class="col-md-24 IndexFooterMenuBlock ">
		<?=
			// Выводится меню "Личные данные | Выход".
			FooterMenuWidget::widget([
				'ItemList' => [
					Yii::t('Dictionary', 'Personal') => Url::to(['site/personal']),
					Yii::t('Dictionary', 'Logout') => Url::to(['site/logout'])
				],
				'Style' => 2
			]);
		?>
	</div>
</div>



<!-- LobbyCreateModal -->
<div class="modal fade" id="LobbyCreateDialog" tabindex="-1" role="dialog" aria-labelledby="LobbyLabel" data-backdrop="static" aria-hidden="true">
	<div class="modal-dialog modal-lg" style="width:96%;">
		<div class="modal-content modal-background">
			<div class="modal-header" style="padding:30px;">
				<button type="button" class="close text-48" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title text-48" id="LobbyLabel"><?= Yii::t('Dictionary', 'Add lobby'); ?></h4>
			</div>
			<div class="modal-body" style="padding:30px;">
				<div class="row">
					<div class="col-xs-24 form-group" id="DivLobbyName">
						<label class="control-label text-36" for="LobbyName"><?= Yii::t('Dictionary', 'Name'); ?></label>
						<input type="text" class="form-control text-48" style="height:100px; margin-bottom:40px; padding:0 30px;"  id="LobbyName" placeholder="<?= Yii::t('Dictionary', 'Enter a name'); ?>">
					</div>
					<div class="col-xs-24 form-group">
						<label class="control-label text-36" for="ColorsNumber"><?= Yii::t('Dictionary', 'Colors number'); ?></label>
						<select class="form-control text-48" style="height:100px; margin-bottom:40px; padding:0 30px;" id="ColorsNumber"></select>
					</div>
					
					<div class="col-xs-24 btn-group btn-group-justified indent-bottom-md" data-toggle="buttons">
						<label class="btn btn-lg btn-default text-36" style="height:120px;" for="SizeA" id="SizeALable">
							<input type="radio" name="PlayingFieldSizeSelect" id="SizeA"><?= Yii::t('Dictionary', 'Simple'); ?><br><span class="text-24">18 x 12</span>
						</label>
						<label class="btn btn-lg btn-default text-36 active" style="height:120px;" for="SizeB" id="SizeBLable">
							<input type="radio" name="PlayingFieldSizeSelect" id="SizeB" checked><?= Yii::t('Dictionary', 'Medium'); ?><br><span class="text-24">24 x 16</span>
						</label>
						<label class="btn btn-lg btn-default text-36" style="height:120px;" for="SizeC" id="SizeCLable">
							<input type="radio" name="PlayingFieldSizeSelect" id="SizeC"><?= Yii::t('Dictionary', 'Difficult'); ?><br><span class="text-24">30 x 20</span>
						</label>
					</div>

					<div class="col-xs-24 btn-group btn-group-justified indent-top-sm" data-toggle="buttons">
						<label class="btn btn-lg btn-default text-36 active" style="height:100px;" for="x2Players" id="x2PlayersLable">
							<input type="radio" name="PlayersNumberSelect" id="x2Players" checked><?= Yii::t('Dictionary', '2 players'); ?>
						</label>
						<label class="btn btn-lg btn-default text-36" style="height:100px;" for="x4Players" id="x4PlayersLable">
							<input type="radio" name="PlayersNumberSelect" id="x4Players"><?= Yii::t('Dictionary', '4 players'); ?>
						</label>
					</div>
					
				</div>
			</div>
			<div class="modal-footer" style="padding:30px;">
				<button type="button" class="btn btn-default text-48" style="height:120px;" data-dismiss="modal"><?= Yii::t('Dictionary', 'Cancel'); ?></button>
				<button type="button" class="btn btn-primary text-48" style="height:120px;" id="LobbySaveButton"><?= Yii::t('Dictionary', 'Posting'); ?></button>
			</div>
		</div>
	</div>
</div>



<!-- LobbyViewModal -->
<div class="modal fade" id="LobbyDialog" tabindex="-1" role="dialog" aria-labelledby="LobbyLabel2" data-backdrop="static" aria-hidden="true">
	<div class="modal-dialog modal-lg" style="width:96%;">
		<div class="modal-content modal-background">
			<div class="modal-header" style="padding:30px;">
				<h4 class="modal-title text-48" id="LobbyTitle"><?= Yii::t('Dictionary', 'Lobby'); ?></h4>
			</div>
			<div class="modal-body" style="padding:30px;">
				<div class="row">
					<div class="col-xs-24 text-right text-36 indent-bottom-md color-gray" id="LobbyDialog-LobbySettings"></div>
					<div class="col-xs-24 text-36" id="LobbyPlayersList"></div>
					<div class="col-xs-24 text-36 text-italic color-gray" id="LobbyTip"></div>
				</div>
			</div>
			<div class="modal-footer" style="padding:30px;">
				<div class="WaitingIcon" id="LobbyDialog-Loading">
					<img src="<?= $bundle -> baseUrl . '/images/ajax-loader.gif'; ?>" alt=""></img>
				</div>
				<div class="row">
					<div class="col-xs-6 timer" id="LobbyTimer"></div>
					<div class="col-xs-18">
						<button type="button" class="btn btn-default text-48" style="height:120px;" data-dismiss="modal" id="LobbyClose"><?= Yii::t('Dictionary', 'Close'); ?></button>
						<button type="button" class="btn btn-primary text-48" style="height:120px;" disabled="disabled" id="GameStartButton"><?= Yii::t('Dictionary', 'Start game'); ?></button>
						<button type="button" class="btn btn-primary text-48" style="height:120px;" data-loading-text="Подключение..." data-complete-text="Вы в игре" id="LobbyJoinButton"><?= Yii::t('Dictionary', 'Join'); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<!-- MessageModal -->
<div class="modal fade" id="MessageDialog" tabindex="-1" role="dialog" aria-labelledby="MessageLabel" data-backdrop="static" aria-hidden="true">
	<div class="modal-dialog modal-lg" style="width:96%;">
		<div class="modal-content modal-background">
			<div class="modal-header" style="padding:30px;">
				<h4 class="modal-title text-48" id="MessageDialog-Caption"></h4>
			</div>
			<div class="modal-body" style="padding:30px;">
				<div class="row">
					<div class="col-xs-24 text-36 color-white indent-bottom-sm" id="MessageDialog-Message"></div>
				</div>
			</div>
			<div class="modal-footer" style="padding:30px;"><!-- text-center -->
				<div class="WaitingIcon" id="MessageDialog-Loading">
					<img src="<?= $bundle -> baseUrl . '/images/ajax-loader.gif'; ?>" alt=""></img>
				</div>
				<button type="button" class="btn btn-primary Button-Padding col-xs-24 text-48" style="height:120px;" data-dismiss="modal" id="MessageDialog-YesButton">YesButton</button>
				<button type="button" class="btn btn-default Button-Padding col-xs-24 text-48" style="height:120px;" data-dismiss="modal" id="MessageDialog-NoButton">NoButton</button>
			</div>
		</div>
	</div>
</div>



<audio preload="auto" id="Move">
	<source src="<?= Yii::$app -> request -> baseUrl; ?>/sounds/move.mp3" type="audio/mp3"></source>
	<source src="<?= Yii::$app -> request -> baseUrl; ?>/sounds/move.ogg" type="audio/ogg"></source>
</audio>
<audio preload="auto" id="Victory">
	<source src="<?= Yii::$app -> request -> baseUrl; ?>/sounds/victory.mp3" type="audio/mp3"></source>
	<source src="<?= Yii::$app -> request -> baseUrl; ?>/sounds/victory.ogg" type="audio/ogg"></source>
</audio>
