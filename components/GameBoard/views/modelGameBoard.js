
$(document).ready(function () {

	/**
	 *	Модель игрового поля.
	 *	Игровое поле состоит из ячеек.
	 *	Каждая ячейка имеет уникальный порядковый индекс, 
	 *	индекс цвета и идентификатор владельца (игрока).
	 *	Нумерация ячеек игрового поля производится слева направо и сверху вниз:
	 *		1  2  3  4
	 *		5  6  7  8
	 *		9 10 11 12
	 *
	 */
	modelGameBoard = function (Settings) {
		// Инициализация игрового поля.
		this.Init(Settings);
	}
	/**
	 *	Запуск новой игры. Отправляется AJAX-запрос на сервер.
	 *
	 */
	modelGameBoard.prototype.GameStart = function (LobbyID, PlayersNumber, Callback) {
		console.log('modelGameBoard.prototype.GameStart');
		var self = this;
		// AJAX-запрос.
		$.post(
			this.URL.Base + this.URL.GameStart,
			{
				LobbyID: LobbyID
			},
			function(GameData) {
				// Если ошибок нет:
				if (!GameData.Error) {
					// Установка игровых параметров.
					self.GameSet(GameData);
					// Если задана callback-функция:
					if (typeof Callback === 'function')
						Callback();
				}
				// Если ошибка данных:
				else if (GameData.Error == self.ErrorTypes.DataError)
					// Повторный запуск новой игры.
					self.GameStart(LobbyID, PlayersNumber, Callback);
				// Если неизвестная ошибка:
				else
					console.log(self.URL.Base + self.URL.GameStart + ' : Error = ' + GameData.Error);
			},
			'json'
		)
	}
	/**
	 *	Запрос новой игры. Отправляется AJAX-запрос на сервер.
	 *
	 */
	modelGameBoard.prototype.GameGet = function (LobbyID, Callback) {
		console.log('modelGameBoard.prototype.GameGet');
		var self = this;
		// AJAX-запрос.
		$.post(
			this.URL.Base + this.URL.GameGet,
			{
				LobbyID: LobbyID
			},
			function(Result) {
				// Если ошибок нет:
				if (!Result.Error) {
					// Установка игровых параметров.
					self.GameSet(Result);
					// Если задана callback-функция:
					if (typeof Callback === 'function')
						Callback();
				}
				// Если ошибка данных:
				else if (Result.Error == self.ErrorTypes.ExpireError || Result.Error == self.ErrorTypes.DataError)
					// Повторный запрос начала игры.
					self.GameGet(LobbyID, Callback);
				// Если неизвестная ошибка:
				else
					console.log(self.URL.Base + self.URL.GameGet + ' : Error = ' + Result.Error);
			},
			'json'
		)
	}
	/**
	 *	Завершение текущей игры. Отправляется AJAX-запрос на сервер.
	 *
	 */
	modelGameBoard.prototype.GameFinish = function (Callback) {
		console.log('modelGameBoard.prototype.GameFinish');
		// Получение победителя текущей игры.
		var Winner = this.WinnerGet();
		// Если победитель не определен:
		if (!Winner)
			this.GameReset();
		var self = this;
		// AJAX-запрос.
		$.post(
			this.URL.Base + this.URL.GameFinish,
			{
				GameID: this.GameID,
				WinnerID: Winner.ID
			},
			function(Result) {
				// 
				self.GameReset();
				// Если задана callback-функция:
				if (typeof Callback === 'function')
					Callback();
			},
			'text'
		)
	}
	/**
	 *	Регистрация хода собственного игрока.
	 *
	 */
	modelGameBoard.prototype.MoveSet = function (ColorIndex, Callback) {
		console.log('modelGameBoard.prototype.MoveSet');
		// Ход регистрируется только, если идет игра, очередь хода не нарушена 
		// и ячейка игрового поля свободна:
		if (!this.GameID || this.NextMove != this.PlayerID || !this.isMoveCorrect(ColorIndex))
			return false;
		// Регистрация хода на игровом поле.
		var CellNumber = this.AdjacentCellsAccession(this.PlayerID, ColorIndex);
		// Добавление хода в протокол ходов.
		this.MoveAdd(this.PlayerID, ColorIndex, CellNumber);
		// Переключение очереди хода.
		this.MoveTurn();
		// Регистрация хода на сервере.
		this.serverMoveSet(ColorIndex, CellNumber, Callback);
		return true;
	}
	/**
	 *	Регистрация хода на сервере. Отправляется AJAX-запрос на сервер.
	 *
	 */
	modelGameBoard.prototype.serverMoveSet = function (ColorIndex, CellNumber, Callback) {
		console.log('modelGameBoard.prototype.serverMoveSet');
		var self = this;
		// AJAX-запрос.
		$.post(
			this.URL.Base + this.URL.MoveSet,
			{
				GameID: this.GameID,
				PlayerID: this.PlayerID,
				ColorIndex: ColorIndex,
				CellNumber: CellNumber
			},
			function(Result) {
				console.log('Result = ' + Result);
				// Если ошибок нет и ход зарегистрирован:
				if (!Result.Error) {
					// Если задана callback-функция:
					if (typeof Callback === 'function')
						Callback();
				}
				// Повторное отправление запроса на сервер.
				else
					self.serverMoveSet(ColorIndex, CellNumber, Callback);
			},
			'json'
		);
	}
	/**
	 *	Запрос сделанного соперником хода на сервере.
	 *
	 */
	modelGameBoard.prototype.MoveGet = function (CompetitorID, Callback) {
		console.log('modelGameBoard.prototype.MoveGet');
		// Если не идет игра или очередь хода собственного игрока:
		if (!this.GameID || this.NextMove == this.PlayerID)
			return false;
		// 
		var self = this;
		// AJAX-запрос.
		$.post(
			this.URL.Base + this.URL.MoveGet,
			{
				GameID: this.GameID,
				CompetitorID: CompetitorID
			},
			function(Result) {
				// Если ошибок нет:
				if (!Result.Error) {
					// Регистрация хода соперника на игровом поле.
					var CellNumber = self.AdjacentCellsAccession(CompetitorID, Result.ColorIndex);
					// Добавление хода в протокол ходов.
					self.MoveAdd(CompetitorID, Result.ColorIndex, CellNumber);
					// Переключение очереди хода.
					self.MoveTurn();
					// Если задана callback-функция:
					if (typeof Callback === 'function')
						Callback();
				}
				// Если ошибка данных:
				else if (Result.Error == self.ErrorTypes.ExpireError || Result.Error == self.ErrorTypes.DataError)
					self.MoveGet(CompetitorID, Callback);
				// Если неизвестная ошибка:
				else
					console.log(self.URL.Base + self.URL.MoveGet + ' : Error = ' + Result.Error);
			},
			'json'
		);
		return true;
	}
	/**
	 *	Переключение очереди хода.
	 *
	 */
	modelGameBoard.prototype.MoveTurn = function () {
		console.log('modelGameBoard.prototype.MoveTurn');
		// Если не идет игра:
		if (!this.GameID)
			return false;
		// Поиск в списке игроков игрока, очередь которого делать ход.
		var Index = 0;
		while (this.PlayersList.Player[Index].ID != this.NextMove) {
			Index++;
		}
		// Если игрок не последний в списке:
		if ((Index + 1) != this.PlayersList.Player.length)
			// Переход хода к следующему в списке игроку.
			this.NextMove = this.PlayersList.Player[Index + 1].ID;
		else
			// Переход хода к первому игроку в списке.
			this.NextMove = this.PlayersList.Player[0].ID;
		return true;
	}
	/**
	 *	Инициализация.
	 *
	 */
	modelGameBoard.prototype.Init = function (Settings) {
		// Идентификатор игрока.
		if (typeof Settings.PlayerID !== 'undefined' && Settings.PlayerID !== null)
			this.PlayerID = parseInt(Settings.PlayerID, 10);
		// Адреса типа контроллер / действие на сервере.
		if (typeof Settings.URL !== 'undefined' && Settings.URL !== null)
			this.URL = Settings.URL;
		// Размеры игрового поля.
		if (typeof Settings.Size !== 'undefined' && Settings.Size !== null)
			this.Size = Settings.Size;
		// Типы ошибок.
		if (typeof Settings.ErrorTypes !== 'undefined' && Settings.ErrorTypes !== null)
			this.ErrorTypes = Settings.ErrorTypes;
		// Сброс игровых параметров.
		this.GameReset();

		return true;
	}
	/**
	 *	Установка параметров игры.
	 *
	 */
	modelGameBoard.prototype.GameSet = function (GameData) {
		// Идентификатор текущего лобби.
		this.LobbyID = parseInt(GameData.LobbyID, 10);
		// Идентификатор текущей игры.
		this.GameID = parseInt(GameData.GameID, 10);
		// Размеры игрового поля.
		this.Size.X = parseInt(GameData.SizeX, 10);
		this.Size.Y = parseInt(GameData.SizeY, 10);
		// Количество игровых цветов.
		this.ColorsNumber = parseInt(GameData.ColorsNumber, 10);
		// Матрица цветов.
		this.ColorMatrix = GameData.ColorMatrix;
		// Список игроков.
		this.PlayersList.Player = GameData.PlayersList;
		// Список домашних ячеек игроков.
		this.PlayersList.Position = GameData.PlayersPosition;
		// Таймер игры.
		this.GameTimer = parseInt(GameData.GameTimer, 10);
		// Таймер хода.
		this.MoveTimer = parseInt(GameData.MoveTimer, 10);
		// Инициализация массива состояния игрового поля значением 0.
		this.PlayingField = Array();
		var Index = this.Size.X * this.Size.Y;
		while (Index--)
			this.PlayingField.push(0);
		// Очередь следующего хода.
		this.NextMove = GameData.FirstMove;
		// Расстановка игроков на поле.
		var self = this;
		$.each(this.PlayersList.Position, function(Index, Position) {
			self.PlayingField[Position - 1] = parseInt(self.PlayersList.Player[Index].ID);
			if (self.PlayingField[Position - 1] == self.PlayerID)
				self.HomeCellIndex = parseInt(Position, 10);
		});
	}
	/**
	 *	Сброс игровых параметров.
	 *
	 */
	modelGameBoard.prototype.GameReset = function () {
		// Массив состояния игрового поля.
		this.PlayingField = Array();
		// Индекс домашней ячейки собственного игрока.
		this.HomeCellIndex = null;
		// Матрица индексов цветов игрового поля.
		this.ColorMatrix = Array();
		// Идентификатор текущего лобби.
		this.LobbyID = null;
		// Идентификатор текущей игры.
		this.GameID = null;
		// Дата и время начала игры.
		this.StartDate = null;
		// Дата и время окончания игры.
		this.FinishDate = null;
		// Идентификатор игрока для следующего хода.
		this.NextMove = null;
		// Победитель игры.
		this.Winner = null;
		// Список игроков и их стартовых позиций.
		this.PlayersList = {
			Player: [],
			Position: []
		};
		// Список ходов.
		this.MoveList = Array();
		// Текущая ошибка.
		this.Error = {
			Code: null,
			Name: null
		};
	}
	/**
	 *	Добавление хода в список ходов.
	 *
	 */
	modelGameBoard.prototype.MoveAdd = function (PlayerID, ColorIndex, CellNumber) {
		this.MoveList.push({
			PlayerID: PlayerID,
			ColorIndex: ColorIndex,
			CellNumber: CellNumber
		});
	}
	/**
	 *	Получение последнего сделанного хода из списка ходов.
	 *	Если список ходов пустой, возвращается null.
	 *
	 */
	modelGameBoard.prototype.LastMoveGet = function (PlayerID) {
		console.log('modelGameBoard.prototype.LastMoveGet');
		var Index = this.MoveList.length;
		// Если указан игрок:
		if (typeof PlayerID !== 'undefined') {
			// Поиск последнего хода для указанного игрока.
			while (Index && this.MoveList[Index - 1].PlayerID != PlayerID)
				Index--;
		}
		// Если ход найден:
		if (Index)
			// Возвращается указанный ход.
			return this.MoveList[Index - 1];
		else
			return null;
	}
	/**
	 *	Получение текущего состояния игры.
	 *	Если игра еще идет, возвращается true, иначе false.
	 *
	 */
	modelGameBoard.prototype.GameStatusGet = function () {
		// Если определен досрочный победитель или победитель по очкам:
		if (this.GameEarlyResultGet() || this.GamePointsResultGet())
			// Игра закончена.
			return false;
		else
			// Игра не закончена.
			return true;
	}
	/**
	 *	Получение победителя текущей игры.
	 *	Если победитель еще не определен, возвращается null.
	 *
	 */
	modelGameBoard.prototype.WinnerGet = function () {
		return this.Winner;
	}
	/**
	 *	Загрузка параметров и состояния текущей игры.
	 *
	 */
	modelGameBoard.prototype.GameLoad = function (GameData) {
		// Установка параметров игры.
		this.GameSet(GameData);
		// Загрузка текущего состояния игрового поля из списка ходов.
		this.MovesLoad(GameData.MovesList);
	}
	/**
	 *	Загрузка текущего состояния игрового поля из списка ходов.
	 *
	 */
	modelGameBoard.prototype.MovesLoad = function (MovesList) {
		// Если список ходов пустой, возвращается false.
		if (MovesList == null)
			return false;
		var self = this;
		$.each(MovesList, function(Index, Move) {
			// Регистрация хода на игровом поле.
			self.AdjacentCellsAccession(Move.PlayerID, Move.ColorIndex);
			// Добавление хода в протокол ходов.
			self.MoveAdd(Move.PlayerID, Move.ColorIndex, Move.CellNumber)
			// Переключение очереди хода.
			self.MoveTurn();
		});
		return true;
	}
	/**
	 *	Установка стартовых позиций (домашних ячеек) всех игроков.
	 *
	 */
	modelGameBoard.prototype.PlayersStartingPositionSet = function (PlayersNumber) {
		console.log('modelGameBoard.prototype.PlayersStartingPositionGet(' + PlayersNumber + ')');
		this.PlayersList.Position = [];
		// Позиция игрока #1.
		this.PlayersList.Position.push(1);
		// Если участвует 2 игрока.
		if (PlayersNumber == 2)
			// Позиция игрока #2.
			this.PlayersList.Position.push(this.Size.X * this.Size.Y);
		// Если участвует 4 игрока.
		else if (PlayersNumber == 4) {
			// Позиция игрока #2.
			this.PlayersList.Position.push(this.Size.X);
			// Позиция игрока #3.
			this.PlayersList.Position.push((this.Size.X * this.Size.Y) - this.Size.X + 1);
			// Позиция игрока #4.
			this.PlayersList.Position.push(this.Size.X * this.Size.Y);
		}
		// Если указано непредусмотренное количество игроков.
		else
			return false;
		// Возвращается список стартовых позиций всех игроков.
		return true;
	}
	/**
	 *	Получение текущего количества очков указанного игрока.
	 *	Количество очков равно количеству ячеек игрока.
	 *
	 */
	modelGameBoard.prototype.PointsGet = function (PlayerID, PlayingField) {
		// Если не задана копия игрового поля:
		if (typeof PlayingField === 'undefined' || !Array.isArray(PlayingField))
			// Используется основное игровое поле.
			PlayingField = this.PlayingField;
		// Количество очков игрока.
		var Points = 0;
		// Сканирование всего игрового поля.
		PlayingField.forEach(function(Item, Index) {
			// Если ячейка принадлежит игроку:
			if (Item == PlayerID)
				Points++;
		});
		return Points;
	}
	/**
	 *	Получение текущей доли игрока от всего игрового поля.
	 *
	 */
	modelGameBoard.prototype.PortionGet = function (PlayerID) {
		return Math.round((this.PointsGet(PlayerID) / (this.Size.X * this.Size.Y)) * 100);
	}
	/**
	 *	Получение текущего счета игры для всех игроков.
	 *
	 */
	modelGameBoard.prototype.PlayerGameScoreGet = function () {
		var PlayersList = this.PlayersList.Player;
		var self = this;
		PlayersList.forEach(function(Player, Index) {
			Player.Points = self.PointsGet(Player.ID);
			Player.Portion = self.PortionGet(Player.ID);
			Player.ColorIndex = self.PlayerColorIndexGet(Player.ID);
			Player.LastMove = self.LastMoveGet(Player.ID);
		});
		return PlayersList;
	}
	/**
	 *	Получение текущего индекса цвета указанного игрока.
	 *	Если указанный игрок не найден, возвращается null.
	 *
	 */
	modelGameBoard.prototype.PlayerColorIndexGet = function (PlayerID) {
		var PlayerColorIndex = null;
		var self = this;
		// Поиск указанного игрока.
		$.each(this.PlayersList.Position, function(Index, HomeCellIndex) {
			if (self.PlayingField[HomeCellIndex - 1] == PlayerID)
				PlayerColorIndex = parseInt(self.ColorMatrix[HomeCellIndex - 1], 10);
		});
		return PlayerColorIndex;
	}
	/**
	 *	Получение идентификатора игрока, который делает следующий ход.
	 *
	 */
	modelGameBoard.prototype.NextMovePlayerGet = function () {
		return this.NextMove;
	}
	/**
	 *	Проверка корректности хода.
	 *
	 */
	modelGameBoard.prototype.isMoveCorrect = function (ColorIndex) {
		// Проверка всех ячеек игрового поля.
		for (var Index = 0; Index < this.PlayingField.length; Index++) {
			// Если ячейка занята любым игроком и ее цвет совпадает с выбранным цветом:
			if (this.PlayingField[Index] && this.ColorMatrix[Index] == ColorIndex)
				// Ход некорректный.
				return false;
		}
		// Ход корректный.
		return true;
	}
	/**
	 *	Получение из списка цветов индекса случайного цвета, 
	 *	доступного для следующего хода.
	 *
	 */
	modelGameBoard.prototype.CorrectMoveGet = function () {
		var ColorIndex;
		var DisabledColors = this.DisabledColorsGet();
		// Выбор случайного цвета из списка цветов.
		// Если выбранный цвет присутствует в списке занятых цветов,
		// выбирается другой случайный цвет.
		do {
			ColorIndex = this.RandomInteger(1, this.ColorsNumber);
		} while (DisabledColors.indexOf(ColorIndex) != -1)
		return ColorIndex;
	}
	/**
	 *	Получение списка занятых цветов.
	 *
	 */
	modelGameBoard.prototype.DisabledColorsGet = function () {
		var DisabledColors = Array();
		var self = this;
		$.each(this.PlayersList.Position, function(Key, Value) {
			DisabledColors.push(parseInt(self.ColorMatrix[Value - 1], 10));
		});
		return DisabledColors;
	}
	/**
	 *	Получение количества смежных ячеек для каждого цвета.
	 *
	 */
	modelGameBoard.prototype.ProgressByColorsListGet = function () {
		// console.time('ProgressByColorsListGet');
		// Список количества смежных ячеек.
		var ProgressByColorsList = Array();
		// Добавление количества смежных ячеек для каждого цвета в список.
		for (var ColorIndex = 1; ColorIndex <= this.ColorsNumber; ColorIndex++) {
			ProgressByColorsList.push(this.AdjacentCellsNumberGet(this.PlayerID, ColorIndex));
		}
		// console.timeEnd('ProgressByColorsListGet');
		return ProgressByColorsList;
	}
	/**
	 *	Получение списка индексов смежных ячеек для указанного цвета и игрока.
	 *	
	 */
	modelGameBoard.prototype.AdjacentCellsListGet = function (PlayerID, ColorIndex) {
		// Копия состояния игрового поля.
		var PlayingField = this.PlayingField.slice();
		// Флаг наличия новых присоединенных ячеек.
		var Flag = true;
		// Количество присоединенных ячеек указанным игроком.
		var AdjacentCellsList = Array();
		// Сканирование продолжается пока в каждом цикле указанному игроку 
		// добавляется хотя бы одна новая ячейка.
		while (Flag) {
			Flag = false;
			// Проверка всех ячеек игрового поля.
			for (var Index = 0; Index < PlayingField.length; Index++) {
				// Если указанная ячейка имеет указанный цвет и граничит с полем указанного игрока:
				if (this.FreeCellCheck(Index + 1, ColorIndex, PlayerID, PlayingField)) {
					// Добавление индекса указанной ячейки в список.
					AdjacentCellsList.push(Index + 1);
					// Присоединение указанной ячейки к территории указанного игрока.
					PlayingField[Index] = parseInt(PlayerID, 10);
					// Установка флага наличия новых присоединенных ячеек.
					Flag = true;
				}
			}
		}
		return AdjacentCellsList;
	}
	/**
	 *	Получение количества смежных ячеек для указанного цвета и игрока.
	 *
	 */
	modelGameBoard.prototype.AdjacentCellsNumberGet = function (PlayerID, ColorIndex) {
		var AdjacentCellsList = this.AdjacentCellsListGet(PlayerID, ColorIndex);
		return AdjacentCellsList.length;
	}
	/**
	 *	Присоединение смежных ячеек указанного цвета к территории указанного игрока.
	 *
	 */
	modelGameBoard.prototype.AdjacentCellsAccession = function (PlayerID, ColorIndex) {
		// Список индексов смежных ячеек.
		var AdjacentCellsList = this.AdjacentCellsListGet(PlayerID, ColorIndex);
		// 
		var self = this;
		// Присоединение всех смежных ячеек к территории указанного игрока.
		$.each(AdjacentCellsList, function(Key, CellIndex) {
			// Присоединение указанной ячейки к территории указанного игрока.
			self.PlayingField[CellIndex - 1] = parseInt(PlayerID, 10);
		});
		// Если есть присоединенные ячейки:
		if (AdjacentCellsList.length)
			// Изменение цвета всех ячеек игрока.
			this.PlayerFieldReindex(PlayerID, ColorIndex);
		// Возвращается количество присоединенных ячеек.
		return AdjacentCellsList.length;
	}
	/**
	 *	Переиндексация (перекрашивание) всей территории указанного игрока в указанный цвет.
	 *
	 */
	modelGameBoard.prototype.PlayerFieldReindex = function (PlayerID, ColorIndex) {
		// Проверка всех ячеек игрового поля.
		for (var Index = 0; Index < this.PlayingField.length; Index++) {
			// Если ячейка принадлежит указанному игроку:
			if (this.PlayingField[Index] == PlayerID)
				// Установка указанной ячейке указанного индекса цвета.
				this.ColorMatrix[Index] = ColorIndex;
		}
	}
	/**
	 *	Проверка ячейки на возможность захвата.
	 *	Если указанная ячейка свободна, является смежной для указанного игрока 
	 *	и ее цвет совпадает с указанным цветом, возвращается true, иначе false.
	 *
	 */
	modelGameBoard.prototype.FreeCellCheck = function (CellIndex, ColorIndex, PlayerID, PlayingField) {
		// Результат проверки ячейки.
		var CheckResult = false;
		// Если указанная ячейка свободна и ее цвет совпадает с указанным цветом:
		if (!PlayingField[CellIndex - 1] && this.ColorMatrix[CellIndex - 1] == ColorIndex) {
			// Получение списка смежных ячеек для указанной ячейки.
			var AdjacentCellIndexList = this.AdjacentCellIndexGet(CellIndex);
			// Проверка всех смежных ячеек.
			$.each(AdjacentCellIndexList, function(Key, Value) {
				// Если текущая смежная ячейка принадлежит указанному игроку:
				if (PlayingField[Value - 1] == PlayerID)
					CheckResult = true;
			});
		}
		return CheckResult;
	}
	/**
	 *	Получение списка смежных ячеек для указанной ячейки.
	 *	Возвращается массив индексов ячеек.
	 *
	 */
	modelGameBoard.prototype.AdjacentCellIndexGet = function (CellIndex, Direction) {
		// Список индексов смежных ячеек для указанной ячейки.
		var AdjacentCellIndexList = Array();
		// Если для указанной ячейки требуются все смежные ячейки или только смежная ячейка слева
		// и данная ячейка существует (не левый край игрового поля):
		if ((typeof Direction === 'undefined' || Direction == 'left') && ((CellIndex - 1) / this.Size.X) != Math.floor((CellIndex - 1) / this.Size.X))
			// Добавление индекса смежной ячейки в список.
			AdjacentCellIndexList.push(CellIndex - 1);
		// Если для указанной ячейки требуются все смежные ячейки или только смежная ячейка справа
		// и данная ячейка существует (не правый край игрового поля):
		if ((typeof Direction === 'undefined' || Direction == 'right') && (CellIndex / this.Size.X) != Math.floor(CellIndex / this.Size.X))
			AdjacentCellIndexList.push(CellIndex + 1);
		// Если для указанной ячейки требуются все смежные ячейки или только смежная ячейка сверху
		// и данная ячейка существует (не верхний край игрового поля):
		if ((typeof Direction === 'undefined' || Direction == 'top') && (CellIndex - this.Size.X) > 0)
			AdjacentCellIndexList.push(CellIndex - this.Size.X);
		// Если для указанной ячейки требуются все смежные ячейки или только смежная ячейка снизу
		// и данная ячейка существует (не нижний край игрового поля):
		if ((typeof Direction === 'undefined' || Direction == 'bottom') && (CellIndex + this.Size.X) <= (this.Size.X * this.Size.Y))
			AdjacentCellIndexList.push(CellIndex + this.Size.X);
		// Возвращается список индексов смежных ячеек для указанной ячейки.
		return AdjacentCellIndexList;
	}
	/**
	 *	Получение списка идентификаторов игроков, владеющих смежными ячейками.
	 *	Возвращается массив идентификаторов игроков.
	 *
	 */
	modelGameBoard.prototype.AdjacentCellOwnerGet = function (CellIndex, PlayingField) {
		// Список идентификаторов игроков, владеющих смежными ячейками для указанной ячейки.
		var AdjacentCellOwnerList = Array();
		// Получение списка смежных ячеек для указанной ячейки.
		var AdjacentCellIndexList = this.AdjacentCellIndexGet(CellIndex);
		// 
		$.each(AdjacentCellIndexList, function(Key, Value) {
			// Если указанная ячейка принадлежит игроку и данный игрок еще отсутствует в списке:
			if (PlayingField[Value - 1] && AdjacentCellOwnerList.indexOf(parseInt(PlayingField[Value - 1], 10)) == -1)
				// Добавление идентификатора игрока в список.
				AdjacentCellOwnerList.push(parseInt(PlayingField[Value - 1], 10));
		});
		return AdjacentCellOwnerList;
	}
	/**
	 *	Получение количества игроков граничащих с указанной ячейкой.
	 *
	 */
	modelGameBoard.prototype.AdjacentCellsOwnersNumberGet = function (CellIndex, PlayingField) {
		var AdjacentCellOwner = this.AdjacentCellOwnerGet(CellIndex, PlayingField);
		return AdjacentCellOwner.length;
	}
	/**
	 *	Получение идентификатора досрочного победителя текущей игры.
	 *	Если победитель определен, возвращается идентификатор победителя.
	 *	Если победитель не определен, возращается null.
	 *
	 */
	modelGameBoard.prototype.GameEarlyResultGet = function () {
		// Копия состояния игрового поля.
		var PlayingField = this.PlayingField.slice();
		// Список идентификаторов игроков, владеющих смежными ячейками.
		var AdjacentCellOwnerList;
		// Показатели для подсчета результата игры.
		var CurrentCellsNumber = 0;
		var MaximumCellsNumber = 0;
		// 
		var self = this;
		// Распределение свободных ячеек по игрокам.
		$.each(PlayingField, function(CellIndex, PlayerID) {
			// Если текущая ячейка свободна:
			if (!PlayingField[CellIndex]) {
				// Если с текущей ячейкой граничит только один игрок:
				if (self.AdjacentCellsOwnersNumberGet(CellIndex + 1, PlayingField) == 1) {
					// Получение идентификатора игрока, владеющего смежными ячейками.
					AdjacentCellOwnerList = self.AdjacentCellOwnerGet(CellIndex + 1, PlayingField);
					// Установка текущей ячейке полученного идентификатора игрока.
					PlayingField[CellIndex] = AdjacentCellOwnerList[0];
				}
			}
		});
		// Если свободных ячеек не осталось:
		if (PlayingField.indexOf(0) == -1) {
			// Подсчет результата игры для каждого игрока и определение победителя.
			this.PlayersList.Player.forEach(function(Player, Index) {
				// Подсчет количества ячеек текущего игрока.
				CurrentCellsNumber = self.PointsGet(Player.ID, PlayingField);
				// Если текущий игрок является лидером:
				if (CurrentCellsNumber > MaximumCellsNumber) {
					// Обновление текущего результата лидера.
					MaximumCellsNumber = CurrentCellsNumber;
					self.Winner = Player;
				}
			});
			return self.Winner.ID;
		}
		else
			// Победитель не определен.
			return null;
	}
	/**
	 *	Контроль результата игры.
	 *	Если победитель определен, возвращается идентификатор победителя.
	 *	Если победитель не определен, возращается 0.
	 *
	 */
	modelGameBoard.prototype.GamePointsResultGet = function () {
		// 
		var PlayersPoints = Array();
		var MaximumPoints = 0;
		var MaximumIndex = 0;
		var LeaderID = null;
		// Количество свободных ячеек игрового поля.
		var EmptyCellsNumber = this.PointsGet(0);
		// 
		var self = this;
		// Поиск лидера среди всех игроков.
		this.PlayersList.Player.forEach(function(Player, Index) {
		// this.PlayersList.forEach(function(PlayerID, Index) {
			// Подсчет количества очков для указанного игрока.
			PlayersPoints.push(self.PointsGet(Player.ID));
			// Если указанный игрок является лидером:
			if (PlayersPoints[PlayersPoints.length - 1] > MaximumPoints) {
				// Обновление текущего результата лидера.
				MaximumPoints = PlayersPoints[PlayersPoints.length - 1];
				LeaderID = Player.ID;
				MaximumIndex = Index;
				self.Winner = Player;
			}
		});
		// Удаление лидера из списка результатов всех игроков.
		PlayersPoints.splice(MaximumIndex, 1);
		// Сравнение результатов всех игроков, учитывая свободные ячейки, с результатами лидера.
		PlayersPoints.forEach(function(Points, Index) {
			// Если победитель не выявлен:
			if (MaximumPoints < (Points + EmptyCellsNumber)) {
				// Сброс указателя лидера.
				LeaderID = null;
				self.Winner = null;
			}
		});
		// 
		return LeaderID;
	}
	/**
	 *	Генерирование случайного целого числа в заданном диапазоне.
	 *
	 */
	modelGameBoard.prototype.RandomInteger = function (Min, Max) {
		return Math.floor(Math.random() * (Max - Min + 1)) + Min;
	}
});
