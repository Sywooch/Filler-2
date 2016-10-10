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
		<div class="col-sm-10 col-xs-24 text-center text-20 Subtitle"><?= Yii::t('Dictionary', 'Active lobbies'); ?></div>
		<div class="col-sm-4 text-center"></div>
		<div class="col-sm-10 hidden-xs text-center text-20 Subtitle"><?= Yii::t('Dictionary', 'Active players'); ?></div>

		<div class="col-sm-10 col-xs-24 text-left BlueLight-Box" id="LobbiesList"></div>

		<div class="col-xs-24 visible-xs text-right" id="LobbiesQuantity-xs"></div>
		
		<div class="col-sm-4 hidden-xs text-center">
			<button type="button" class="btn btn-primary col-xs-24" style="height:140px; white-space:normal;" data-toggle="modal" id="LobbyCreate"><?= Yii::t('Dictionary', 'Add lobby'); ?></button>
		</div>
		<div class="col-xs-24 visible-xs">
			<br>
			<button type="button" class="btn btn-primary btn-block" data-toggle="modal" id="LobbyCreate-xs"><?= Yii::t('Dictionary', 'Add lobby'); ?></button>
			<br><br>
		</div>
		<!-- LobbiesBlock -->
		<div class="col-xs-24 visible-xs text-center text-20 Subtitle"><?= Yii::t('Dictionary', 'Active players'); ?></div>
		<div class="col-sm-10 col-xs-24 text-left BlueLight-Box" id="PlayersList"></div>
		
		<div class="col-sm-12 hidden-xs text-left" id="LobbiesQuantity"></div>
		<div class="col-sm-12 col-xs-24 text-right" id="PlayersQuantity"></div>
	</div>



	<!-- GameMode -->
	<div class="col-xs-24" id="Mode-2">
		<div class="col-xs-24 text-center text-14 color-yellow" id="TopString"></div>
		<!-- GameBoardBlock -->
		<div class="col-lg-14 col-md-18 col-sm-24 col-xs-24 col-lg-push-5 text-center" id="GameBoardDiv">
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
		<div class="col-lg-5 col-md-6 col-sm-12 col-xs-24 col-lg-pull-14" id="PlayersList-1"></div>
		<!-- GamePlayersBlock -->
		<div class="col-lg-5 col-md-6 col-sm-12 col-xs-24" id="PlayersList-2"></div>
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
	<div class="modal-dialog modal-md">
		<div class="modal-content modal-background">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="LobbyLabel"><?= Yii::t('Dictionary', 'Add lobby'); ?></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-16 form-group" id="DivLobbyName">
						<label class="control-label" for="LobbyName"><?= Yii::t('Dictionary', 'Name'); ?></label>
						<input type="text" class="form-control" id="LobbyName" placeholder="<?= Yii::t('Dictionary', 'Enter a name'); ?>">
					</div>
					<div class="col-sm-8 form-group">
						<label for="ColorsNumber"><?= Yii::t('Dictionary', 'Colors number'); ?></label>
						<select class="form-control" id="ColorsNumber"></select>
					</div>
					
					<div class="col-xs-24 btn-group" style="display: inline-flex !important;">
						<div class="radio-button selected-radio-button size-mode-button" name="fieldSize" id="SizeA" checked>
							<?= Yii::t('Dictionary', 'Simple'); ?>
							<br><span class="text-12 text-normal">18 x 12</span>
						</div>
						<div class="radio-button size-mode-button" name="fieldSize" id="SizeB">
							<?= Yii::t('Dictionary', 'Medium'); ?>
							<br><span class="text-12 text-normal">24 x 16</span>
						</div>
						<div class="radio-button size-mode-button" name="fieldSize" id="SizeC">
							<?= Yii::t('Dictionary', 'Difficult'); ?>
							<br><span class="text-12 text-normal">30 x 20</span>
						</div>
					</div>

					<div class="col-xs-10 btn-group indent-top-sm" style="display: inline-flex !important;">
						<div class="radio-button selected-radio-button player-mode-button" name="playersNumber" id="x2Players" checked>
							<?= Yii::t('Dictionary', '2 players'); ?>
						</div>
						<div class="radio-button player-mode-button" name="playersNumber" id="x4Players">
							<?= Yii::t('Dictionary', '4 players'); ?>
						</div>
					</div>

					<div class="col-sm-14 form-group" id="botSettings">
						<div class="check-button bot-mode-button">
							<?= Yii::t('Dictionary', 'Bot'); ?>
						</div>
						<div class="check-button bot-mode-button bot-off">
							<?= Yii::t('Dictionary', 'Bot'); ?>
						</div>
						<div class="check-button bot-mode-button bot-off">
							<?= Yii::t('Dictionary', 'Bot'); ?>
						</div>
						<div>
							<select class="form-control" id="botLevel" style="cursor: pointer; height: 50px">
								<option style="padding: 7px 7px;" value="1"><?= Yii::t('Dictionary', 'Simple'); ?></option>
								<option style="padding: 7px 7px;" value="2" selected><?= Yii::t('Dictionary', 'Medium'); ?></option>
								<option style="padding: 7px 7px;" value="3"><?= Yii::t('Dictionary', 'Difficult'); ?></option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('Dictionary', 'Cancel'); ?></button>
				<button type="button" class="btn btn-primary" id="LobbySaveButton"><?= Yii::t('Dictionary', 'Posting'); ?></button>
			</div>
		</div>
	</div>
