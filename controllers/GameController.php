<?php

namespace app\controllers;

use Yii;
use app\components\GameException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\helpers\Url;
// use yii\web\View;
use yii\filters\VerbFilter;
use app\assets\IndexAsset;
use app\assets\ThemesAsset;
use app\components\ExtController;
use app\models\User;
use app\models\models\Player;
use app\models\models\Lobby;
use app\models\models\Game;

// use app\models\User;
use app\components\EmailNotification;
use app\components\UserIdentity;
// use app\models\models\User;
// use app\models\User;
// use app\models\ContactForm;

/**
 *	Игровой контроллер.
 *	Выводит основное представление для игры.
 *	Обрабатывает все AJAX-запросы по ведению игры, получению списков лобби и игроков.
 *	Для доступа требуется авторизация.
 *
 */
class GameController extends ExtController {

	/**
	 *	Макет для представлений контроллера.
	 *
	 */
	const GAME_LAYOUT = '/game';

	/**
	 *	Временной интервал активности игрока (секунды).
	 *
	 */
	const ACTIVE_PLAYER_TIME_INTERVAL = 20;

	/**
	 *	Временной интервал активности лобби (секунды).
	 *
	 */
	const ACTIVE_LOBBY_TIME_INTERVAL = 3600;

	/**
	 *	Максимальный таймаут игрока во время игры (секунды).
	 *
	 */
	const MAXIMUM_PLAYER_TIMEOUT = 120;

	/**
	 *	Временной интервал паузы между запросами к БД 
	 *	для AJAX Long-Polling запросов (секунды).
	 *
	 */
	const SLEEP_INTERVAL = 2;

	/**
	 *	Код успешного результата.
	 *
	 */
	const SUCCESS = 1;

	/**
	 *	Код ошибки истекшего времени.
	 *
	 */
	const EXPIRE_ERROR = 'EXPIRE_ERROR';

	/**
	 *	Код ошибки данных.
	 *
	 */
	const DATA_ERROR = 'DATA_ERROR';

	/**
	 *	Код ошибки.
	 *
	 */
	const ERROR = 0;
	


