<?php

namespace app\models\models;

use \DateTime;

use app\models\Bot as tableBot;
use app\models\User as tableUser;
use app\models\Lobby as tableLobby;
use app\models\LobbyPlayer as tableLobbyPlayer;

use app\models\models\User as User;
use app\models\models\Player;
use app\models\models\Lobby;
use app\models\models\LobbyPlayer;



/**
 * Bot class file.
 *
 * @author Konstantin Poluektov <poluektovkv@gmail.com>
 * @copyright 2016 Konstantin Poluektov
 *
 */



/**
 * Bot manages the bot.
 *
 * @property float $Rating The rating of the player.
 * @property integer $WinningStreak Winning streak.
 * @property integer $TotalGames The total number of games.
 * @property integer $WinGames The number of wins.
 * @property integer $LoseGames The number of losses.
 * @property integer $DrawGames The number of draws.
 * @property date $ActivityMarker The marker of the activity of the player.
 * @property date $GameMarker The player's marker in the game.
 * 
 * @author Konstantin Poluektov <poluektovkv@gmail.com>
 *
 */
class Bot extends Player {

	/**
	 *	Скрытый бот.
	 *
	 */
	const SECRET = 1;



	/**
	 *	Уровень мастерства.
	 *
	 */
	protected $level = 1;



	/**
	 *	Возвращает уровень мастерства.
	 *
	 */
	public function getLevel() {
		return $this -> level;
	}

}
