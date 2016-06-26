
// Типы ошибок сервера.
var SERVER_ERROR = {
	ExpireError: 'EXPIRE_ERROR',
	DataError: 'DATA_ERROR',
	UnknownError: '0'
}

// Настройки приложения.
var Application = {
	Default: {
		LobbyPlayers: 2,
		LobbyColorsNumber: 7
	},
	ColorsList: [
		'220000',
		'00FF00',
		'3355FF',
		'FFFF00',
		'FF00FF',
		'FFFFFF',
		'BB0000',
		'3E5900',
		'333399',
		'EE7700',
		'888890',
		'9900DD',
		'FFBB88',
		'00FFBB',
		'4A4A4F',
		'7C4600'
	],
	MoveTimer: 40,
	LobbyTimer: 120
}

/**
 *	Основной контроллер. Управляет всем приложением.
 *	Для начала работы контроллера необходимо вызвать действие Run.
 *
 */
GameController = function() {

}
/**
 *	Запуск приложения. Инициализация контроллера.
 *
 */
GameController.Run = function() {
	console.log('GameController.Run');
	// Игрок.
	window.PlayerModel = new modelPlayer({
		// Собственный игрок.
		Player: Player,
		// Адреса типа контроллер / экшен на сервере.
		URL: {
			Base: BASE_URL,
			PlayerStatistics: '/game/playerget',
			PlayerGameMarker: '/game/gamemarker'
		},
		// Типы ошибок сервера.
		ErrorTypes: SERVER_ERROR,
		// Период обновления маркера игрока (секунды).
		GameMarkerPeriod: 10
	});

	// Лобби.
	window.LobbyModel = new modelLobby({
		// Идентификатор собственного игрока.
		PlayerID: Player.ID,
		// Адреса типа контроллер / экшен на сервере.
		URL: {
			Base: BASE_URL,
			Create: '/game/lobbycreate',
			Load: '/game/lobbyload',
			Join: '/game/lobbyjoin',
			Result: '/game/lobbyresult'
		},
		// Типы ошибок сервера.
		ErrorTypes: SERVER_ERROR
	});

	// Список действующих лобби.
	window.LobbiesCollectionModel = new modelCollection({
		// Идентификатор собственного игрока.
		PlayerID: Player.ID,
		// Адреса типа контроллер / экшен на сервере.
		URL: {
			Base: BASE_URL,
			ListGet: '/game/lobbieslistget'
		},
		// Типы ошибок сервера.
		ErrorTypes: SERVER_ERROR,
		// Период обновления (секунды).
		TimerPeriod: 5,
		// Callback-функция.
		Callback: GameController.LobbiesListReady
	});

	// Список активных игроков.
	window.CompetitorsModelCollection = new modelCollection({
		// Идентификатор собственного игрока.
		PlayerID: Player.ID,
		// Адреса типа контроллер / экшен на сервере.
		URL: {
			Base: BASE_URL,
			ListGet: '/game/availableplayersget'
		},
		// Типы ошибок сервера.
		ErrorTypes: SERVER_ERROR,
		// Период обновления (секунды).
		TimerPeriod: 10,
		// Callback-функция.
		Callback: GameController.CompetitorsListReady
	});

	// Проигрыватель звука.
	window.Sound = new modelSound();

	// Таймер лобби (обратный отсчет).
	window.LobbyTimer = new modelTimer();
	// Таймер хода (обратный отсчет).
	window.MoveTimer = new modelTimer();
	// Таймер игры (прямой отсчет).
	window.GameTimer = new modelTimer();

	// Игровое поле с заданными параметрами.
	window.GameBoard = new modelGameBoard({
		// Идентификатор собственного игрока.
		PlayerID: Player.ID,
		// Адреса типа контроллер / экшен на сервере.
		URL: {
			Base: BASE_URL,
			GameStart: '/game/gamestart',
			GameFinish: '/game/gamefinish',
			GameGet: '/game/gameget',
			MoveSet: '/game/moveset',
			MoveGet: '/game/moveget',
			ColorMatrixGet: '/game/colormatrixget'
		},
		// Типы ошибок сервера.
		ErrorTypes: SERVER_ERROR,
		// Размер игрового поля.
		Size: {
			X: GameBoardSettings.SizeX,
			Y: GameBoardSettings.SizeY
		}
	});

	// Представление игрока.
	window.PlayerView = new viewPlayer();

	// Представление игрового поля.
	window.GameBoardView = new viewGameBoard({
		// 
		GameBoardID: 'GameBoardDiv'
	});

	// Представление табло.
	window.ScoreboardView = new viewScoreboard(DIALOG.LobbyView);

	// Установка размеров представления игрового поля по умолчанию.
	window.GameBoardView.SizeSet(window.GameBoard.Size.X, window.GameBoard.Size.Y);

	// Инициализация диалогового окна создания лобби.
	LobbyCreateDialog.ColorsNumberListSet(Application.Default.LobbyPlayers);

	// Обновление статистики игрока.
	GameController.StatisticsRefresh();

	// Проверка и загрузка незавершенной игры.
	GameController.Load();
}
/**
 *	По умолчанию включается режим просмотра / создания лобби.
 *	Если обнаружена незавершенная игра, происходит автоматическое 
 *	восстановлении незавершенной игры.
 *
 */
