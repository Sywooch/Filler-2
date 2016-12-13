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
	protected $ColorMatrix = [];

	/**
	 *	Игровое поле.
	 *
	 */
	protected $playingField = [];

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
	 *	Лобби.
	 *
	 */
	protected $lobby;



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
		// Если указано лобби:
		if ($this -> LobbyID)
			// Инициализация текущей игры.
			$this -> init();
	}



	/**
	 *	Инициализация.
	 *
	 */
	protected function init() {
		//
		$this -> lobby = new Lobby();
		//
		if ($this -> lobby -> Load($this -> LobbyID)) {
			// Инициализация игрового поля.
			$this -> playingField = [];
			$index = $this -> lobby -> getSizeX() * $this -> lobby -> getSizeY();
			while ($index--) {
				$this -> playingField[] = 0;
			}
			// Расстановка игроков на игровом поле.
			$playersList = $this -> lobby -> getPlayersList();
			$startingPositionsList = $this -> lobby -> getPlayersStartingPosition();
			foreach ($startingPositionsList as $index => $startingPosition) {
				$this -> playingField[$startingPosition - 1] = $playersList[$index]['ID'];
			}
			return true;
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
	 *	Присоединение смежных ячеек указанного цвета к территории указанного игрока.
	 *
	 */
	protected function adjacentCellsAccession($playerID, $colorIndex) {
		// Список индексов смежных ячеек.
		$adjacentCellsList = $this -> adjacentCellsListGet($playerID, $colorIndex);
		// Присоединение всех смежных ячеек к территории указанного игрока.
		foreach ($adjacentCellsList as $cellIndex) {
			// Присоединение указанной ячейки к территории указанного игрока.
			$this -> playingField[$cellIndex - 1] = $playerID;
		}
		// Если есть присоединенные ячейки:
		if (count($adjacentCellsList))
			// Изменение цвета всех ячеек игрока.
			$this -> playerFieldReindex($playerID, $colorIndex);
		// Возвращается количество присоединенных ячеек.
		return count($adjacentCellsList);
	}



	/**
	 *	Переиндексация (перекрашивание) всей территории указанного игрока в указанный цвет.
	 *
	 */
	protected function playerFieldReindex($playerID, $colorIndex) {
		// Проверка всех ячеек игрового поля.
		for ($index = 0; $index < count($this -> playingField); $index++) {
			// Если ячейка принадлежит указанному игроку:
			if ($this -> playingField[$index] == $playerID)
				// Установка указанной ячейке указанного индекса цвета.
				$this -> ColorMatrix[$index] = $colorIndex;
		}
	}



	/**
	 *	Получение списка индексов смежных ячеек для указанного цвета и игрока.
	 *	
	 */
	protected function adjacentCellsListGet($playerID, $colorIndex) {
		// Копия состояния игрового поля.
		$playingField = $this -> playingField;
		// Флаг наличия новых присоединенных ячеек.
		$flag = true;
		// Количество присоединенных ячеек указанным игроком.
		$adjacentCellsList = [];
		// Сканирование продолжается пока в каждом цикле указанному игроку 
		// добавляется хотя бы одна новая ячейка.
		while ($flag) {
			$flag = false;
			// Проверка всех ячеек игрового поля.
			for ($index = 0; $index < count($playingField); $index++) {
				// Если указанная ячейка имеет указанный цвет и граничит с полем указанного игрока:
				if ($this -> freeCellCheck($index + 1, $colorIndex, $playerID, $playingField)) {
					// Добавление индекса указанной ячейки в список.
					$adjacentCellsList[] = $index + 1;
					// Присоединение указанной ячейки к территории указанного игрока.
					$playingField[$index] = $playerID;
					// Установка флага наличия новых присоединенных ячеек.
					$flag = true;
				}
			}
		}
		return $adjacentCellsList;
	}



	/**
	 *	Получение количества смежных ячеек для указанного цвета и игрока.
	 *
	 */
	protected function adjacentCellsNumberGet($playerID, $colorIndex) {
		$adjacentCellsList = $this -> adjacentCellsListGet($playerID, $colorIndex);
		return count($adjacentCellsList);
	}



	/**
	 *	Проверка ячейки на возможность захвата.
	 *	Если указанная ячейка свободна, является смежной для указанного игрока 
	 *	и ее цвет совпадает с указанным цветом, возвращается true, иначе false.
	 *
	 */
	protected function freeCellCheck($cellIndex, $colorIndex, $playerID, $playingField) {
		// Результат проверки ячейки.
		$checkResult = false;
		// Если указанная ячейка свободна и ее цвет совпадает с указанным цветом:
		if (!$playingField[$cellIndex - 1] && $this -> ColorMatrix[$cellIndex - 1] == $colorIndex) {
			// Получение списка смежных ячеек для указанной ячейки.
			$adjacentCellIndexList = $this -> adjacentCellIndexGet($cellIndex);
			// Проверка всех смежных ячеек.
			foreach ($adjacentCellIndexList as $value) {
				// Если текущая смежная ячейка принадлежит указанному игроку:
				if ($playingField[$value - 1] == $playerID)
					$checkResult = true;
			}
		}
		return $checkResult;
	}



	/**
	 *	Получение списка смежных ячеек для указанной ячейки.
	 *	Возвращается массив индексов ячеек.
	 *
	 */
	protected function adjacentCellIndexGet($cellIndex, $direction = null) {
		// Список индексов смежных ячеек для указанной ячейки.
		$adjacentCellIndexList = [];
		// Если для указанной ячейки требуются все смежные ячейки или только смежная ячейка слева
		// и данная ячейка существует (не левый край игрового поля):
		if (($direction === null || $direction == 'left') && ((($cellIndex - 1) / $this -> lobby -> getSizeX()) != floor(($cellIndex - 1) / $this -> lobby -> getSizeX())))
			// Добавление индекса смежной ячейки в список.
			$adjacentCellIndexList[] = $cellIndex - 1;
		// Если для указанной ячейки требуются все смежные ячейки или только смежная ячейка справа
		// и данная ячейка существует (не правый край игрового поля):
		if (($direction === null || $direction == 'right') && (($cellIndex / $this -> lobby -> getSizeX()) != floor($cellIndex / $this -> lobby -> getSizeX())))
			$adjacentCellIndexList[] = $cellIndex + 1;
		// Если для указанной ячейки требуются все смежные ячейки или только смежная ячейка сверху
		// и данная ячейка существует (не верхний край игрового поля):
		if (($direction === null || $direction == 'top') && (($cellIndex - $this -> lobby -> getSizeX()) > 0))
			$adjacentCellIndexList[] = $cellIndex - $this -> lobby -> getSizeX();
		// Если для указанной ячейки требуются все смежные ячейки или только смежная ячейка снизу
		// и данная ячейка существует (не нижний край игрового поля):
		if (($direction === null || $direction == 'bottom') && (($cellIndex + $this -> lobby -> getSizeX()) <= ($this -> lobby -> getSizeX() * $this -> lobby -> getSizeY())))
			$adjacentCellIndexList[] = $cellIndex + $this -> lobby -> getSizeX();
		// Возвращается список индексов смежных ячеек для указанной ячейки.
		return $adjacentCellIndexList;
	}



	/**
	 *	Загрузка текущего состояния игрового поля из списка ходов.
	 *
	 */
	public function movesLoad($movesList) {
		// Если список ходов пустой, возвращается false.
		if (!$movesList)
			return false;
		// Регистрация всех ходов на игровом поле.
		foreach ($movesList as $move) {
			// Регистрация хода на игровом поле.
			$this -> adjacentCellsAccession($move['PlayerID'], $move['ColorIndex']);
		}
		return true;
	}



	/**
	 *	Получение списка занятых цветов.
	 *
	 */
	public function disabledColorsGet() {
		$disabledColors = [];
		$startingPositionsList = $this -> lobby -> getPlayersStartingPosition();
		foreach ($startingPositionsList as $cellIndex) {
			$disabledColors[] = $this -> ColorMatrix[$cellIndex - 1];
		}
		return $disabledColors;
	}



	/**
	 *	Получение количества смежных ячеек для каждого цвета.
	 *
	 */
	public function progressByColorsListGet($playerID) {
		// Список количества смежных ячеек.
		$progressByColorsList = [];
		// Добавление количества смежных ячеек для каждого цвета в список.
		for ($cellIndex = 1; $cellIndex <= $this -> lobby -> getColorsNumber(); $cellIndex++) {
			$progressByColorsList[$cellIndex] = $this -> adjacentCellsNumberGet($playerID, $cellIndex);
		}
		return $progressByColorsList;
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
		return [
			'ID' => $this -> id,
			'ColorMatrix' => $this -> ColorMatrix,
			'StartDate' => $this -> StartDate,
			'FinishDate' => $this -> FinishDate,
			'Comment' => $this -> Comment,
			'LobbyID' => $this -> LobbyID,
			'WinnerID' => $this -> WinnerID,
		];
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
	 *	Возвращает количество сделанных в игре ходов.
	 *
	 */
	public function getMovesNumber() {
		//
		return tableGameDetail::find()
			-> where(['GameID' => $this -> id])
			-> count();
	}



	/**
	 *	Возвращает список всех сделанных в игре ходов.
	 *
	 */
	public function getMovesList($order = 'DESC', $limit = 1000) {
		// Список ходов.
		$movesList = [];
		// Загрузка из БД всех сделанных ходов для указанной игры.
		$dbModel = tableGameDetail::find()
			-> where(['GameID' => $this -> id])
			-> orderBy('ID ' . $order)
			-> limit($limit)
			-> all();
		// Формирование списка ходов.
		foreach ($dbModel as $move) {
			$movesList[] = [
				// Индекс цвета.
				'ColorIndex' => $move -> ColorIndex,
				// Количество захваченных ячеек.
				'CellNumber' => $move -> Points,
				// Время регистрации хода.
				'Date' => $move -> Date,
				// Идентификатор игрока.
				'PlayerID' => $move -> PlayerID,
			];
		}
		// Возвращается список ходов.
		return $movesList;
	}



	/**
	 *	Возвращается последний сделанный ход указанным соперником.
	 *	Если ход еще не сделан, возвращается false.
	 *
	 */
	public function getMove($playerID, $competitorID) {
		// Получение последних 4-х ходов игры.
		$movesList = $this -> getMovesList('DESC', 4);
		// Поиск хода указанного соперника.
		foreach ($movesList as $move) {
			// Если ход не найден:
			if ($move['PlayerID'] == $playerID)
				return false;
			// Если ход найден:
			else if ($move['PlayerID'] == $competitorID)
				// Возвращается найденный ход.
				return $move;
		}
		return false;
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
			$dbModel -> attributes = [
				'ColorIndex' => $ColorIndex,
				'Points' => $Points,
				'GameID' => $this -> id,
				'PlayerID' => $PlayerID
			];
			// Сохранение модели в БД.
			return $dbModel -> save();
		}
		return false;
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
	 *	Проверяет зарегистрирована ли в БД игра по указанному лобби.
	 *	Если игра зарегистрирована, возвращает true.
	 *	Если игра не зарегистрирована, возвращает false.
	 *
	 */
	public function isExist($LobbyID) {
		// Поиск игры в БД по указанному идентификатору лобби.
		if (tableGame::findOne([
			'LobbyID' => $LobbyID
		]))
			return true;
		return false;
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
			if ($this -> Load($dbModel -> id)) {
				// Инициализация идентификатора игры.
				$this -> id = $dbModel -> id;
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
		$Result = parent::loadModel($ID, '\app\models\Game', [
			'ColorMatrix',
			'StartDate',
			'FinishDate',
			'Comment',
			'LobbyID',
			'WinnerID'
		]);
		$this -> ColorMatrix = unserialize($this -> ColorMatrix);
		//
		$this -> init();
		//
		return $Result;
	}



	/**
	 *	Сохранение модели игры в базу данных.
	 *	Если модель успешно сохранилась, возвращает true.
	 *	Если модель не сохранилась, возвращает false.
	 *
	 */
	public function Save() {
		// Если игра уже зарегистрирована в БД:
		if (!$this -> getID() && $this -> isExist($this -> LobbyID))
			return false;
		$gameData = [
			'ColorMatrix' => serialize($this -> ColorMatrix),
			'FinishDate' => $this -> FinishDate,
			'Comment' => $this -> Comment,
			'LobbyID' => $this -> LobbyID,
			'WinnerID' => $this -> WinnerID,
		];
		// Если дата начала игры уже установлена:
		if ($this -> StartDate)
			$gameData['StartDate'] = $this -> StartDate;
		return parent::saveModel('\app\models\Game', $gameData);
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