</div>



<!-- LobbyViewModal -->
<div class="modal fade" id="LobbyDialog" tabindex="-1" role="dialog" aria-labelledby="LobbyLabel2" data-backdrop="static" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content modal-background">
			<div class="modal-header">
				<h4 class="modal-title" id="LobbyTitle"><?= Yii::t('Dictionary', 'Lobby'); ?></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-24 col-sm-24 text-right text-13 color-gray" id="LobbyDialog-LobbySettings"></div>
					<div class="col-xs-24 col-sm-24 col-md-24 text-14" id="LobbyPlayersList"></div>
					<div class="col-xs-24 col-sm-24 col-md-24 text-12 text-italic color-gray" id="LobbyTip"></div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="WaitingIcon" id="LobbyDialog-Loading">
					<img src="<?= $bundle -> baseUrl . '/images/ajax-loader.gif'; ?>" alt=""></img>
				</div>
				<div class="row">
					<div class="col-xs-4 timer" id="LobbyTimer"></div>
					<div class="col-xs-20">
						<button type="button" class="btn btn-default" data-dismiss="modal" id="LobbyClose"><?= Yii::t('Dictionary', 'Close'); ?></button>
						<button type="button" class="btn btn-primary" disabled="disabled" id="GameStartButton"><?= Yii::t('Dictionary', 'Start game'); ?></button>
						<button type="button" class="btn btn-primary" data-loading-text="Подключение..." data-complete-text="Вы в игре" id="LobbyJoinButton"><?= Yii::t('Dictionary', 'Join'); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<!-- MessageModal -->
<div class="modal fade" id="MessageDialog" tabindex="-1" role="dialog" aria-labelledby="MessageLabel" data-backdrop="static" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content modal-background">
			<div class="modal-header">
				<h4 class="modal-title" id="MessageDialog-Caption"></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-24 text-14 color-white indent-bottom-sm" id="MessageDialog-Message"></div>
				</div>
			</div>
			<div class="modal-footer"><!-- text-center -->
				<div class="WaitingIcon" id="MessageDialog-Loading">
					<img src="<?= $bundle -> baseUrl . '/images/ajax-loader.gif'; ?>" alt=""></img>
				</div>
				<button type="button" class="btn btn-primary Button-Padding" data-dismiss="modal" id="MessageDialog-YesButton">YesButton</button>
				<button type="button" class="btn btn-default Button-Padding" data-dismiss="modal" id="MessageDialog-NoButton">NoButton</button>
			</div>
		</div>
	</div>
</div>



<audio preload="auto" id="Move">
	<source src="<?= Yii::$app -> request -> baseUrl; ?>/sounds/move.mp3" type="audio/mp3"></source>
	<source src="<?= Yii::$app -> request -> baseUrl; ?>/sounds/move.ogg" type="audio/ogg"></source>
</audio>
<!-- <audio preload="auto" loop="loop" id="Lobby">
	<source src="<?php //echo Yii::app() -> request -> baseUrl; ?>/sounds/lobby.mp3" type="audio/mp3"></source>
	<source src="<?php //echo Yii::app() -> request -> baseUrl; ?>/sounds/lobby.ogg" type="audio/ogg"></source>
</audio> -->
<audio preload="auto" id="Victory">
	<source src="<?= Yii::$app -> request -> baseUrl; ?>/sounds/victory.mp3" type="audio/mp3"></source>
	<source src="<?= Yii::$app -> request -> baseUrl; ?>/sounds/victory.ogg" type="audio/ogg"></source>
</audio>