GameController.Load = function() {
	console.log('GameController.Load');
	// Если обнаружена незавершенная игра:
	if (LoadGame != false) {
		// Восстановление незавершенной игры.
		window.GameBoard.GameLoad(LoadGame);
		// Включение режима игры.
		GameController.GameStartReady(true);
	}
	// Если незавершенная игра отсутствует:
	else
		// Включение режима просмотра / создания лобби.
		GameController.LobbyModeSet();
}
/**
 *	Включение режима просмотра / создания лобби.
 *
 */
GameController.LobbyModeSet = function() {
	console.log('GameController.LobbyModeSet');
	// Выключение отображения игроков текущей игры.
	window.ScoreboardView.GamePlayersHide();
	// Включение отображения режима работы с лобби.
	window.ScoreboardView.LobbyMode();
	// Запуск таймера обновления списка действующих лобби.
	window.LobbiesCollectionModel.ListRefreshStart();
	// Запуск таймера обновления списка активных соперников.
	window.CompetitorsModelCollection.ListRefreshStart();
	// Выключение таймера маркера собственного игрока.
	window.PlayerModel.GameMarkerStop();
}
/**
 *	Включение режима игры.
 *
 */
GameController.GameModeSet = function() {
	console.log('GameController.GameModeSet');
	// Включение отображения игроков текущей игры.
	window.ScoreboardView.GamePlayersShow(window.GameBoard.PlayersList.Player);
	// Включение отображения режима игры.
	window.ScoreboardView.GameMode();
	// Выключение таймера обновления списка действующих лобби.
	window.LobbiesCollectionModel.ListRefreshStop();
	// Выключение таймера обновления списка активных соперников.
	window.CompetitorsModelCollection.ListRefreshStop();
	// Запуск таймера маркера собственного игрока.
	window.PlayerModel.GameMarkerStart(GameController.CompetitorEscape);
}
/**
 *	Создание нового лобби.
 *
 */
