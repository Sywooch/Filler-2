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
 * Player class file.
 *
 * @author Konstantin Poluektov <poluektovkv@gmail.com>
 * @copyright 2016 Konstantin Poluektov
 *
 */



/**
 * Player manages the player.
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
class Player extends User {

	/**
	 *	Количество игр, после которого ведется рейтинг.
	 *
	 */
	const RATING_MINIMUM_GAMES = 1;

	/**
	 *	Очки за победу.
	 *
	 */
	const WIN_POINTS = 3;

	/**
	 *	Очки за поражение.
	 *
	 */
	const LOSE_POINTS = 0;

	/**
	 *	Очки за ничью.
	 *
	 */
	const DRAW_POINTS = 1;



	/**
	 *	Рейтинг.
	 *
	 */
	protected $Rating = 0;

	/**
	 *	Победная серия.
	 *
	 */
	protected $WinningStreak = 0;

	/**
	 *	Все игры.
	 *
	 */
	protected $TotalGames = 0;

	/**
	 *	Победы.
	 *
	 */
	protected $WinGames = 0;

	/**
	 *	Поражения.
	 *
	 */
	protected $LoseGames = 0;

	/**
	 *	Ничьи.
	 *
	 */
	protected $DrawGames = 0;

	/**
	 *	Маркер активности игрока.
	 *
	 */
	protected $ActivityMarker;

	/**
	 *	Маркер участия игрока в игре.
	 *
	 */
	protected $GameMarker;



	/**
	 *	Вычисляет текущий рейтинг игрока:
	 *		Победа - 3 очка
	 *		Ничья - 1 очко
	 *		Поражение - 0 очков
	 *	Если общее количество игр меньше минимального, рейтинг равен 0.
	 *	Возвращает рейтинг в формате 00.00 (%).
	 *
	 */
	public function getRating($Unit = null) {
		$this -> Rating = 0;
		if ($this -> TotalGames >= self::RATING_MINIMUM_GAMES) {
			$this -> Rating = round(((
				$this -> WinGames * self::WIN_POINTS + 
				$this -> DrawGames * self::DRAW_POINTS + 
				$this -> LoseGames * self::LOSE_POINTS
			) / ($this -> TotalGames * self::WIN_POINTS)) * 100, 2) * 100;
		}
		return $this -> Rating / 100 . $Unit;
	}



	/**
	 *	Возвращает победную серию.
	 *
	 */
	public function getWinningStreak() {
		return $this -> WinningStreak;
	}



	/**
	 *	Возвращает общее количество игр.
	 *
	 */
	public function getTotalGames() {
		return $this -> TotalGames;
	}



	/**
	 *	Возвращает количество побед.
	 *
	 */
	public function getWinGames() {
		return $this -> WinGames;
	}



	/**
	 *	Возвращает количество поражений.
	 *
	 */
	public function getLoseGames() {
		return $this -> LoseGames;
	}



	/**
	 *	Возвращает количество ничьих.
	 *
	 */
	public function getDrawGames() {
		return $this -> DrawGames;
	}



	/**
	 *	Возвращает маркер активности игрока в указанном формате $Format.
	 *
	 */
	public function getActivityMarker($Format = 'Y-m-d H:i:s') {
		$ActivityMarker = new DateTime($this -> ActivityMarker);
		return $ActivityMarker -> format($Format);
	}



	/**
	 *	Возвращает маркер участия игрока в игре в указанном формате $Format.
	 *
	 */
	public function getGameMarker($Format = 'Y-m-d H:i:s') {
		$GameMarker = new DateTime($this -> GameMarker);
		return $GameMarker -> format($Format);
	}



	/**
	 *	Возвращает массив всех свойств.
	 *
	 */
	public function getPropertyList() {
		return array_merge(
			[
				'ID' => $this -> id,
				'Name' => $this -> Name
			],
			$this -> getStatistics()
		);
	}



	/**
	 *	Возвращает массив игровых показателей.
	 *
	 */
	public function getStatistics() {
		return [
			'TotalGames' => $this -> TotalGames,
			'WinGames' => $this -> WinGames,
			'LoseGames' => $this -> LoseGames,
			'DrawGames' => $this -> DrawGames,
			'WinningStreak' => $this -> WinningStreak,
			'Rating' => $this -> getRating(' %'),
			'Status' => $this -> getStatus()
		];
	}



	/**
	 *	Возвращает идентификатор последнего лобби, в котором участвовал игрок.
	 *	Если лобби не найдено, возвращает null.
	 *
	 */
	public function getLastLobbyID() {
		// Поиск в БД последнего лобби, в котором участвовал игрок.
		$dbModel = tableLobbyPlayer::find()
			-> where(['PlayerID' => $this -> id])
			-> orderBy('LobbyID DESC')
			-> one();
		return $dbModel -> LobbyID;
	}



	/**
	 *	Возвращает список свободных соперников и соперников в процессе игры.
	 *
	 */
	public function getBots($conditions = []) {
		// Поиск ботов, соответствующих условиям.
		$dbModel = tableBot::find()
			-> with('player')
			-> where(['Level' => 1, 'Secret' => 0])
			-> all();
		// Если активные игроки найдены:
		if ($dbModel !== null) {
			$Players = [];
			$Player = new Player();
			// Формирование массива активных игроков.
			foreach ($dbModel as $PlayerData) {
				if ($Player -> Load($PlayerData -> player -> id)) {
					$Players[] = array_merge(['bot' => true], $Player -> getPropertyList());
				}
			}
			return $Players;
		}
		else
			return false;
	}



	/**
	 *	Возвращает список свободных соперников и соперников в процессе игры.
	 *
	 */
	public function getAvailableCompetitors($TimeInterval = 60) {
		// Поиск активных игроков за указанный интервал времени.
		$dbModel = tableUser::find()
			-> where(
				'Enable = 1 AND ID <> ' . $this -> id .
				' AND (ActivityMarker >= (NOW() - INTERVAL ' . $TimeInterval . ' SECOND)' .
				' OR GameMarker >= (NOW() - INTERVAL ' . $TimeInterval . ' SECOND))'
			)
			-> orderBy('Name ASC')
			-> all();
		// Если активные игроки найдены:
		if ($dbModel !== null) {
			$Players = [];
			$Player = new Player();
			// Формирование массива активных игроков.
			foreach ($dbModel as $PlayerData) {
				if ($Player -> Load($PlayerData -> id)) {
					$Players[] = $Player -> getPropertyList();
				}
			}
			return $Players;
		}
		else
			return false;
	}



	/**
	 *	Возвращает список активных лобби.
	 *
	 */
	public function getLobbiesList($TimeInterval = 60) {
		// Поиск в БД активных лобби за указанный интервал времени.
		$dbModel = tableLobby::find()
			-> where('Status = 1 AND Date >= (NOW() - INTERVAL ' . $TimeInterval . ' SECOND)')
			-> orderBy('ID DESC')
			-> all();
		// Если активные лобби найдены:
		if ($dbModel !== null) {
			$LobbiesList = [];
			$Lobby = new Lobby();
			// Формирование списка активных лобби.
			foreach ($dbModel as $LobbyData) {
				if ($Lobby -> Load($LobbyData -> id) && $Lobby -> isActive()) {
					$LobbiesList[] = $Lobby -> getPropertyList();
				}
			}
			return $LobbiesList;
		}
		else
			return false;
	}



	/**
	 *	Возвращает текущий статус игрока.
	 *	Если игрок свободен, возвращает true.
	 *	Если игрок в процессе игры, возвращает false.
	 *
	 */
	public function getStatus() {
		// Если игрок ни разу не играл:
		if (!$this -> GameMarker) return true;
		// 
		$GameMarker = new DateTime($this -> GameMarker);
		$ActivityMarker = new DateTime($this -> ActivityMarker);
		return $ActivityMarker > $GameMarker ? true : false;
	}



	/**
	 *	Возвращает время в секундах текущего таймаута игрока.
	 *
	 */
	public function getGameTimeout() {
		$GameMarkerTime = new DateTime($this -> getGameMarker());
		$CurrentTime = new DateTime('now');
		return $CurrentTime -> getTimestamp() - $GameMarkerTime -> getTimestamp();
	}



	/**
	 *	Обновление маркера активности игрока.
	 *	Регистрирует в БД текущее время и дату.
	 *	Если маркер успешно зарегистрирован, возвращает true, иначе false.
	 *
	 */
	public function setActivityMarker() {
		$Date = date("Y-m-d H:i:s");
		$dbModel = tableUser::findOne($this -> id);
		$dbModel -> ActivityMarker = $Date;
		if ($dbModel -> update()) {
			$this -> ActivityMarker = $Date;
			return true;
		}
		else
			return false;
	}



	/**
	 *	Обновление маркера участия игрока в игре.
	 *	Регистрирует в БД текущее время и дату.
	 *	Если маркер успешно зарегистрирован, возвращает true, иначе false.
	 *
	 */
	public function setGameMarker() {
		$Date = date("Y-m-d H:i:s");
		$dbModel = tableUser::findOne($this -> id);
		$dbModel -> GameMarker = $Date;
		if ($dbModel -> update()) {
			$this -> GameMarker = $Date;
			return true;
		}
		else
			return false;
	}



	/**
	 *	Загрузка модели игрока из базы данных.
	 *	Если модель успешно загрузилась, возвращает true.
	 *	Если модель не загрузилась, возвращает false.
	 *
	 */
	public function Load($id) {
		// Если модель игрока загружена:
		if (parent::Load($id)) {
			// Загрузка игровых показателей игрока.
			$this -> loadStatistics($id);
			return parent::loadModel($id, '\app\models\User', [
				'Rating',
				'ActivityMarker',
				'GameMarker'
			]);
		}
		return false;
	}



	/**
	 *	Подсчет количества побед, поражений, ничьих, общего количества игр
	 *	и текущей победной серии игрока.
	 *
	 */
	protected function loadStatistics($id) {
		$this -> WinGames = 0;
		$this -> LoseGames = 0;
		$this -> DrawGames = 0;
		$this -> TotalGames = 0;
		$this -> WinningStreak = 0;
		// Получение из БД всех игр для указанного игрока.

		// SELECT lobby_player.*, game.*, lobby.*
		// FROM lobby_player 
		// LEFT JOIN game ON lobby_player.LobbyID = game.LobbyID 
		// LEFT JOIN lobby ON lobby_player.LobbyID = lobby.ID 
		// WHERE PlayerID = 4;

		// Получение из БД всех игр для указанного игрока.
		$dbModel = tableLobbyPlayer::find()
			-> with('lobby.games') 
			-> where(['PlayerID' => $id])
			-> all();
		// Подсчет общего количества побед и текущей победной серии игрока.
		foreach ($dbModel as $Game) {
			// Если по текущему лобби существует игра:
			if ($Game -> lobby -> games[0] -> WinnerID != null) {
				// Общее количество игр увеличивается.
				$this -> TotalGames++;
				// Если в текущей игре игрок победитель:
				if ($Game -> lobby -> games[0] -> WinnerID == $id) {
					// Количество побед увеличивается.
					$this -> WinGames++;
					// Непрерывная победная серия увеличивается.
					$this -> WinningStreak++;
				}
				else
					// Непрерывная победная серия обнуляется.
					$this -> WinningStreak = 0;
			}
		}
		// Подсчет общего количества игр и количества поражений игрока.
		$this -> LoseGames = $this -> TotalGames - $this -> WinGames;
	}



	/**
	 *	Сохранение модели игрока в базу данных.
	 *	Если модель успешно сохранилась, возвращает true.
	 *	Если модель не сохранилась, возвращает false.
	 *
	 */
	public function Save() {
		return parent::saveModel('\app\models\User', [
			'Name' => $this -> Name,
			'Email' => $this -> Email,
			'Password' => $this -> Password,
			'Enable' => $this -> Enable,
			'Rating' => $this -> Rating,
		]);
	}

}