	/**
	 *	Макет для представлений контроллера.
	 *
	 */
	public $layout = self::GAME_LAYOUT;



	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['game', 'lobbieslistget'],
				'rules' => [
					// Список actions, доступных не авторизованным пользователям.
					[
						'actions' => [
							'game', 'lobbieslistget'
						],
						'allow' => false,
						'roles' => ['?'],
					],
					// Список actions, доступных авторизованным пользователям.
					[
						'actions' => [
							'game', 'lobbieslistget'
						],
						'allow' => true,
						'roles' => ['@'],
					]
				],
			],
		];
	}



	/**
	 *	Устанавливает фильтры.
	 *	Включен фильтр контроля доступа.
	 *
	 */
	// public function filters() {
	// 	return array(
	// 		// Фильтр для AJAX-запросов.
	// 		'ajaxOnly + MoveSet',
	// 		'ajaxOnly + MoveGet',
	// 		// Фильтр контроля доступа.
	// 		'accessControl'
	// 	);
	// }



	/**
	 *	Устанавливает правила контроля доступа.
	 *	Всем авторизованным пользователям разрешен доступ ко всем действиям.
	 *	Неавторизованным пользователям доступ ко всем действиям запрещен.
	 *
	 */
	// public function accessRules() {
	// 	return array(
	// 		// Разрешение доступа ко всем действиям всем авторизованным пользователям.
	// 		array('allow',
	// 			'users' => array('@'),
	// 		),
	// 		// Отклонение доступа всем остальным пользователям.
	// 		array('deny',
	// 			'users' => array('*'),
	// 			'deniedCallback' => array($this, 'DeniedRedirect'),
	// 		),
	// 	);
	// }



	/**
	 *	Запускает игру.
	 *
	 */
	public function actionGame() {
		// $jsFilePath = './protected/js/game/';
		// // Подключение и публикация скрипта контроллера.
		// Yii::app() -> clientScript -> registerScriptFile(
		// 	Yii::app() -> assetManager -> publish(
		// 		(YII_DEBUG ? $jsFilePath . 'GameController.js' : $jsFilePath . 'GameController.min.js')
		// 	)
		// );
		// // Подключение и публикация скрипта модели.
		// Yii::app() -> clientScript -> registerScriptFile(
		// 	Yii::app() -> assetManager -> publish(
		// 		(YII_DEBUG ? $jsFilePath . 'GameModel.js' : $jsFilePath . 'GameModel.min.js')
		// 	)
		// );
		// // Подключение и публикация скрипта представления.
		// Yii::app() -> clientScript -> registerScriptFile(
		// 	Yii::app() -> assetManager -> publish(
		// 		(YII_DEBUG ? $jsFilePath . 'GameView.js' : $jsFilePath . 'GameView.min.js')
		// 	)
		// );
		// 
		$Player = new Player();
		// Если указанный игрок существует:
		if ($Player -> Load(1)) // Yii::$app -> user -> getId()
			// Получение основных данных игрока.
			$PlayerPropertyList = $Player -> getPropertyList();
		// Передача скрипту данных собственного игрока, диалогов и информации по загрузке игры.
		Yii::$app -> view -> registerJs(
			//  . Yii::$app -> user -> getId() . 
			"var PlayerID = " . 1 . ";
			var Player = " . json_encode($PlayerPropertyList) . ";
			var BASE_URL = '" . Yii::$app -> request -> baseUrl . "';
			var DIALOG = " . json_encode($this -> getDialogMessages()) . ";
			var LoadGame = " . json_encode($this -> GameLoad()) . ";",
			yii\web\View::POS_HEAD
		);
		// Вывод представления.
		return $this -> render('game', array(
			'PlayerID' => Yii::$app -> user -> getId()
		));
	}



	/**
	 *	Возвращает диалоговые сообщения (заголовок, текст, кнопки, индикатор загрузки).
	 *
	 */
	private function getDialogMessages() {
		// Получение пути к файлу с набором шаблонов.
		$FilePath = realpath(Yii::$app -> params['DialogLayout']);
		// Если файл с набором шаблонов не найден:
		if ($FilePath === false)
			throw new GameException('Не удается открыть файл с набором шаблонов диалоговых окон.');
		// Получение набора шаблонов диалоговых окон.
		$DialogLayout = require($FilePath);
		return $DialogLayout;
	}



	/**
	 *	Возвращает сводную информацию по незавершенной игре.
	 *
	 */
	private function GameLoad() {
		// Загрузка текущего игрока.
		$Player = new Player();
		$Player -> Load(Yii::$app -> user -> getId());
		// Получение идентификатора последнего лобби для текущего игрока.
		$LobbyID = $Player -> getLastLobbyID();
		$Lobby = new Lobby();
		// Загрузка последнего лобби для текущего игрока.
		$Lobby -> Load($LobbyID);
		$Game = new Game();
		// Поиск игры по указанному лобби.
		$Game -> Search($Lobby -> getID());
		// Если в указанном интервале времени найдена незавершенная игра:
		if ($Player -> getGameTimeout() < self::MAXIMUM_PLAYER_TIMEOUT && !$Game -> isFinish() && $Game -> getID())
			// Формирование и возврат массива сводной информации по игре.
			return $this -> getGameData($Lobby, $Game);
		// Если в указанном интервале времени незавершенная игра не найдена:
		else
			return FALSE;
	}



	/**
	 *	Работает в формате AJAX-запросов от клиента.
	 *	Получает запрос на регистрацию маркера для указанного игрока $_POST['PlayerID'].
	 *	Регистрирует в БД маркер указанного игрока.
	 *
	 *	Формирует и возвращает список игроков с текущими статусами таймаутов в формате JSON.
	 *	Если соперник еще в игре, статус таймаута равен true, иначе false.
	 *	Если возникла ошибка, возвращает код ошибки.
	 *
	 */
	public function actionGamemarker() {
		// Получение из запроса идентификатора игрока.
		$PlayerID = Yii::app() -> request -> getPost('PlayerID');
		$Player = new Player();
		// Если тип запроса AJAX:
		if (Yii::app() -> request -> isAjaxRequest) {
			// Если указанный игрок существует:
			if ($Player -> Load($PlayerID)) {
				// Если маркер указанного игрока успешно установлен:
				if ($Player -> setGameMarker()) {
					// Получение идентификатора последнего лобби указанного игрока.
					$Lobby = new Lobby();
					$LobbyID = $Player -> getLastLobbyID();
					// Если указанное лобби найдено:
					if ($Lobby -> Load($LobbyID)) {
						// Получение списка игроков указанного лобби.
						$PlayersPropertyList = $Lobby -> getPlayersList();
						$PlayersList = array();
						foreach ($PlayersPropertyList as $PlayerProperty) {
							// Если текущий игрок найден:
							if ($Player -> Load($PlayerProperty['ID'])) {
								// Если таймаут игрока в пределах допустимого:
								if ($Player -> getGameTimeout() < self::MAXIMUM_PLAYER_TIMEOUT && $Player -> getLastLobbyID() == $LobbyID)
									$PlayersList[$Player -> getID()] = true;
								else
									$PlayersList[$Player -> getID()] = false;
							}
						}
						// Возвращается список игроков с текущими статусами.
						echo(json_encode($PlayersList));
					}
					// Если возникла ошибка:
					else
						echo(json_encode(array('Error' => self::DATA_ERROR)));
				}
				// Если возникла ошибка:
				else
					echo(json_encode(array('Error' => self::DATA_ERROR)));
			}
			// Если возникла ошибка:
			else
				echo(json_encode(array('Error' => self::DATA_ERROR)));
			Yii::app() -> end();
		}
		else {
			// $this -> render();
		}
	}



	/**
	 *	Работает в формате AJAX-запросов от клиента.
	 *	Получает запрос на регистрацию хода в указанной игре $_POST['GameID'],
	 *	указанным игроком $_POST['PlayerID'], с указанным индексом цвета $_POST['ColorIndex'] 
	 *	и указанным количеством ячеек $_POST['CellNumber'].
	 *	Регистрирует в БД сделанный ход.
	 *	Если ход успешно зарегистрирован, возвращает Error = false, иначе возвращает код ошибки.
	 *
	 */
	public function actionMoveset() {
		// Получение из запроса идентификаторов игры и игрока, индекса цвета и количества ячеек.
		$GameID = Yii::app() -> request -> getPost('GameID');
		$PlayerID = Yii::app() -> request -> getPost('PlayerID');
		$ColorIndex = Yii::app() -> request -> getPost('ColorIndex');
		$CellNumber = Yii::app() -> request -> getPost('CellNumber');
		$Game = new Game();
		// Если тип запроса AJAX:
		if (Yii::app() -> request -> isAjaxRequest) {
			// Если указанная игра найдена и ход успешно зарегистрирован:
			if ($Game -> Load($GameID) && $Game -> setMove($ColorIndex, $CellNumber, $PlayerID))
				// Возвращает Error = false.
				echo(json_encode(array('Error' => false)));
			// Если возникла ошибка:
			else
				echo(json_encode(array('Error' => self::DATA_ERROR)));
			Yii::app() -> end();
		}
		else {
			// $this -> render();
		}
	}



	/**
	 *	Работает в формате AJAX Long-Polling запросов от клиента.
	 *	Получает запрос информации о последнем сделанном ходе 
	 *	в указанной игре $_POST['GameID'], указанным игроком $_POST['CompetitorID'].
	 *	Если ход сделан, возвращает данные хода в формате JSON.
	 *	Если время ожидания хода истекло, возвращает код ошибки.
	 *	Если возникла ошибка данных, возвращает код ошибки.
	 *
	 */
	public function actionMoveget() {
		// Получение из запроса идентификаторов игры и игрока.
		$GameID = Yii::app() -> request -> getPost('GameID');
		$CompetitorID = Yii::app() -> request -> getPost('CompetitorID');
		$Game = new Game();
		// Если тип запроса AJAX:
		if (Yii::app() -> request -> isAjaxRequest) {
			// Если указанная игра найдена:
			if ($Game -> Load($GameID, FALSE)) {
				// Установка ограничения времени выполнения запроса.
				set_time_limit(100);
				// Установка количества циклов ожидания.
				$Timeout = 30;
				// Закрытие сессии, чтобы обрабатывались другие запросы.
				Yii::app() -> session -> close();
				// Ожидание хода соперника, пока не истекло время ожидания.
				do {
					$Timeout--;
					sleep(self::SLEEP_INTERVAL);
					// Получение списка ходов для данной игры.
					$GameMovesList = $Game -> getMovesList();
				}
				while ($Timeout > 0 && ($GameMovesList === NULL || $GameMovesList[0]['PlayerID'] != $CompetitorID));
				// Открытие сессии.
				Yii::app() -> session -> open();
				// Если время ожидания хода истекло:
				if ($Timeout == 0)
					// Возвращает код ошибки.
					echo(json_encode(array('Error' => self::EXPIRE_ERROR)));
				else
					// Возвращает данные хода.
					echo(json_encode(array(
						// Индекс цвета.
						'ColorIndex' => $GameMovesList[0]['ColorIndex'],
						// Комментарий к ходу.
						'Comment' => 'None',
						'Error' => false
					)));
			}
			// Если указанная игра не найдена:
			else
				// Возвращает код ошибки.
				echo(json_encode(array('Error' => self::DATA_ERROR)));
			Yii::app() -> end();
		}
		else {
			// $this -> render();
		}
	}



	/**
	 *	Работает в формате AJAX-запросов от клиента.
	 *	Получает запрос на начало игры с указанным идентификатором лобби $_POST['LobbyID']
	 *	Регистрирует в БД новую игру.
	 *	Если игра успешно зарегистрирована, возвращает данные новой игры в формате JSON.
	 *	Если возникла ошибка, возвращает код ошибки.
	 *
	 */
	public function actionGamestart() {
		// Получение из запроса идентификатора лобби.
		$LobbyID = Yii::$app -> request -> post('LobbyID');
		$Lobby = new Lobby();
		// Если тип запроса AJAX:
		if (Yii::$app -> request -> isAjax) {
			// Если указанное лобби найдено:
			if ($Lobby -> Load($LobbyID)) {
				$Game = new Game(
					$LobbyID, 
					$Lobby -> getSizeX(), 
					$Lobby -> getSizeY(), 
					$Lobby -> getColorsNumber(),
					$Lobby -> getPlayersStartingPosition()
				);
				// Если новая игра успешно зарегистрирована в БД и статус лобби изменен:
				if ($Game -> Save() && $Lobby -> setStatus(2)) {
					// Получение данных игры.
					$GameData = $this -> getGameData($Lobby, $Game);
					// Регистрация временных меток всех участников игры.
					$Player = new Player();
					foreach ($GameData['PlayersList'] as $PlayerData) {
						// Если игрок найден:
						if ($Player -> Load($PlayerData['ID']))
							// Регистрация временной метки игрока.
							$Player -> setGameMarker();
					}
					// Возвращает данные игры.
					echo(json_encode($GameData));
				}
				// Если возникла ошибка:
				else
					echo(json_encode(array('Error' => self::DATA_ERROR)));
			}
			// Если возникла ошибка:
			else
				echo(json_encode(array('Error' => self::DATA_ERROR)));
			Yii::$app -> end();
		}
		else {
			// $this -> render();
		}
	}



	/**
	 *	Работает в формате AJAX Long-Polling запросов от клиента.
	 *	Получает запрос наличия начатой игры для указанного лобби $_POST['LobbyID'].
	 *	Если игра для указанного лобби началась, возвращает данные новой игры в формате JSON.
	 *	Если время ожидания начала игры истекло, возвращает код ошибки.
	 *	Если возникла ошибка, возвращает код ошибки.
	 *
	 */
	public function actionGameget() {
		// Получение из запроса идентификатора лобби.
		$LobbyID = Yii::$app -> request -> post('LobbyID');
		$Game = new Game();
		$Lobby = new Lobby();
		// Если тип запроса AJAX:
		if (Yii::$app -> request -> isAjax) {
			// Установка ограничения времени выполнения запроса.
			set_time_limit(100);
			// Установка количества циклов ожидания.
			$Timeout = 15;
			// Закрытие сессии, чтобы обрабатывались другие запросы.
			Yii::$app -> session -> close();
			// Ожидание начала новой игры по указанному лобби, пока не истекло время ожидания.
			while ($Timeout > 0 && !$Game -> Search($LobbyID)) {
				$Timeout--;
				sleep(self::SLEEP_INTERVAL);
			}
			// Открытие сессии.
			Yii::$app -> session -> open();
			// Если время ожидания начала новой игры истекло:
			if ($Timeout == 0)
				// Возвращает код ошибки.
				echo(json_encode(array('Error' => self::EXPIRE_ERROR)));
			else {
				// Если указанное лобби найдено:
				if ($Lobby -> Load($LobbyID)) {
					// Получение данных игры.
					$GameData = $this -> getGameData($Lobby, $Game);
					// Возвращает данные игры.
					echo(json_encode($GameData));
				}
				else
					// Возвращает код ошибки.
					echo(json_encode(array('Error' => self::DATA_ERROR)));
			}
			Yii::$app -> end();
		}
		else {
			// $this -> render();
		}
	}



	/**
	 *	Работает в формате AJAX-запросов от клиента.
	 *	Получает запрос на завершение игры с указанным идентификатором $_POST['GameID']
	 *	и идентификатором победителя $_POST['WinnerID'].
	 *	Регистрирует в БД окончание и результат игры.
	 *	Если завершение игры успешно зарегистрировано в БД, возвращает 1. 
	 *	Если возникла ошибка, возвращает 0.
	 *
	 */
	public function actionGamefinish() {
		// Получение из запроса идентификатора игры и победителя игры.
		$GameID = Yii::$app -> request -> post('GameID');
		$WinnerID = Yii::$app -> request -> post('WinnerID');
		$Game = new Game();
		// Если тип запроса AJAX:
		if (Yii::$app -> request -> isAjax) {
			// Если указанная игра найдена:
			if ($Game -> Load($GameID)) {
				// Игра завершается с указанным победителем.
				$Game -> Finish($WinnerID);
				// Если завершение игры успешно зарегистрировано в БД:
				if ($Game -> Save())
					echo(self::SUCCESS);
				// Если возникла ошибка:
				else
					echo(self::ERROR);
			}
			// Если игра не найдена:
			else
				echo(self::ERROR);
			Yii::$app -> end();
		}
		else {
			// $this -> render();
		}
	}



	/**
	 *	Возвращает основные параметры указанного лобби и игры.
	 *	В качестве аргументов получает объекты класса Lobby и Game.
	 *
	 */
	protected function getGameData($Lobby, $Game) {
		// Проверка соответствия типа объекта:
		if (!$Lobby instanceof Lobby)
			throw new GameException('Первый аргумент не является объектом класса Lobby.');
		// Проверка соответствия типа объекта:
		if (!$Game instanceof Game)
			throw new GameException('Второй аргумент не является объектом класса Game.');
		// Возвращает массив данных указанного лобби и игры.
		return array(
			'LobbyID' => $Lobby -> getID(),
			'GameID' => $Game -> getID(),
			'ColorMatrix' => $Game -> getColorMatrix(),
			'PlayersList' => $Lobby -> getPlayersList(),
			'PlayersPosition' => $Lobby -> getPlayersStartingPosition(),
			'FirstMove' => $Lobby -> getCreatorID(),
			'ColorsNumber' => $Lobby -> getColorsNumber(),
			'SizeX' => $Lobby -> getSizeX(),
			'SizeY' => $Lobby -> getSizeY(),
			'MovesOrder' => NULL,
			'MovesList' => $Game -> getMovesList('ASC'),
			'GameTimer' => $Game -> getGameTimer(),
			'MoveTimer' => $Game -> getMoveTimer()
		);
	}



	/**
	 *	Работает в формате AJAX-запросов от клиента.
	 *	Получает запрос на регистрацию лобби от указанного игрока $_POST['PlayerID']
	 *	и с указанными параметрами: 
	 *		$_POST['Name'], 
	 *		$_POST['SizeX'], 
	 *		$_POST['SizeY'], 
	 *		$_POST['ColorsNumber'], 
	 *		$_POST['PlayersNumber'].
	 *
	 *	Регистрирует в БД лобби.
	 *	Возвращает информацию о зарегистрированном лобби в формате JSON.
	 *	Если возникла ошибка, возвращает код ошибки.
	 *
	 */
	public function actionLobbycreate() {
		// Получение из запроса идентификатора игрока.
		$PlayerID = Yii::$app -> request -> post('PlayerID');
		// Получение из запроса параметров лобби.
		$Name = Yii::$app -> request -> post('Name');
		$SizeX = Yii::$app -> request -> post('SizeX');
		$SizeY = Yii::$app -> request -> post('SizeY');
		$ColorsNumber = Yii::$app -> request -> post('ColorsNumber');
		$PlayersNumber = Yii::$app -> request -> post('PlayersNumber');
		// Создание нового лобби с указанными параметрами.
		$Lobby = new Lobby($Name, $SizeX, $SizeY, $ColorsNumber, $PlayersNumber, $PlayerID);
		// Если тип запроса AJAX:
		if (Yii::$app -> request -> isAjax) {
			// Если лобби успешно зарегистрировано в БД:
			if ($Lobby -> Save()) {
				// Добавление автора лобби в список игроков.
				$Lobby -> AddPlayer($PlayerID);
				// Возвращает информацию о зарегистрированном лобби.
				echo(json_encode($Lobby -> getPropertyList()));
			}
			// Если возникла ошибка:
			else
				// Возвращает код ошибки.
				echo(json_encode(array('Error' => self::DATA_ERROR)));
			Yii::$app -> end();
		}
		else {
			// $this -> render();
		}
	}



	/**
	 *	Работает в формате AJAX Long-Polling запросов от клиента.
	 *	Получает запрос подключения нового игрока к указанному лобби $_POST['LobbyID'].
	 *	Если новый игрок подключился к лобби, возвращает информацию о лобби в формате JSON.
	 *	Если время ожидания подключения игрока истекло, возвращает код ошибки.
	 *	Если возникла ошибка, возвращает код ошибки.
	 *
	 */
	public function actionLobbyresult() {
		// Получение из запроса идентификатора лобби и текущего количества подключившихся игроков.
		$LobbyID = Yii::$app -> request -> post('LobbyID');
		$PlayersNumber = Yii::$app -> request -> post('PlayersNumber');
		$Lobby = new Lobby();
		$Player = new Player();
		// Если тип запроса AJAX:
		if (Yii::$app -> request -> isAjax) {
			// Если указанное лобби найдено:
			if ($Lobby -> Load($LobbyID)) {
				// Закрытие сессии, чтобы обрабатывались другие запросы.
				Yii::$app -> session -> close();
				// Ожидание подключения нового игрока к указанному лобби, пока не истек срок действия лобби.
				while ($Lobby -> getCurrentPlayersNumber() == $PlayersNumber && $Lobby -> isActive()) {
					set_time_limit(30);
					sleep(self::SLEEP_INTERVAL);
				}
				// Открытие сессии.
				Yii::$app -> session -> open();
				// Возвращает информацию о лобби.
				echo(json_encode($Lobby -> getPropertyList()));
			}
			// Если возникла ошибка:
			else
				// Возвращает код ошибки.
				echo(json_encode(array('Error' => self::DATA_ERROR)));
			Yii::$app -> end();
		}
		else {
			// $this -> render();
		}
	}



	/**
	 *	Работает в формате AJAX-запросов от клиента.
	 *	Получает запрос на подключение указанного игрока $_POST['PlayerID'] 
	 *	к указанному лобби $_POST['LobbyID'].
	 *	Если указанный игрок успешно подключен к лобби, возвращает информацию о лобби в формате JSON.
	 *	Если возникла ошибка, возвращает код ошибки.
	 *
	 */
	public function actionLobbyjoin() {
		// Получение из запроса идентификаторов лобби и игрока.
		$LobbyID = Yii::$app -> request -> post('LobbyID');
		$PlayerID = Yii::$app -> request -> post('PlayerID');
		$Lobby = new Lobby();
		// Если тип запроса AJAX:
		if (Yii::$app -> request -> isAjax) {
			// Если указанное лобби найдено:
			if ($Lobby -> Load($LobbyID)) {
				// Если указанный игрок успешно подключен к лобби:
				if ($Lobby -> AddPlayer($PlayerID))
					// Возвращает информацию о лобби.
					echo(json_encode($Lobby -> getPropertyList()));
				// Если возникла ошибка:
				else
					// Возвращает код ошибки.
					echo(json_encode(array('Error' => self::DATA_ERROR)));
			}
			// Если возникла ошибка:
			else
				// Возвращает код ошибки.
				echo(json_encode(array('Error' => self::DATA_ERROR)));
			Yii::$app -> end();
		}
		else {
			// $this -> render();
		}
	}



	/**
	 *	Работает в формате AJAX-запросов от клиента.
	 *	Получает запрос на получение информации об указанном лобби $_POST['LobbyID'].
	 *	Если указанное лобби найдено, возвращает информацию о лобби в формате JSON.
	 *	Если возникла ошибка, возвращает код ошибки.
	 *
	 */
	public function actionLobbyload() {
		// Получение из запроса идентификатора лобби.
		$LobbyID = Yii::$app -> request -> post('LobbyID');
		$Lobby = new Lobby();
		// Если тип запроса AJAX:
		if (Yii::$app -> request -> isAjax) {
			// Если указанное лобби найдено:
			if ($Lobby -> Load($LobbyID))
				// Возвращает информацию о лобби.
				echo(json_encode($Lobby -> getPropertyList()));
			// Если возникла ошибка:
			else
				// Возвращает код ошибки.
				echo(json_encode(array('Error' => self::DATA_ERROR)));
			Yii::$app -> end();
		}
		else {
			// $this -> render();
		}
	}



	/**
	 *	Работает в формате AJAX-запросов от клиента.
	 *	Получает запрос информации об указанном игроке $_POST['PlayerID'].
	 *	Возвращает информацию об игроке (идентификатор, имя, количество игр, 
	 *	количество побед, количество поражений, количество ничьих, рейтинг) в формате JSON.
	 *	Если указанный игрок не найден, возвращает код ошибки.
	 *
	 */
	public function actionPlayerget() {
		// Получение из запроса идентификатора игрока.
		$PlayerID = Yii::$app -> request -> post('PlayerID');
		$Player = new Player();
		// Если тип запроса AJAX:
		if (Yii::$app -> request -> isAjax) {
			// Если указанный игрок найден:
			if ($Player -> Load($PlayerID)) {
				// Возвращается сводная информация по указанному игроку в формате JSON.
				echo(json_encode($Player -> getStatistics()));
			}
			// Если игрок не найден:
			else
				// Возвращает код ошибки.
				echo(json_encode(array('Error' => self::DATA_ERROR)));
			Yii::$app -> end();
		}
		else {
			// $this -> render();
		}
	}



	/**
	 *	Работает в формате AJAX-запросов от клиента.
	 *	Получает запрос информации об активных в текущий момент соперниках. 
	 *	В качестве параметра передается идентификатор собственного игрока $_POST['PlayerID'].
	 *	Возвращает список активных соперников в формате JSON.
	 *	Если активные соперники не найдены, возвращает пустой массив.
	 *
	 */
	public function actionAvailableplayersget() {
		// Получение из запроса идентификатора игрока.
		$PlayerID = Yii::$app -> request -> post('PlayerID');
		$Player = new Player();
		// Если тип запроса AJAX:
		if (Yii::$app -> request -> isAjax) {
			// Если указанный игрок найден:
			if ($Player -> Load($PlayerID)) {
				// Регистрация маркера активности игрока.
				$Player -> setActivityMarker();
				// Возвращается список активных соперников.
				echo(json_encode($Player -> getAvailableCompetitors(self::ACTIVE_PLAYER_TIME_INTERVAL)));
			}
			// Если игрок не найден:
			else
				// Возвращается код ошибки.
				echo(json_encode(array('Error' => self::DATA_ERROR)));
			Yii::$app -> end();
		}
		else {
			// $this -> render();
		}
	}



	/**
	 *	Работает в формате AJAX-запросов от клиента.
	 *	Получает запрос информации о действующих лобби в текущий момент. 
	 *	В качестве параметра передается идентификатор собственного игрока $_POST['PlayerID'].
	 *	Возвращает список действующих лобби в формате JSON.
	 *	Если действующие лобби не найдены, возвращает пустой массив.
	 *
	 */
	public function actionLobbieslistget() {
		// Получение из запроса идентификатора игрока.
		$PlayerID = Yii::$app -> request -> post('PlayerID');
		$Player = new Player();
		// Если тип запроса AJAX:
		if (Yii::$app -> request -> isAjax) {
			// Если указанный игрок найден:
			if ($Player -> Load($PlayerID))
				// Возвращается список действующих лобби.
				echo(json_encode($Player -> getLobbiesList(self::ACTIVE_LOBBY_TIME_INTERVAL)));
			// Если игрок не найден:
			else
				// Возвращается код ошибки.
				echo(json_encode(['Error' => self::DATA_ERROR]));
			Yii::$app -> end();
		}
		else {
			// $this -> render();
		}
	}

}