GameController.LobbyCreate = function() {
	console.log('GameController.LobbyCreate');
	// Если в диалоговом окне указано название лобби:
	if (LobbyCreateDialog.LobbyNameGet() != '') {
		// Получение параметров лобби из диалогового окна.
		var PlayersNumber = LobbyCreateDialog.PlayersNumberGet();
		var LobbyName = LobbyCreateDialog.LobbyNameGet();
		var ColorsNumber = LobbyCreateDialog.ColorsNumberGet();
		var Size = LobbyCreateDialog.SizeGet();
		// Установка параметров в модели лобби.
		window.LobbyModel.Set({
			Name: LobbyName,
			SizeX: Size.X,
			SizeY: Size.Y,
			ColorsNumber: ColorsNumber,
			PlayersNumber: PlayersNumber,
			CreatorID: Player.ID,
			Active: true
		});
		// Регистрация нового лобби на сервере.
		window.LobbyModel.Create(GameController.LobbyResultReady);
		// Закрытие диалогового окна создания лобби.
		LobbyCreateDialog.Hide();
		// Обновление данных диалогового окна просмотра лобби.
		LobbyDialog.Refresh(window.LobbyModel, Player.ID);
		// Открытие диалогового окна просмотра лобби.
		LobbyDialog.Show();
		// Включение воспроизведения звукового файла.
		// window.Sound.Play('Lobby');
		// Запуск таймера лобби.
		window.LobbyTimer.Start({Callback: GameController.LobbyTimer, StartValue: Application.LobbyTimer, Countdown: true});
	}
	else
		// Отображение ошибки.
		LobbyCreateDialog.ErrorShow();

}
/**
 *	Обновление представления значения таймера лобби 
 *	в окне просмотра лобби.
 *
 */
GameController.LobbyTimer = function() {
	// console.log('GameController.LobbyTimer');
	// Получение текущего значения таймера лобби.
	var TimerValue = window.LobbyTimer.Get();
	// Обновление значения таймера в диалоговом окне просмотра лобби.
	LobbyDialog.TimerRefresh(TimerValue);
}
/**
 *	Подключение к выбранному лобби.
 *
 */
GameController.LobbyJoin = function() {
	console.log('GameController.LobbyJoin');
	// Блокирование кнопки Подключения к лобби в окне просмотра лобби.
	LobbyDialog.ButtonDisabled('LobbyJoinButton');
	// Подключение к выбранному лобби на сервере.
	window.LobbyModel.Join(GameController.LobbyJoinReady);
}
/**
 *	Подтверждение подключения к выбранному лобби.
 *
 */
GameController.LobbyJoinReady = function() {
	console.log('GameController.LobbyJoinReady');
	// Вычисление текущего значения таймера лобби.
	var LobbyStartValue = Application.LobbyTimer - window.LobbyModel.Timer;
	if (LobbyStartValue < 0) LobbyStartValue = 0;
	// Запуск таймера лобби.
	window.LobbyTimer.Start({Callback: GameController.LobbyTimer, StartValue: LobbyStartValue, Countdown: true});
}
/**
 *	Запрос от сервера показателей собственного игрока.
 *
 */
GameController.StatisticsRefresh = function() {
	console.log('GameController.StatisticsRefresh');
	// 
	window.PlayerModel.StatisticsReload(GameController.StatisticsRefreshReady);
}
/**
 *	Подтверждение получения показателей собственного игрока от сервера.
 *
 */
GameController.StatisticsRefreshReady = function() {
	console.log('GameController.StatisticsRefreshReady');
	// Обновление представления показателей собственного игрока.
	window.PlayerView.StatisticsRefresh(window.PlayerModel.GetStatistics());
}
/**
 *	Подтверждение обновления списка действующих лобби.
 *
 */
GameController.LobbiesListReady = function() {
	console.log('GameController.LobbiesListReady');
	// Обновление представления списка действующих лобби.
	window.ScoreboardView.LobbiesListRefresh(window.LobbiesCollectionModel.ListGet(), 'GameController.LobbySelectHandler');
}
/**
 *	Подтверждение обновления списка активных игроков.
 *
 */
GameController.CompetitorsListReady = function() {
	console.log('GameController.CompetitorsListReady');
	// Обновление представления списка активных соперников.
	window.ScoreboardView.CompetitorsListRefresh(window.CompetitorsModelCollection.ListGet(), 'GameController.PlayerSelectHandler');
}
/**
 *	Выбор лобби в списке лобби.
 *
 */
