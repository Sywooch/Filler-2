<?php

namespace app\models\models;

use \DateTime;
use app\models\Game as tableGame;
use app\models\GameDetail as tableGameDetail;

/**
 * Game class file.
 *
 * @author Konstantin Poluektov <poluektovkv@gmail.com>
 * @copyright 2016 Konstantin Poluektov
 *
 */



/**
 * Game manages the game.
 *
 * @property array $ColorMatrix Colour matrix of the playing field.
 * @property date $StartDate Date and time of the start of the game.
 * @property date $FinishDate Date and time of the end of the game.
 * @property string $Comment Comment on the game.
 * @property integer $LobbyID The ID of the lobby.
 * @property integer $WinnerID The ID of the winner.
 * 
 * @author Konstantin Poluektov <poluektovkv@gmail.com>
 *
 */
class Game extends LSD {

	/**
	 *	Цветовая матрица игрового поля.
	 *
	 */
	protected $ColorMatrix; // = array();

	/**
	 *	Дата и время начала игры.
	 *
	 */
	protected $StartDate;

	/**
	 *	Дата и время окончания игры.
	 *
	 */
	protected $FinishDate;

	/**
	 *	Комментарий.
	 *
	 */
	protected $Comment;

	/**
	 *	Идентификатор лобби.
	 *
	 */
	protected $LobbyID;

	/**
	 *	Идентификатор победителя.
	 *
	 */
	protected $WinnerID;



	/**
	 *	
	 *
	 */
	function __construct($LobbyID = null, $SizeX = null, $SizeY = null, $ColorsNumber = null, $PlayersStartingPosition = null, $Comment = null) {
		$this -> LobbyID = $LobbyID;
		$this -> Comment = $Comment;
		// Если заданы размеры матрицы и количество цветов:
		if ($SizeX && $SizeY && $ColorsNumber)
			// Генерация случайной матрицы из цветов в заданном диапазоне.
			$this -> ColorMatrix = $this -> ColorMatrixGeneration($SizeX, $SizeY, $ColorsNumber, $PlayersStartingPosition);
	}



	/**
	 *	Возвращает матрицу индексов цвета.
	 *
	 */
	public function getColorMatrix() {
		return $this -> ColorMatrix;
	}



	/**
	 *	Возвращает дату начала игры в указанном формате.
	 *
	 */
	public function getStartDate($Format = 'Y-m-d H:i:s') {
		$StartDate = new DateTime($this -> StartDate);
		return $StartDate -> format($Format);
	}



	/**
	 *	Возвращает дату окончания игры в указанном формате.
	 *
	 */
	public function getFinishDate($Format = 'Y-m-d H:i:s') {
		$FinishDate = new DateTime($this -> FinishDate);
		return $FinishDate -> format($Format);
	}



	/**
	 *	Возвращает комментарий к игре.
	 *
	 */
	public function getComment() {
		return $this -> Comment;
	}



	/**
	 *	Возвращает идентификатор лобби.
	 *
	 */
	public function getLobbyID() {
		return $this -> LobbyID;
	}



	/**
	 *	Возвращает идентификатор победителя.
	 *
	 */
	public function getWinnerID() {
		return $this -> WinnerID;
	}



	/**
	 *	Возвращает массив всех свойств.
	 *
	 */
	public function getPropertyList() {
		return array(
			'ID' => $this -> ID,
			'ColorMatrix' => $this -> ColorMatrix,
			'StartDate' => $this -> StartDate,
			'FinishDate' => $this -> FinishDate,
			'Comment' => $this -> Comment,
			'LobbyID' => $this -> LobbyID,
			'WinnerID' => $this -> WinnerID,
		);
	}



	/**
	 *	Возвращает продолжительность игры в указанном формате.
	 *	Если игра не завершена, возвращает указанное значение.
	 *
	 */
	public function getDuration($Format = '%H:%I:%S', $BlankLine = '-') {
		// Если игра не завершена:
		if ($this -> FinishDate == null)
			// Возвращает указанное значение.
			return $BlankLine;
		$StartDate = new DateTime($this -> StartDate);
		$FinishDate = new DateTime($this -> FinishDate);
		// Вычисление продолжительности игры.
		$Duration = $FinishDate -> diff($StartDate);
		return $Duration -> format($Format);
	}



