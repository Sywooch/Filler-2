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
	const SECRET = true;



	/**
	 *	Уровень мастерства.
	 *
	 */
	protected $level = 1;

	/**
	 *	Время хода.
	 *
	 */
	protected $moveTime = 0;

	/**
	 *	Режим секретности.
	 *
	 */
	protected $secret = self::SECRET;



	/**
	 *	Возвращает уровень мастерства.
	 *
	 */
	public function getLevel() {
		return $this -> level;
	}



	/**
	 *	Возвращает время хода.
	 *
	 */
	public function getMoveTime() {
		return $this -> moveTime;
	}



	/**
	 *	Проверка является ли указанный игрок ботом.
	 *	Если игрок является ботом, загрузка бота из БД 
	 *	и возвращается true, иначе false.
	 *
	 */
	public function isBot($id) {
		$dbModel = tableBot::findOne($id);
		if ($dbModel) {
			$this -> Load($id);
			return true;
		}
		else
			return false;
	}



	/**
	 *	Поиск бота по заданным условиям в БД.
	 *	В качестве условий можно задать уровень сложности, скрытость 
	 *	и список исключений.
	 *	Если бот найден, модель загужается из БД и возвращается true, 
	 *	иначе возвращается false.
	 *
	 */
	public function search($condition, $excludedList = []) {
		// Поиск бота в БД.
		$dbModel = tableBot::find()
			-> where($condition)
			-> all();
		// Если боты найдены:
		if ($dbModel !== null) {
			// Проверка всех ботов на соответствие условиям.
			foreach ($dbModel as $botData) {
				// Если бота нет в списке исключений и модель бота загрузилась из БД:
				if (!in_array($botData -> PlayerID, $excludedList) && 
					$this -> Load($botData -> PlayerID)) {
					return true;
				}
			}
		}
		return false;
	}



	/**
	 *	Возвращает данные по сделанному ходу.
	 *
	 */
	public function getMove() {
		$move['colorIndex'] = rand(1, 10);
		$move['points'] = 1;
		return $move;
	}

}