GameController.LobbySelectHandler = function(LobbyID) {
	console.log('GameController.LobbySelectHandler = ' + LobbyID);
	// Получение лобби из списка лобби по указанному идентификатору.
	var Lobby = window.LobbiesCollectionModel.ItemGet(LobbyID);
	// Если указанное лобби не найдено, выход.
	if (!Lobby) return;
	// Установка параметров указанного лобби.
	window.LobbyModel.Set(Lobby);
	// Обновление списка игроков и активности лобби.
	window.LobbyModel.Result(GameController.LobbyResultReady);
	// Обновление данных диалогового окна просмотра лобби.
	LobbyDialog.Refresh(window.LobbyModel, Player.ID);
	// Открытие диалогового окна просмотра лобби.
	LobbyDialog.Show();
}
/**
 *	Подтверждение изменения списка подключенных к лобби игроков 
 *	или окончания срока действия лобби.
 *
 */
GameController.LobbyResultReady = function(NotRepeat) {
	console.log('GameController.LobbyResultReady');
	// Если лобби еще активно:
	if (window.LobbyModel.isActive())
		// Ожидание подключения следующего игрока.
		window.LobbyModel.Result(GameController.LobbyResultReady);
	else
		// Выключение таймера лобби.
		window.LobbyTimer.Stop();
	// Обновление данных диалогового окна просмотра лобби.
	LobbyDialog.Refresh(window.LobbyModel, Player.ID);
	// Если лобби еще активно, подключились все игроки, 
	// собственный игрок подключился к лобби и не является автором лобби,
	// и игра еще не началась:
	if (window.LobbyModel.isFullPlayersList() && 
		window.LobbyModel.isActive() && 
		window.LobbyModel.GetCreatorID() != Player.ID && 
		window.LobbyModel.isPlayerIncluded(Player.ID) && 
		window.LobbyModel.ID != window.GameBoard.LobbyID)
		// Запрос ожидания начала игры.
		window.GameBoard.GameGet(window.LobbyModel.ID, GameController.GameStartReady);
}
/**
 *	Выбор игрока в списке игрока.
 *
 */
GameController.PlayerSelectHandler = function(CompetitorID) {
	console.log('GameController.PlayerSelectHandler = ' + CompetitorID);
	// Поиск действующего лобби, созданного игроком.
	var LobbyID;
	var LobbiesList = window.LobbiesCollectionModel.ListGet();
	if (Array.isArray(LobbiesList)) {
		$.each(LobbiesList, function(Key, Lobby) {
			// Если игрок является автором действующего лобби:
			if (Lobby.CreatorID == CompetitorID)
				LobbyID = Lobby.ID;
		});
	}
	// Подготовка данных об игроке для вывода сообщения.
	DIALOG.Player.Message = MessageDialog.PlayerViewGet(
		'GameController.PlayerLobbyViewHandler', 
		window.CompetitorsModelCollection.ItemGet(CompetitorID),
		LobbyID
	);
	// Вывод сообщения с подробной информацией об игроке.
	MessageDialog.Show(DIALOG.Player);
}
/**
 *	Просмотр лобби через карточку соперника.
 *
 */
GameController.PlayerLobbyViewHandler = function(LobbyID) {
	// Закрытие окна сообщений.
	MessageDialog.Hide();
	// Выбор лобби в списке лобби.
	GameController.LobbySelectHandler(LobbyID);
}
/**
 *	Запуск игры.
 *
 */
GameController.GameStart = function() {
	console.log('GameController.GameStart');
	window.GameBoard.GameStart(window.LobbyModel.ID, window.LobbyModel.PlayersNumber, GameController.GameStartReady);
}
/**
 *	Подтверждение запуска игры.
 *
 */
