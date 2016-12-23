<?php

return [

	// Начало игры.
	'GameStart' => [
		'Caption' => Yii::t('Dictionary', 'Game start'),
		'Message' => Yii::t('Dictionary', 'The game started. Good luck!'),
		'YesButton' => Yii::t('Dictionary', 'OK'),
		'NoButton' => '',
		'Type' => 'info',
		'Loading' => false
	],

	// Конец игры.
	'GameOver' => [
		'Caption' => Yii::t('Dictionary', 'Game over'),
		'Message' => Yii::t('Dictionary', 'The game is over.'),
		'YesButton' => Yii::t('Dictionary', 'OK'),
		'NoButton' => '',
		'Type' => 'info',
		'Loading' => false
	],

	// Победа.
	'Victory' => [
		'Caption' => Yii::t('Dictionary', 'Victory'),
		'Message' => Yii::t('Dictionary', 'Congratulations! You won!'),
		'YesButton' => Yii::t('Dictionary', 'OK'),
		'NoButton' => '',
		'Type' => 'info',
		'Loading' => false
	],

	// Поражение.
	'Defeat' => [
		'Caption' => Yii::t('Dictionary', 'Defeat'),
		'Message' => Yii::t('Dictionary', 'Sorry. You lost. In this game wins the player {COMPETITOR_NAME}.'),
		'YesButton' => Yii::t('Dictionary', 'OK'),
		'NoButton' => '',
		'Type' => 'info',
		'Loading' => false
	],

	// Ничья.
	'Draw' => [
		'Caption' => Yii::t('Dictionary', 'Draw'),
		'Message' => Yii::t('Dictionary', 'At this time the forces are equal.'),
		'YesButton' => Yii::t('Dictionary', 'OK'),
		'NoButton' => '',
		'Type' => 'info',
		'Loading' => false
	],

	// Игра еще не окончена.
	'GameNotOver' => [
		'Caption' => Yii::t('Dictionary', 'The game is not over yet'),
		'Message' => Yii::t('Dictionary', 'The current game is not over yet. Are you sure you want to leave the game?'),
		'YesButton' => Yii::t('Dictionary', 'Yes'),
		'NoButton' => Yii::t('Dictionary', 'No'),
		'Type' => 'warning',
		'Loading' => false
	],

	// Восстановление игры.
	'GameRecovery' => [
		'Caption' => Yii::t('Dictionary', 'The last game is not over yet'),
		'Message' => Yii::t('Dictionary', 'The unfinished game will be automatically restored. Good luck!'),
		'YesButton' => Yii::t('Dictionary', 'OK'),
		'NoButton' => '',
		'Type' => 'info',
		'Loading' => false
	],

	// Соперник покинул игру.
	'CompetitorEscape' => [
		'Caption' => Yii::t('Dictionary', 'The competitor left the game'),
		'Message' => Yii::t('Dictionary', 'Sorry, it looks that one of the competitors left the game. The game is over.'),
		'YesButton' => Yii::t('Dictionary', 'Close'),
		'NoButton' => '',
		'Type' => 'info',
		'Loading' => false
	],

	// Правила игры.
	'GameHelp' => [
		'Caption' => Yii::t('Dictionary', 'Game rules'),
		'MessageURL' => Yii::$app -> urlManager -> createUrl("/site/shorthelp"),
		'YesButton' => Yii::t('Dictionary', 'Close'),
		'NoButton' => '',
		'Type' => 'info',
		'Loading' => false
	],

	// Ошибка авторизации.
	'AuthorizationError' => [
		'Caption' => Yii::t('Dictionary', 'Authorization error'),
		'Message' => Yii::t('Dictionary', ''),
		'YesButton' => Yii::t('Dictionary', 'OK'),
		'NoButton' => '',
		'Type' => 'error',
		'Loading' => false
	],

	// Неизвестная ошибка.
	'UnknownError' => [
		'Caption' => Yii::t('Dictionary', 'Unknown error'),
		'Message' => Yii::t('Dictionary', 'Everything breaks sooner or later. But it is fixable. Please reload the page.'),
		'YesButton' => Yii::t('Dictionary', 'Close'),
		'NoButton' => '',
		'Type' => 'error',
		'Loading' => false
	],

	// Данные игрока.
	'Player' => [
		'Caption' => Yii::t('Dictionary', 'Player data'),
		'Message' => '',
		'YesButton' => Yii::t('Dictionary', 'Close'),
		'NoButton' => '',
		'Type' => 'info',
		'Loading' => false
	],

	// Окно сообщений.
	'MessageDialog' => [
		'PlayerName' => Yii::t('Dictionary', 'Player name'),
		'Rating' => Yii::t('Dictionary', 'Rating'),
		'GamesNumber' => Yii::t('Dictionary', 'Games number'),
		'WinsNumber' => Yii::t('Dictionary', 'Wins number'),
		'WinningStreak' => Yii::t('Dictionary', 'Winning streak'),
	],

	// Список лобби.
	'LobbyView' => [
		'Player' => Yii::t('Dictionary', 'Players'),
		'Color' => Yii::t('Dictionary', 'Colors'),
		'Size' => Yii::t('Dictionary', 'Size'),
		'Point' => Yii::t('Dictionary', 'Points'),
		'Portion' => Yii::t('Dictionary', 'Portion'),
		'Rating' => Yii::t('Dictionary', 'Rating'),
		'Lobby' => Yii::t('Dictionary', 'Lobby'),
		'Free' => Yii::t('Dictionary', 'Free'),
		'Busy' => Yii::t('Dictionary', 'In playing...'),
		'PlayerName' => Yii::t('Dictionary', 'Player name'),
		'WinningStreak' => Yii::t('Dictionary', 'Winning streak'),
		'PlayerStatus' => Yii::t('Dictionary', 'Player status'),
		'Features' => Yii::t('Dictionary', 'Features'),
		'Name' => Yii::t('Dictionary', 'Name'),
		'InTotal' => Yii::t('Dictionary', 'In total'),
	],

	// Просмотр лобби.
	'LobbyViewDialog' => [
		'Tip' => [
			'WaitPlayers' => Yii::t('Dictionary', 'Waiting connecting all players...'),
			'Expire' => Yii::t('Dictionary', 'The validity of the lobby is up.'),
			'WaitStart' => Yii::t('Dictionary', 'All players connected. The game starts...'),
			'Start' => Yii::t('Dictionary', 'All players connected. You can start the game.'),
			'NoPlace' => Yii::t('Dictionary', 'Sorry, all players already connected to this lobby.'),
		],
		'Settings' => [
			'Player' => Yii::t('Dictionary', 'Players'),
			'Color' => Yii::t('Dictionary', 'Colors'),
			'Size' => Yii::t('Dictionary', 'Size'),
		],
	],

	// Успешное сохранение карты.
	'MapSave' => [
		'Caption' => Yii::t('Dictionary', 'Map Save'),
		'Message' => Yii::t('Dictionary', 'The map {MAP_NAME} has been successfully saved.'),
		'YesButton' => Yii::t('Dictionary', 'OK'),
		'NoButton' => '',
		'Type' => 'info',
		'Loading' => false
	],

	// Успешное сохранение карты.
	'ErrorMapSave' => [
		'Caption' => Yii::t('Dictionary', 'Error saving card'),
		'Message' => Yii::t('Dictionary', 'When you save the map an error occurred.'),
		'YesButton' => Yii::t('Dictionary', 'Close'),
		'NoButton' => '',
		'Type' => 'info',
		'Loading' => false
	],
	
];