	/**
	 *	Возвращает текущую продолжительность игры в секундах.
	 *
	 */
	public function getGameTimer() {
		$StartTime = new DateTime($this -> StartDate);
		$CurrentTime = new DateTime('now');
		return $CurrentTime -> getTimestamp() - $StartTime -> getTimestamp();
	}



	/**
	 *	Возвращает время в секундах, прошедшее с последнего сделанного хода.
	 *
	 */
	public function getMoveTimer() {
		// Получение списка ходов текущей игры.
		$GameMovesList = $this -> getMovesList();
		// Если ни одного хода еще не сделано:
		if (sizeof($GameMovesList) == 0)
			// Возвращает время, прошедшее с начала игры.
			return $this -> getGameTimer();
		$MoveTime = new DateTime($GameMovesList[0]['Date']);
		$CurrentTime = new DateTime('now');
		return $CurrentTime -> getTimestamp() - $MoveTime -> getTimestamp();
	}



	/**
	 *	Возвращает список всех сделанных в игре ходов.
	 *
	 */
	public function getMovesList($Order = 'DESC') {
		// Загрузка из БД всех сделанных ходов для указанной игры.
		// $dbModel = tableGameDetail::model() -> findAllByAttributes(
		// 	array('GameID' => $this -> ID),
		// 	array('order' => 'ID ' . $Order)
		// );
		$dbModel = tableGameDetail::find()
			-> where(['GameID' => $this -> ID])
			-> orderBy('ID ' . $Order);
		// Формирование массива ходов.
		foreach ($dbModel as $Move) {
			$MovesList[] = array(
				// Индекс цвета.
				'ColorIndex' => $Move -> ColorIndex,
				// Количество захваченных ячеек.
				'CellNumber' => $Move -> Points,
				// Время регистрации хода.
				'Date' => $Move -> Date,
				// Идентификатор игрока.
				'PlayerID' => $Move -> PlayerID,
			);
		}
		// Возвращается список ходов.
		return $MovesList;
	}



	/**
	 *	Регистрирует новый ход.
	 *	Сохраняет в БД информацию о новом ходе (индекс цвета, количество баллов, идентификаторы игры и игрока).
	 *
	 */
	public function setMove($ColorIndex, $Points, $PlayerID) {
		// Создание модели хода.
		$dbModel = new tableGameDetail;
		// Если модель создана:
		if ($dbModel !== null) {
			// Копирование свойств (индекс цвета, количество баллов, идентификаторы игры и игрока) в модель.
			$dbModel -> attributes = array(
				'ColorIndex' => $ColorIndex,
				'Points' => $Points,
				'GameID' => $this -> ID,
				'PlayerID' => $PlayerID
			);
			// Сохранение модели в БД.
			return $dbModel -> save();
		}
		return false;
	}



