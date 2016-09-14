<?php

namespace app\models\models;

use \DateTime;
use app\models\models\User;
use app\models\models\Player;
use app\models\models\Lobby;
use app\models\models\LobbyPlayer;

use app\models\User as tableUser;
use app\models\Lobby as tableLobby;
use app\models\LobbyPlayer as tableLobbyPlayer;

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
class Player extends \app\models\models\User {

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
				'ID' => $this -> ID,
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
		// $dbModel = LobbyPlayer::findOne(['PlayerID' => 1]) -> orderBy('LobbyID DESC');
		$dbModel = tableLobbyPlayer::find()
			-> where(['PlayerID' => 1])
			-> orderBy('LobbyID DESC')
			-> one();
		return $dbModel -> LobbyID;
	}



	/**
	 *	Возвращает список свободных соперников и соперников в процессе игры.
	 *
	 */
	public function getAvailableCompetitors($TimeInterval = 60) {
		// Поиск активных игроков за указанный интервал времени.
		// $dbModel = tableUser::model() -> findAllByAttributes(
		// 	array('Enable' => 1),
		// 	array(
		// 		'condition' => 'ID <> ' . $this -> ID . 
		// 		' AND (ActivityMarker >= (NOW() - INTERVAL ' . $TimeInterval . ' SECOND) OR GameMarker >= (NOW() - INTERVAL ' . $TimeInterval . ' SECOND))',
		// 		'order' => 'Name ASC'
		// 	)
		// );
		$dbModel = tableUser::find()
			-> where(
				'Enable = 1 AND ID <> ' . $this -> ID .
				' AND (ActivityMarker >= (NOW() - INTERVAL ' . $TimeInterval . 
				' SECOND) OR GameMarker >= (NOW() - INTERVAL ' . $TimeInterval . ' SECOND))'
			)
			-> orderBy('Name ASC');
		// Если активные игроки найдены:
		if ($dbModel !== null) {
			$Players = [];
			$Player = new Player();
			// Формирование массива активных игроков.
			foreach ($dbModel as $PlayerData) {
				if ($Player -> Load($PlayerData -> ID)) {
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
		// $dbModel = tableLobby::model() -> findAllByAttributes(
		// 	array('Status' => 1),
		// 	array(
		// 		'condition' => 'Date >= (NOW() - INTERVAL ' . $TimeInterval . ' SECOND)',
		// 		'order' => 'ID DESC'
		// 	)
		// );
		$dbModel = tableLobby::find()
			-> where('Status = 1 AND Date >= (NOW() - INTERVAL ' . $TimeInterval . ' SECOND)')
			-> orderBy('ID DESC');
		// Если активные лобби найдены:
		if ($dbModel !== null) {
			$LobbiesList = [];
			$Lobby = new Lobby();
			// Формирование списка активных лобби.
			foreach ($dbModel as $LobbyData) {
				if ($Lobby -> Load($LobbyData -> ID) && $Lobby -> isActive()) {
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
		$dbModel = tableUser::findOne($this -> ID);
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
		if (tableUser::model() -> updateByPk(
			$this -> ID, 
			array('GameMarker' => $Date)
		)) {
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
	public function Load($ID) {
		// Если модель игрока загружена:
		if (parent::Load($ID)) {
			// Загрузка игровых показателей игрока.
			$this -> loadStatistics($ID);
			return parent::loadModel($ID, '\app\models\User', array(
				'Rating',
				'ActivityMarker',
				'GameMarker'
			));
		}
		return false;
	}



	/**
	 *	Подсчет количества побед, поражений, ничьих, общего количества игр
	 *	и текущей победной серии игрока.
	 *
	 */
	protected function loadStatistics($ID) {
		$this -> WinGames = 0;
		$this -> LoseGames = 0;
		$this -> DrawGames = 0;
		$this -> WinningStreak = 0;
		// Получение из БД всех игр для указанного игрока.
		// $dbModel = LobbyPlayer::model() -> with(array(
		// 	'lobby.games' => array(
		// 		'select' => 'ID, StartDate, WinnerID', 
		// 		'condition' => 'WinnerID'
		// 	)
		// )) -> findAllByAttributes(array('PlayerID' => $ID));

		$dbModel = tableLobbyPlayer::find() -> with([
			'lobby.games' => [
				'select' => 'ID, StartDate, WinnerID', 
				'condition' => 'WinnerID'
			]
		]) -> where(['PlayerID' => $ID]);

		// Подсчет общего количества побед и текущей победной серии игрока.
		foreach ($dbModel as $Game) {
			if ($Game -> lobby -> games[0] -> WinnerID == $ID) {
				$this -> WinGames++;
				$this -> WinningStreak++;
			}
			else
				$this -> WinningStreak = 0;
		}
		// Подсчет общего количества игр и количества поражений игрока.
		$this -> TotalGames = sizeof($dbModel);
		$this -> LoseGames = $this -> TotalGames - $this -> WinGames;
	}



	/**
	 *	Сохранение модели игрока в базу данных.
	 *	Если модель успешно сохранилась, возвращает true.
	 *	Если модель не сохранилась, возвращает false.
	 *
	 */
	public function Save() {
		return parent::saveModel('\app\models\User', array(
			'Name' => $this -> Name,
			'Email' => $this -> Email,
			'Password' => $this -> Password,
			'Enable' => $this -> Enable,
			'Rating' => $this -> Rating,
		));
	}

}