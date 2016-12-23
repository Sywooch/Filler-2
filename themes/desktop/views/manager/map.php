<?php

	use yii\helpers\Url;
	use yii\helpers\Html;
	
	use app\assets\ThemesAsset;
	use app\assets\ManagerAsset;

	use app\components\FooterMenu\FooterMenuWidget;
	use app\components\GameMap\GameMapWidget;

	// 
	ManagerAsset::register($this);
	// 
	$bundle = ThemesAsset::register($this);

?>

<div class="row">
	<!-- GameMode -->
	<div class="col-xs-24" id="map">
		<div class="col-xs-24 text-center text-14 color-yellow" id="TopString"></div>
		<!-- GameBoardBlock -->
		<div class="col-xs-16 text-center" id="GameBoardDiv">
			<?=
				// Виджет игрового поля.
				GameMapWidget::widget([
					// Размер игрового поля.
					'Size' => [18, 12]
				]);
			?>
		</div>
		<!-- GamePlayersBlock -->
		<div class="col-xs-8" id="settings">

			<ul class="nav nav-tabs nav-justified">
				<li class="active"><a href="#home" data-toggle="pill">Редактирование карты</a></li>
				<li><a href="#profile" data-toggle="pill">Список карт</a></li>
			</ul>

			<div class="tab-content indent-md">
				<div class="tab-pane active" id="home">
					<div class="row">
						<div class="col-xs-24 form-group" id="name">
							<label class="control-label" for="mapName"><?= Yii::t('Dictionary', 'Name'); ?></label>
							<input type="text" class="form-control" id="mapName" style="height: 50px" placeholder="<?= Yii::t('Dictionary', 'Enter a name'); ?>">
						</div>
						<div class="col-xs-24 form-group" id="description">
							<label class="control-label" for="mapDescription"><?= Yii::t('Dictionary', 'Description'); ?></label>
							<textarea class="form-control" rows="3" id="mapDescription" placeholder="<?= Yii::t('Dictionary', 'Enter a description'); ?>"></textarea>
						</div>
						<div class="col-xs-24 form-group" id="comment">
							<label class="control-label" for="mapComment"><?= Yii::t('Dictionary', 'Comment'); ?></label>
							<textarea class="form-control" rows="2" id="mapComment" placeholder="<?= Yii::t('Dictionary', 'Enter a comment'); ?>"></textarea>
						</div>
						<div class="col-xs-24 form-group" id="size">
							<label class="control-label" for="mapSize"><?= Yii::t('Dictionary', 'Size'); ?></label>
							<select class="form-control" id="mapSize" style="cursor: pointer; height: 50px;">
								<option style="padding: 7px 7px;" value="1">18 x 12</option>
								<option style="padding: 7px 7px;" value="2" selected>24 x 16</option>
								<option style="padding: 7px 7px;" value="3">30 x 20</option>
							</select>
						</div>
						<div class="col-xs-24 form-group" id="enable">
							<label class="control-label">
								<input type="checkbox" id="mapEnable" value="enable">&nbsp;&nbsp;Активная карта
							</label>
						</div>
						<div class="col-xs-24 form-group indent-md" id="buttons">
							<button type="button" class="btn btn-default" id="cancel" style="margin-right:10px;"><?= Yii::t('Dictionary', 'Cancel'); ?></button>
							<button type="button" class="btn btn-primary" data-dismiss="modal" id="save"><?= Yii::t('Dictionary', 'Save'); ?></button>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="profile">
					<div class="row">
						<div class="col-xs-24 form-group">
							<label class="control-label" for="editMapType"><?= Yii::t('Dictionary', 'Type'); ?></label>
							<select class="form-control" id="editMapType" style="cursor: pointer; height: 50px;">
								<option style="padding: 7px 7px;" value="1">Цвет</option>
								<option style="padding: 7px 7px;" value="2">Блок</option>
								<option style="padding: 7px 7px;" value="0" selected>Все типы</option>
							</select>
						</div>
						<div class="col-xs-24 form-group">
							<label class="control-label" for="editMapSize"><?= Yii::t('Dictionary', 'Size'); ?></label>
							<select class="form-control" id="editMapSize" style="cursor: pointer; height: 50px;">
								<option style="padding: 7px 7px;" value="1">18 x 12</option>
								<option style="padding: 7px 7px;" value="2">24 x 16</option>
								<option style="padding: 7px 7px;" value="3">30 x 20</option>
								<option style="padding: 7px 7px;" value="0" selected>Все размеры</option>
							</select>
						</div>
						<div class="col-xs-24 form-group">
							<label class="control-label" for="editMapName"><?= Yii::t('Dictionary', 'Name'); ?></label>
							<select class="form-control" id="editMapName" style="cursor: pointer; height: 50px;">
								<option style="padding: 7px 7px;" value="1" selected>Имя 1</option>
								<option style="padding: 7px 7px;" value="2">Имя 2</option>
								<option style="padding: 7px 7px;" value="3">Имя 3</option>
							</select>
						</div>
						<div class="col-xs-24 form-group indent-md" id="buttons">
							<button type="button" class="btn btn-default" id="editCancel" style="margin-right:10px;"><?= Yii::t('Dictionary', 'Cancel'); ?></button>
							<button type="button" class="btn btn-primary" data-dismiss="modal" id="editSave"><?= Yii::t('Dictionary', 'Save'); ?></button>
						</div>
					</div>
				</div>
			</div>


		</div>
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
