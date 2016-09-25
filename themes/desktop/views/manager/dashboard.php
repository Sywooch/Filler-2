<?php

	use yii\helpers\Url;
	use yii\helpers\Html;
	use app\components\FooterMenu\FooterMenuWidget;

?>

<div class="row">
	<div class="col-xs-1">
	</div>
	<div class="col-xs-22">
		<div class="col-xs-24 text-left indent-top-md indent-bottom-md text-18 color-blue">
			Менеджер
		</div>
		<div class="col-sm-24 col-xs-24 text-14 indent-bottom-md color-gray">
			<span class="title-2">ABOUT THE GAME</span>
			<ul class="nav nav-pills">
				<li class="active"><a href="#home" data-toggle="pill">Home</a></li>
				<li><a href="#profile" data-toggle="pill">Profile</a></li>
				<li><a href="#messages" data-toggle="pill">Messages</a></li>
				<li><a href="#settings" data-toggle="pill">Settings</a></li>
			</ul>
			<p>
			Filler - free online puzzle multiplayer game.
			</p>
			<div class="tab-content">
				<div class="tab-pane active" id="home">home...</div>
				<div class="tab-pane" id="profile">profile...</div>
				<div class="tab-pane" id="messages">messages...</div>
				<div class="tab-pane" id="settings">settings...</div>
			</div>			
		</div>
		<div class="col-sm-24 col-xs-24 text-14 indent-bottom-md color-gray">
			<span class="title-2">CREATE LOBBY</span>
			<p>
			In order to gather players and start the game, you must create the lobby. 
			In the lobby you need to enter a title, choose the number of players, size of playing field and number of colors.
			</p>			
		</div>
	</div>
	<div class="col-xs-1">
	</div>
</div>

<div class="row">
	<div class="col-md-24 IndexFooterMenuBlock ">

<?php

	// Если пользователь не авторизован:
	if (Yii::$app -> user -> isGuest) {
		// Выводится меню "В начало".
		echo FooterMenuWidget::widget([
			'ItemList' => [
				Yii::t('Dictionary', 'Start') => Url::to(['site/index'])
			]
		]);
	}
	// Если пользователь авторизован:
	else {
		// Выводится меню "Играть | Выход".
		echo FooterMenuWidget::widget([
			'ItemList' => [
				Yii::t('Dictionary', 'Play') => Url::to(['game/game']),
				Yii::t('Dictionary', 'Logout') => Url::to(['site/logout'])
			],
			'Style' => 2
		]);
	}

?>

	</div>
</div>
