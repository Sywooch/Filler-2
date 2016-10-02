<?php

namespace app\models\models;

use \DateTime;
use app\models\User as tableUser;
use app\models\Lobby as tableLobby;
use app\models\LobbyPlayer as tableLobbyPlayer;

use app\models\models\Player;


/**
 * Lobby class file.
 *
 * @author Konstantin Poluektov <poluektovkv@gmail.com>
 * @copyright 2016 Konstantin Poluektov
 *
 */



/**
 * Lobby manages the game lobby.
 *
 * @property string $Name The name of the lobby.
 * @property integer $SizeX X-size of the playing field.
 * @property integer $SizeY Y-size of the playing field.
 * @property integer $ColorsNumber The number of colors.
 * @property integer $PlayersNumber The number of players.
 * @property date $Date Creation date lobby.
 * @property integer $CreatorID The author ID of the lobby.
 * @property integer $Status The status of the lobby. Defaults to 1.
 *
 * @author Konstantin Poluektov <poluektovkv@gmail.com>
 *
 */
class Lobby extends LSD {

	/**
	 *	Срок действия лобби (секунды)
	 *
	 */
	const ACTIVE_LOBBY_TIME_INTERVAL = 120;



	/**
	 *	Название.
	 *
	 */
	protected $Name;

	/**
	 *	X-размер игрового поля.
	 *
	 */
	protected $SizeX;

	/**
	 *	Y-размер игрового поля.
	 *
	 */
	protected $SizeY;

	/**
	 *	Количество цветов.
	 *
	 */
	protected $ColorsNumber;

	/**
	 *	Количество игроков.
	 *
	 */
	protected $PlayersNumber;

	/**
	 *	Дата.
	 *
	 */
	protected $Date;

	/**
	 *	Идентификатор автора лобби.
	 *
	 */
	protected $CreatorID;

	/**
	 *	Статус.
	 *
	 */
	protected $Status = 1;

	/**
	 *	Уровень сложности ботов.
	 *
	 */
	protected $botsLevel;

	/**
	 *	Количество ботов.
	 *
	 */
	protected $botsNumber;



	/**
	 *	
	 *
	 */
	function __construct($Name = null, $SizeX = null, $SizeY = null, $ColorsNumber = null, $PlayersNumber = null, $CreatorID = null) {
		$this -> Name = $Name;
		$this -> SizeX = $SizeX;
		$this -> SizeY = $SizeY;
		$this -> ColorsNumber = $ColorsNumber;
		$this -> PlayersNumber = $PlayersNumber;
		$this -> CreatorID = $CreatorID;
	}



	/**
	 *	Возвращает название лобби.
	 *
	 */
	public function getName() {
		return $this -> Name;
	}



	/**
	 *	Возвращает X-размер игрового поля.
	 *
	 */
	public function getSizeX() {
		return $this -> SizeX;
	}



	/**
	 *	Возвращает Y-размер игрового поля.
	 *
	 */
	public function getSizeY() {
		return $this -> SizeY;
	}



	/**
	 *	Возвращает количество цветов лобби.
	 *
	 */
	public function getColorsNumber() {
		return $this -> ColorsNumber;
	}



	/**
	 *	Возвращает требуемое количество игроков.
	 *
	 */
	public function getRequiredPlayersNumber() {
		return $this -> PlayersNumber;
	}



	/**
	 *	Возвращает текущее количество подключившихся к лобби игроков.
	 *
	 */
	public function getCurrentPlayersNumber() {
		return tableLobbyPlayer::find()
			-> where(['LobbyID' => $this -> ID])
			-> count();
	}



	/**
	 *	Возвращает дату создания лобби в указанном формате.
	 *
	 */
	public function getDate($Format = 'Y-m-d H:i:s') {
		$Date = new DateTime($this -> Date);
		return $Date -> format($Format);
	}



	/**
	 *	Возвращает идентификатор автора лобби.
	 *
	 */
	public function getCreatorID() {
		return $this -> CreatorID;
	}