GameController.GameStartReady = function(LoadFlag) {
	console.log('GameController.GameStartReady');
	// Выключение таймера лобби.
	window.LobbyTimer.Stop();
	// Запуск таймера игры.
	window.GameTimer.Start({StartValue: window.GameBoard.GameTimer});
	// Если текущая игра не является восстановленной:
	if (!LoadFlag)
		// Закрытие диалогового окна просмотра лобби.
		LobbyDialog.Hide();
	// Включение режима игры.
	GameController.GameModeSet();
	// Очистка списка лобби.
	window.ScoreboardView.LobbiesListRefresh();
	// 
	window.GameBoardView.FieldSizeSet(window.GameBoard.Size.X, window.GameBoard.Size.Y);
	// 
	window.GameBoardView.SizeSet(window.GameBoard.Size.X, window.GameBoard.Size.Y);
	// Инициализация панели образцов цветов.
	window.GameBoardView.GamePad(window.GameBoard.ColorsNumber, 'GameController.MoveSet');
	// Обновление игрового поля.
	window.GameBoardView.Repaint(
		window.GameBoard.ColorMatrix, 
		window.GameBoard.PlayingField, 
		window.GameBoard.DisabledColorsGet(),
		window.GameBoard.ProgressByColorsListGet(),
		window.GameBoard.HomeCellIndex
	);
	// Обновление счета игры на табло игроков.
	window.ScoreboardView.PlayersScoreRefresh(
		window.GameBoard.PlayerGameScoreGet(), 
		window.GameBoardView.ColorsListGet()
	);
	// Переключение индикатора следующего хода.
	window.ScoreboardView.GamePlayerHighlight(window.GameBoard.NextMovePlayerGet());
	// Если следующий ход соперника, включение режима ожидания хода.
	if (window.GameBoard.NextMovePlayerGet() != Player.ID)
		GameController.MoveGet();
	// Если текущая игра восстановлена:
	else if (LoadFlag) {
		// Восстановление значения таймера хода.
		var MoveTimerStartValue = Application.MoveTimer - window.GameBoard.MoveTimer;
		if (MoveTimerStartValue < 0) MoveTimerStartValue = 0;
		window.MoveTimer.Start({Callback: GameController.MoveTimer, StartValue: MoveTimerStartValue, Countdown: true});
	}
	else
		// Запуск таймера хода.
		window.MoveTimer.Start({Callback: GameController.MoveTimer, StartValue: Application.MoveTimer, Countdown: true});
	// Если текущая игра является восстановленной:
	if (LoadFlag)
		// Вывод сообщения о восстановлении незавершенной игры.
		MessageDialog.Show(DIALOG.GameRecovery);
	else
		// Вывод сообщения о начале новой игры.
		MessageDialog.Show(DIALOG.GameStart);
}
/**
 *	Завершение текущей игры.
 *
 */
GameController.GameFinish = function() {
	console.log('GameController.GameFinish');
	// Получение победителя игры.
	var Winner = window.GameBoard.WinnerGet();
	// Если победил собственный игрок:
	if (Winner.ID == Player.ID) {
		// Вывод сообщения о победе в игре.
		MessageDialog.Show(DIALOG.Victory, {YesButton: GameController.LobbyModeSet});
		// Включение воспроизведения звукового файла.
		window.Sound.Play('Victory');
	}
	// Если игрок проиграл:
	else {
		// Вставка в сообщение имени победителя.
		DIALOG.Defeat.Message = DIALOG.Defeat.Message
				.replace('{COMPETITOR_NAME}', '«' + Winner.Name + '»');
		// Вывод сообщения о поражении.
		MessageDialog.Show(DIALOG.Defeat, {YesButton: GameController.LobbyModeSet});
	}
	// Завершение игры.
	window.GameBoard.GameFinish(GameController.GameFinishReady);
	// Выключение таймера игры.
	window.GameTimer.Stop();
}
/**
 *	Подтверждение завершения текущей игры.
 *
 */
GameController.GameFinishReady = function() {
	console.log('GameController.GameFinishReady');
	// Обновление статистики игрока.
	GameController.StatisticsRefresh();
}
/**
 *	Отключение одного из соперников.
 *
 */