	/**
	 *	Генерация случайной матрицы индексов цвета указанных размеров 
	 *	и в заданном диапазоне цветов.
	 *	Если указан список стартовых позиций игроков, 
	 *	всем домашним ячейкам игроков устанавливаются разные цвета.
	 *
	 */
	protected function ColorMatrixGeneration($SizeX, $SizeY, $ColorsNumber, $PlayersStartingPosition = null) {
		// Заполнение игровой матрицы случайными индексами цветов 
		// из указанного диапазона.
		for ($Index = 0; $Index < $SizeX * $SizeY; $Index++)
			$ColorMatrix[] = rand(1, $ColorsNumber);
		// Если указан список стартовых позиций игроков:
		if (is_array($PlayersStartingPosition)) {
			// Перемешивание стартовых позиций игроков.
			shuffle($PlayersStartingPosition);
			// Установка начального цвета для домашней ячейки игрока.
			$ColorsIndex = 1;
			foreach ($PlayersStartingPosition as $CellIndex) {
				// Установка уникального цвета домашней ячейки игрока.
				$ColorMatrix[$CellIndex - 1] = $ColorsIndex;
				// Слева
				if ((($CellIndex - 1) / $SizeX) != floor(($CellIndex - 1) / $SizeX))
					$ColorMatrix[$CellIndex - 1 - 1] = rand(sizeof($PlayersStartingPosition) + 1, $ColorsNumber);
				// Справа
				if (($CellIndex / $SizeX) != floor($CellIndex / $SizeX))
					$ColorMatrix[$CellIndex - 1 + 1] = rand(sizeof($PlayersStartingPosition) + 1, $ColorsNumber);
				// Сверху
				if (($CellIndex - $SizeX) > 0)
					$ColorMatrix[$CellIndex - 1 - $SizeX] = rand(sizeof($PlayersStartingPosition) + 1, $ColorsNumber);
				// Снизу
				if (($CellIndex + $SizeX) <= ($SizeX * $SizeY))
					$ColorMatrix[$CellIndex - 1 + $SizeX] = rand(sizeof($PlayersStartingPosition) + 1, $ColorsNumber);
				// Переключение цвета домашней ячейки для следующего игрока.
				$ColorsIndex++;
			}
		}
		// Возвращается случайная матрица индексов цвета.
		return $ColorMatrix;
	}



	/**
	 *	Завершает игру.
	 *	Устанавливает результат и время окончания игры.
	 *
	 */
	public function Finish($WinnerID) {
		$this -> WinnerID = $WinnerID;
		$this -> FinishDate = date("Y-m-d H:i:s");
	}



	/**
	 *	Проверяет закончена ли игра.
	 *	Если игра закончена, возвращает true.
	 *	Если игра не закончена, возвращает false.
	 *
	 */
	public function isFinish() {
		// Если дата окончания игры установлена:
		if (is_null($this -> FinishDate))
			return false;
		return true;
	}



	/**
	 *	Загружает игру по указанному идентификатору лобби $LobbyID.
	 *	Если игра успешно загружена, возвращает true.
	 *	Если игра не загружена, возвращает false.
	 *
	 */
	public function Search($LobbyID) {
		// Поиск игры в БД по указанному идентификатору лобби.
		$dbModel = tableGame::findOne([
			'LobbyID' => $LobbyID
		]);
		// Если игра найдена:
		if ($dbModel !== null) {
			// Если модель загрузилась:
			if ($this -> Load($dbModel -> ID)) {
				// Инициализация идентификатора игры.
				$this -> ID = $dbModel -> ID;
				return true;
			}
		}
		return false;
	}



	/**
	 *	Загрузка модели игры из базы данных.
	 *	Если модель успешно загрузилась, возвращает true.
	 *	Если модель не загрузилась, возвращает false.
	 *
	 */
	public function Load($ID, $AutoSave = true) {
		$this -> AutoSave = $AutoSave;
		$Result = parent::loadModel($ID, '\app\models\Game', array(
			'ColorMatrix',
			'StartDate',
			'FinishDate',
			'Comment',
			'LobbyID',
			'WinnerID'
		));
		$this -> ColorMatrix = unserialize($this -> ColorMatrix);
		return $Result;
	}



	/**
	 *	Сохранение модели игры в базу данных.
	 *	Если модель успешно сохранилась, возвращает true.
	 *	Если модель не сохранилась, возвращает false.
	 *
	 */
	public function Save() {
		return parent::saveModel('\app\models\Game', array(
			'ColorMatrix' => serialize($this -> ColorMatrix),
			'FinishDate' => $this -> FinishDate,
			'Comment' => $this -> Comment,
			'LobbyID' => $this -> LobbyID,
			'WinnerID' => $this -> WinnerID,
		));
	}



	/**
	 *	Удаление модели игры из базы данных.
	 *	Если модель успешно удалилась, возвращает true.
	 *	Если модель не удалилась, возвращает false.
	 *
	 */
	public function Delete() {
		return parent::deleteModel('\app\models\Game');
	}

}