	/**
	 *	Возвращает статус лобби.
	 *
	 */
	public function getStatus() {
		return $this -> Status;
	}



	/**
	 *	Возвращает массив всех свойств лобби.
	 *
	 */
	public function getPropertyList() {
		return [
			'ID' => $this -> ID,
			'Name' => $this -> Name,
			'SizeX' => $this -> SizeX,
			'SizeY' => $this -> SizeY,
			'ColorsNumber' => $this -> ColorsNumber,
			'PlayersNumber' => $this -> PlayersNumber,
			'Date' => $this -> Date,
			'CreatorID' => $this -> CreatorID,
			'PlayersList' => $this -> getPlayersList(),
			'Timer' => $this -> getTimer(),
			'Active' => $this -> isActive(),
		];
	}



	/**
	 *	Возвращает текущее значение таймера лобби в секундах.
	 *
	 */
	public function getTimer() {
		$StartTime = new DateTime($this -> Date);
		$CurrentTime = new DateTime('now');
		return $CurrentTime -> getTimestamp() - $StartTime -> getTimestamp();
	}



	/**
	 *	Возвращает список стартовых позиций игроков.
	 *
	 */
	public function getPlayersStartingPosition() {
		// Список стартовых позиций игроков.
		$PlayersPosition = [];
		// Позиция игрока #1.
		$PlayersPosition[] = 1;
		// Если участвует 2 игрока.
		if ($this -> PlayersNumber == 2)
			// Позиция игрока #2.
			$PlayersPosition[] = $this -> SizeX * $this -> SizeY;
		// Если участвует 4 игрока.
		else if ($this -> PlayersNumber == 4) {
			// Позиция игрока #2.
			$PlayersPosition[] = $this -> SizeX;
			// Позиция игрока #3.
			$PlayersPosition[] = ($this -> SizeX * $this -> SizeY) - $this -> SizeX + 1;
			// Позиция игрока #4.
			$PlayersPosition[] = $this -> SizeX * $this -> SizeY;
		}
		// Если указано непредусмотренное количество игроков.
		else
			return false;
		// Возвращается список стартовых позиций всех игроков.
		return $PlayersPosition;
	}



	/**
	 *	Возвращает список подключившихся к лобби игроков.
	 *
	 */
	public function getPlayersList() {
		// Список подключившихся к лобби игроков.
		$PlayersList = [];
		// Поиск в БД всех подключившихся к лобби игроков.
		$dbModel = tableLobbyPlayer::find()
			-> where(['LobbyID' => $this -> ID])
			-> orderBy('Date, PlayerID ASC')
			-> all();
		// Формирование списка подключившихся к лобби игроков.
		$Player = new Player();
		foreach ($dbModel as $LobbyPlayer) {
			if ($Player -> Load($LobbyPlayer -> PlayerID)) {
				$PlayersList[] = $Player -> getPropertyList();
			}
		}
		// Возвращается список подключившихся к лобби игроков.
		return $PlayersList;
	}



	/**
	 *	Установка свойств лобби.
	 *
	 */
	public function set($lobby) {
		foreach ($lobby as $propertyName => $propertyValue) {
			// Если текущее свойство существует:
			if (property_exists($this, $propertyName))
				// Установка значения текущего свойства.
				$this -> $propertyName = $propertyValue;
		}
	}



	/**
	 *	Обновляет в БД статус лобби.
	 *	Если статус лобби успешно обновлен в БД, возвращает true.
	 *	Иначе возвращает false.
	 *
	 */
	public function setStatus($Status) {
		// Если статус лобби успешно обновлен:
		$dbModel = tableLobby::findOne($this -> ID);
		$dbModel -> Status = $Status;
		if ($dbModel -> update()) {
			$this -> Status = $Status;
			return true;
		}
		else
			return false;
	}



