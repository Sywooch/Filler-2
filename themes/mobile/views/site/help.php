<?php

	use yii\helpers\Url;
	use yii\helpers\Html;
	use app\components\FooterMenu\FooterMenuWidget;

?>

<div class="row">
	<div class="col-xs-24">
		<div class="col-xs-24 text-left indent-top-md indent-bottom-md text-48 color-blue">
			Help
		</div>
		<div class="col-xs-24 text-36 indent-bottom-md color-gray">
			<span class="title-2">ABOUT THE GAME</span>
			<p>
			Filler - free online puzzle multiplayer game.
			</p>
			<p>
			The goal is to capture as many cells as possible and to make points.
			</p>
			<p>
			At the same time can play 2 or 4 players. 
			In order to become a player, you need to register.
			</p>

			<span class="title-2">REGISTRATION</span>
			<p>
			The registration procedure is very simple. 
			You need to "Register" to enter the required information: player name, email and password. 
			 Further, for authentication, you will need to enter email and password. 
			</p>
			<p>
			All registration information is sent to the user's email.
			</p>

			<span class="title-2">EDITING PERSONAL DATA</span>
			<p>
			If necessary, you can always change personal information (name, email, password). 
			For this purpose it is necessary in authorized mode to access the section "Personal data".
			</p>
			<p>
			Modified personal data is sent to the user's email.
			</p>

			<span class="title-2">PASSWORD RECOVERY</span>
			<p>
			If you have forgotten your password, you can use the password recovery procedure. 
			To do this in the login screen follow the link "Forgot password?" and enter 
			the email address that you registered.
			</p>
			<p>
			E-mail the user will receive an email with a link to further action.
			</p>

			<span class="title-2">CHANGE LANGUAGE</span>
			<p>
			The game supports multiple languages. 
			To change the language simply click on the appropriate button in the upper right corner of the screen. 
			</p>

			<span class="title-2">CREATE LOBBY</span>
			<p>
			In order to gather players and start the game, you must create the lobby. 
			In the lobby you need to enter a title, choose the number of players, size of playing field and number of colors.
			</p>
			<p>
			After the publication of the lobby, includes a connected standby players. 
			Once all players are connected, you can start the game.
			</p>
			<p>
			If, prior to the expiration of the lobby is the required number of players does not reach, 
			the lobby will be cancelled.
			</p>
			<p>
			A player also may not create a lobby and connect to one already created by other players.
			</p>

			<span class="title-2">GAMEPLAY</span>
			<p>
			Each player starts the game from the home cell, the location of which is indicated as such. 
			Home cell all the players have a different color. 
			The players make moves in turn. The first move made by the player that created the lobby. 
			</p>
			<p>
			In order to make progress, you need to choose a color Swatch under the playing field. 
			In this case, all the cells adjacent to the territory of the player and having the color matching is selected, 
			will be captured by the player. 
			The whole territory of the player repainted in their chosen color.
			</p>
			<p>
			During the turn you cannot select a color that already belongs to another player.
			</p>
			<p>
			The player is given a limited amount of time to make a move. 
			If a player fails to make a move, the turn automatically passes to the other player.
			</p>
			<p>
			The game ends once one player lose a large part of the cells, than any other.
			</p>

			<span class="title-2">THE INTERRUPTION OF THE GAME</span>
			<p>
			During the game for a short time, the player can move to other sections (personal data, etc.) 
			to switch the language or even to restart your browser or computer. 
			</p>
			<p>
			When returning to the game section of the unfinished game will be automatically restored, provided that 
			no time limit, no. 
			</p>
			<p>
			In case of exceeding the time limit the game will be stopped for all players.
			</p>

			<span class="title-2">STATISTICS</span>
			<p>
			For each player there is statistics of key indicators: total number of games and number of wins, 
			rating, continuous winning streak.
			</p>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-24 IndexFooterMenuBlock ">

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