GameController.CompetitorEscape = function() {
	console.log('GameController.CompetitorEscape');
	// Если идет игра:
	if (window.GameBoard.GameID) {
		// Вывод сообщения о том, что один из соперников покинул игру.
		MessageDialog.Show(DIALOG.CompetitorEscape, {YesButton: GameController.LobbyModeSet});
		// Сброс параметров игры.
		window.GameBoard.GameReset();
	}
}
/**
 *	Регистрация хода собственного игрока.
 *
 */
GameController.MoveSet = function(ColorIndex) {
	console.log('GameController.MoveSet : ' + ColorIndex);
	// Если ход разрешен:
	if (window.GameBoard.MoveSet(ColorIndex, GameController.MoveSetReady)) {
		// Выключение таймера хода.
		window.MoveTimer.Stop();
		// Обновление состояния игрового поля.
		window.GameBoardView.Repaint(
			window.GameBoard.ColorMatrix, 
			window.GameBoard.PlayingField, 
			window.GameBoard.DisabledColorsGet(),
			window.GameBoard.ProgressByColorsListGet(),
			window.GameBoard.HomeCellIndex
		);
		// Обновление счета игры на табло игроков.
		window.ScoreboardView.PlayersScoreRefresh(
			window.GameBoard.PlayerGameScoreGet(), 
			window.GameBoardView.ColorsListGet()
		);
		// Переключение индикатора следующего хода.
		window.ScoreboardView.GamePlayerHighlight(window.GameBoard.NextMovePlayerGet());
		// Включение воспроизведения звукового файла.
		window.Sound.Play('Move');
	}
}
/**
 *	Подтверждение регистрации хода собственного игрока.
 *
 */
GameController.MoveSetReady = function() {
	console.log('GameController.MoveSetReady');
	// Если после последнего хода определен победитель:
	if (!window.GameBoard.GameStatusGet())
		// Завершение текущей игры.
		GameController.GameFinish();
	// Иначе, если следующий ход одного из соперников:
	else if (window.GameBoard.NextMovePlayerGet() != Player.ID)
		// Получение хода соперника.
		GameController.MoveGet();
}
/**
 *	Обработка срабатывания таймера хода собственного игрока.
 *
 */
GameController.MoveTimer = function() {
	console.log('GameController.MoveTimer');
	// Получение текущего значения таймера хода.
	var TimerValue = window.MoveTimer.Get();
	// Обновление представления таймера хода собственного игрока.
	window.ScoreboardView.PlayerTimerRefresh(window.GameBoard.NextMovePlayerGet(), TimerValue);
	// Если собственный игрок не успел сделать ход за отведенное время:
	if (!TimerValue)
		// Регистрируется случайный ход.
		GameController.MoveSet(window.GameBoard.CorrectMoveGet());
}
/**
 *	Получение хода соперника.
 *
 */
GameController.MoveGet = function() {
	console.log('GameController.MoveGet');
	// Запрос сделанного соперником хода на сервере.
	window.GameBoard.MoveGet(window.GameBoard.NextMovePlayerGet(), GameController.MoveGetReady);
}
/**
 *	Подтверждение получения хода соперника.
 *
 */
GameController.MoveGetReady = function() {
	console.log('GameController.MoveGetReady');
	// Обновление представления игрового поля.
	window.GameBoardView.Repaint(
		window.GameBoard.ColorMatrix, 
		window.GameBoard.PlayingField, 
		window.GameBoard.DisabledColorsGet(),
		window.GameBoard.ProgressByColorsListGet(),
		window.GameBoard.HomeCellIndex
	);
	// Обновление представления игровых показателей всех игроков для текущей игры.
	window.ScoreboardView.PlayersScoreRefresh(
		window.GameBoard.PlayerGameScoreGet(), 
		window.GameBoardView.ColorsListGet()
	);
	// Переключение индикатора следующего хода.
	window.ScoreboardView.GamePlayerHighlight(window.GameBoard.NextMovePlayerGet());
	// Включение воспроизведения звукового файла.
	window.Sound.Play('Move');
	// Если победитель определен:
	if (!window.GameBoard.GameStatusGet())
		// Завершение игры.
		GameController.GameFinish();
	// Eсли следующий ход одного из соперников:
	else if (window.GameBoard.NextMovePlayerGet() != Player.ID)
		// Получение хода соперника.
		GameController.MoveGet();
	// Если очередь хода собственного игрока:
	else
		// Запуск таймера хода.
		window.MoveTimer.Start({Callback: GameController.MoveTimer, StartValue: Application.MoveTimer, Countdown: true});
}
/**
 *	Включение режима подсветки смежных ячеек игрового поля.
 *
 */