	/**
	 *	Подключение к лобби указанного игрока.
	 *	Если новый игрок успешно подключен к лобби, возвращается true.
	 *	Возвращается false, если:
	 *		- указанный игрок уже подключен к лобби
	 *		- все игроки уже подключились к лобби
	 *		- срок действия лобби истек
	 *
	 */
	public function AddPlayer($PlayerID) {
		// Если все игроки уже подключились к лобби или 
		// указанный игрок уже подключен к лобби или 
		// срок действия лобби истек:
		if ($this -> isFullPlayersList() || $this -> isPlayerIncluded($PlayerID) || !$this -> isActive())
			return false;
		// Подключение указанного игрока к лобби.
		$dbModel = new tableLobbyPlayer();
		$dbModel -> attributes = [
			'LobbyID' => $this -> ID,
			'PlayerID' => $PlayerID
		];
		// Если указанный игрок успешно добавлен к списку игроков лобби в БД:
		if ($dbModel -> insert())
			return true;
		else
			return false;
	}



	/**
	 *	Проверка подключен ли указанный игрок к лобби.
	 *	Если игрок подключен к лобби, возвращает true, иначе false.
	 *
	 */
	public function isPlayerIncluded($PlayerID) {
		// Если указанный игрок не найден в списке игроков лобби в БД:
		if (!tableLobbyPlayer::findOne([
			'LobbyID' => $this -> ID, 
			'PlayerID' => $PlayerID
		]))
			return false;
		else
			return true;
	}



	/**
	 *	Проверка подключились ли все игроки к лобби.
	 *	Если все игроки подключились к лобби, возвращает true, иначе false.
	 *
	 */
	public function isFullPlayersList() {
		// 
		if ($this -> getRequiredPlayersNumber() == $this -> getCurrentPlayersNumber())
			return true;
		else
			return false;
	}



	/**
	 *	Возвращает текущее состояние лобби.
 	 *	Если лобби активное (срок действия не истек), возвращает true, иначе false.
	 *
	 */
	public function isActive() {
		$StartDate = new DateTime($this -> Date);
		$CurrentTime = new DateTime('now');
		// 
		if (($CurrentTime -> getTimestamp() - $StartDate -> getTimestamp()) <= self::ACTIVE_LOBBY_TIME_INTERVAL)
			return true;
		else
			return false;
	}



	/**
	 *	Загрузка модели лобби из базы данных.
	 *	Если модель успешно загрузилась, возвращает true.
	 *	Если модель не загрузилась, возвращает false.
	 *
	 */
	public function Load($ID) {
		//
		parent::loadModel($ID, '\app\models\LobbyBot', [
			'level',
			'botsNumber'
		]);
		//
		return parent::loadModel($ID, '\app\models\Lobby', [
			'Name',
			'SizeX',
			'SizeY',
			'ColorsNumber',
			'PlayersNumber',
			'Date',
			'CreatorID',
			'Status'
		]);
	}



	/**
	 *	Сохранение модели лобби в базу данных.
	 *	Если модель успешно сохранилась, возвращает true.
	 *	Если модель не сохранилась, возвращает false.
	 *
	 */
	public function Save() {
		//
		$lobby = parent::saveModel('\app\models\Lobby', [
			'Name' => $this -> Name,
			'SizeX' => $this -> SizeX,
			'SizeY' => $this -> SizeY,
			'ColorsNumber' => $this -> ColorsNumber,
			'PlayersNumber' => $this -> PlayersNumber,
			'CreatorID' => $this -> CreatorID,
			'Status' => $this -> Status,
		]);
		//
		if ($this -> botsLevel && $this -> botsNumber) {
			$lobbyBot = parent::saveModel('\app\models\LobbyBot', [
				'lobbyID' => $this -> ID,
				'level' => $this -> botsLevel,
				'botsNumber' => $this -> botsNumber,
			]);
		}
		else
			$lobbyBot = true;
		return $lobby && $lobbyBot;
	}



	/**
	 *	Удаление модели лобби из базы данных.
	 *	Если модель успешно удалилась, возвращает true.
	 *	Если модель не удалилась, возвращает false.
	 *
	 */
	public function Delete() {
		return parent::deleteModel('\app\models\Lobby');
	}

}