GameController.AdjacentCellsHighlightOn = function(ColorIndex) {
	console.log('GameController.AdjacentCellsHighlightOn');
	// Получение списка занятых цветов.
	var DisabledColorsList = window.GameBoard.DisabledColorsGet();
	// Если указанный индекс цвета в списке занятых цветов:
	if (DisabledColorsList.indexOf(ColorIndex) != -1)
		return;
	// Получение списка смежных ячеек для указанного цвета.
	var AdjacentCellsList = window.GameBoard.AdjacentCellsListGet(Player.ID, ColorIndex);
	window.GameBoardView.CellHighlightShow(AdjacentCellsList);
}
/**
 *	Выключение режима подсветки смежных ячеек игрового поля.
 *
 */
GameController.AdjacentCellsHighlightOff = function() {
	console.log('GameController.AdjacentCellsHighlightOff');
	window.GameBoardView.CellHighlightHide();
}





/**
 *	Приложение начинает работать только после загрузки всех скриптов.
 *	Инициализируются все основные объекты.
 *
 */
$(window).load(function () {
	// Инициализация контроллера.
	GameController.Run();
});





/**
 *	Обработчики событий.
 *
 */
$(document).ready(function() {

	// Открытие диалогового окна для создания лобби.
	$('#LobbyCreate, #LobbyCreate-xs').click(function () {
		LobbyCreateDialog.Show();
	});

	// Публикация нового лобби.
	$('#LobbySaveButton').click(GameController.LobbyCreate);

	// Подключение к лобби.
	$('#LobbyJoinButton').click(GameController.LobbyJoin);

	// Начало игры.
	$('#GameStartButton').click(GameController.GameStart);

	// Переключение режима звука.
	$('#Button-Sound').click(function () {
		// Переключение режима звука (включение / выключение).
		window.Sound.Toggle();
		// Обновление иконки состояния переключателя звука.
		window.ScoreboardView.SoundIconRefresh(window.Sound.Mute);
	});

	// Открытие окна со справочной информацией.
	$('#Button-Help').click(function () {
		MessageDialog.Show(DIALOG.GameHelp);
	});

	// Наведение курсора на кнопку-образец цвета.
	$(document).on('mouseover', '.GameButton', function(event) {
		// Вычисление текущего индекса цвета.
		var ColorIndex = event.target.id;
		ColorIndex = ColorIndex.substring(5, ColorIndex.length);
		ColorIndex = parseInt(ColorIndex, 10);
		// Если индекс цвета корректный:
		if (typeof ColorIndex === 'number')
			// Включение режима подсветки смежных ячеек игрового поля.
			GameController.AdjacentCellsHighlightOn(ColorIndex);
	// Курсор покинул кнопку-образец цвета.
	}).on('mouseleave', '.GameButton', function() {
		// Выключение режима подсветки смежных ячеек игрового поля.
		GameController.AdjacentCellsHighlightOff();
	});

});





/**
 *  Изменение позиции диалогового окна и размеров защитного фона 
 *	при изменении размеров окна.
 *
 */
$(window).resize(function () {
	// Установка позиции диалогового окна и размеров защитного фона.
	window.GameBoardView.SizeSet(window.GameBoard.Size.X, window.GameBoard.Size.Y);
});
