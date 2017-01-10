//
// /**
//  *	Модель оповещений.
//  *
//  */
// modelNotification = function (Settings) {
// 	// Если задан объект Player:
// 	if (typeof Settings.PlayerID === 'number') {
// 		// Уникальный идентификатор игрока.
// 		this.PlayerID = Settings.PlayerID;
// 	}
// 	// Адреса типа контроллер / действие на сервере.
// 	if (typeof Settings.URL !== 'undefined' && Settings.URL !== null)
// 		this.URL = Settings.URL;
// 	// Типы ошибок.
// 	if (typeof Settings.ErrorTypes !== 'undefined' && Settings.ErrorTypes !== null)
// 		this.ErrorTypes = Settings.ErrorTypes;
// 	// Игровые показатели игрока.
// 	this.Notification = [];
// }
// /**
//  *
//  *
//  */
// modelNotification.prototype.Load = function (Callback) {
// 	console.log('modelNotification.prototype.Load');
// 	var self = this;
// 	// AJAX-запрос.
// 	$.post(
// 		this.URL.Base + this.URL.Notification,
// 		{
// 			PlayerID: this.PlayerID
// 		},
// 		function(Result) {
// 			// Если нет ошибок:
// 			if (!Result.Error) {
// 				// Текущие игровые показатели.
// 				self.Notification = Result;
// 			}
// 			// Если ошибка данных:
// 			else if (Result.Error == self.ErrorTypes.DataError) {
//
// 			}
// 			// Если неизвестная ошибка:
// 			else
// 				console.log(self.URL.Base + self.URL.PlayerStatistics + ' : Error = ' + Result.Error);
// 			// Если задана callback-функция:
// 			if (typeof Callback === 'function')
// 				Callback();
// 		},
// 		'json'
// 	)
// }
// /**
//  *
//  *
//  */
// modelNotification.prototype.GetID = function () {
// 	return this.ID;
// }





/**
 *  Модель лобби.
 *
 */
modelLobby = function (Settings) {
	// Идентификатор игрока.
	if (typeof Settings.PlayerID !== 'undefined' && Settings.PlayerID !== null)
		this.PlayerID = parseInt(Settings.PlayerID, 10);
	// Адреса типа контроллер / действие на сервере.
	if (typeof Settings.URL !== 'undefined' && Settings.URL !== null)
		this.URL = Settings.URL;
	// Типы ошибок.
	if (typeof Settings.ErrorTypes !== 'undefined' && Settings.ErrorTypes !== null)
		this.ErrorTypes = Settings.ErrorTypes;
	// Начальная инициализация параметров лобби.
	this.Reset();
}
/**
 *  Установка параметров лобби.
 *
 */
modelLobby.prototype.Set = function (Lobby) {
	// console.log('modelLobby.prototype.Set');
	// Если не передан объект лобби:
	if (typeof Lobby === 'undefined' || typeof Lobby !== 'object')
		return;
	// Идентификатор лобби.
	this.ID = Lobby.ID;
	// Название лобби.
	this.Name = Lobby.Name;
	// Дата и время регистрации лобби.
	this.Date = Lobby.Date;
	// Текущее значение таймера лобби.
	this.Timer = Lobby.Timer;
	// Размеры игрового поля.
	this.SizeX = Lobby.SizeX;
	this.SizeY = Lobby.SizeY;
	// Количество цветов лобби.
	this.ColorsNumber = Lobby.ColorsNumber;
	// Количество игроков лобби.
	this.PlayersNumber = Lobby.PlayersNumber;
	// Количество ботов.
	this.botsNumber = Lobby.botsNumber;
	// Уровень мастерства ботов.
	this.botsLevel = Lobby.botsLevel;
	// Автор лобби.
	this.CreatorID = Lobby.CreatorID;
	// Активность лобби.
	this.Active = Lobby.Active;
	// Если указан список подключившихся к лобби игроков:
	if (typeof Lobby.PlayersList !== 'undefined' && Lobby.PlayersList !== null)
		this.PlayersList = Lobby.PlayersList;
	else
		this.PlayersList = [];
}
/**
 *	Начальная инициализация параметров лобби.
 *
 */
modelLobby.prototype.Reset = function () {
	// console.log('modelLobby.prototype.Reset');
	// Идентификатор лобби.
	this.ID = 0;
	// Название лобби.
	this.Name = '';
	// Дата и время регистрации лобби.
	this.Date = null;
	// Размеры игрового поля.
	this.SizeX = 0;
	this.SizeY = 0;
	// Количество цветов лобби.
	this.ColorsNumber = 0;
	// Количество игроков лобби.
	this.PlayersNumber = 0;
	// Автор лобби.
	this.CreatorID = 0;
	// Активность лобби.
	this.Active = true;
	// Список игроков лобби.
	this.PlayersList = [];
}
/**
 *	Создание нового лобби. Отправляется AJAX-запрос на сервер.
 *
 */
modelLobby.prototype.Create = function (Callback) {
	console.log('modelLobby.prototype.Create');
	// 
	var self = this;
	// AJAX-запрос.
	$.post(
		this.URL.Base + this.URL.Create,
		{
			PlayerID: this.PlayerID,
			Name: this.Name,
			SizeX: this.SizeX,
			SizeY: this.SizeY,
			ColorsNumber: this.ColorsNumber,
			PlayersNumber: this.PlayersNumber,
			botsNumber: this.botsNumber,
			botsLevel: this.botsLevel
		},
		function(Lobby) {
			// Установка параметров лобби.
			self.ID = Lobby.ID;
			self.Date = Lobby.Date;
			self.Timer = Lobby.Timer;
			self.PlayersList = Lobby.PlayersList;
			// Если задана callback-функция:
			if (typeof Callback === 'function')
				Callback();
		},
		'json'
	)
}
/**
 *	Загрузка указанного существующего лобби. Отправляется AJAX-запрос на сервер.
 *
 */
modelLobby.prototype.Load = function (LobbyID, Callback) {
	console.log('modelLobby.prototype.Load');
	// 
	var self = this;
	// AJAX-запрос.
	$.post(
		this.URL.Base + this.URL.Load,
		{
			LobbyID: LobbyID
		},
		function(Lobby) {
			// Установка параметров лобби.
			self.Set(Lobby);
			// Если задана callback-функция:
			if (typeof Callback === 'function')
				Callback();
		},
		'json'
	)
}
/**
 *	Подключение к лобби. Отправляется AJAX-запрос на сервер.
 *
 */
modelLobby.prototype.Join = function (Callback) {
	console.log('modelLobby.prototype.Join');
	// 
	var self = this;
	// AJAX-запрос.
	$.post(
		this.URL.Base + this.URL.Join,
		{
			PlayerID: this.PlayerID,
			LobbyID: this.ID,
		},
		function(Result) {
			// Если нет ошибок:
			if (!Result.Error) {
				// Установка параметров лобби.
				self.Set(Result);
				// Если указана Callback-функция:
				if (typeof Callback === 'function')
					Callback();
			}
			// Если срок действия лобби истек или ошибка данных:
			else if (Result.Error == self.ErrorTypes.ExpireError || Result.Error == self.ErrorTypes.DataError) {

			}
			// Если неизвестная ошибка:
			else
				console.log(self.URL.Base + self.URL.Join + ' : Error = ' + Result.Error);
		},
		'json'
	)
}
/**
 *	Обновление списка подключенных к лобби игроков 
 *	и срока действия лобби. Отправляется AJAX-запрос на сервер.
 *	Сервер дает ответ, если изменилось количество подключенных к лобби игроков 
 *	или истек срок действия лобби.
 *
 */
modelLobby.prototype.Result = function (Callback) {
	console.log('modelLobby.prototype.Result');
	// 
	var self = this;
	// AJAX-запрос.
	$.post(
		this.URL.Base + this.URL.Result,
		{
			LobbyID: this.ID,
			PlayersNumber: this.PlayersList.length
		},
		function(Result) {
			// Если нет ошибок:
			if (!Result.Error) {
				if (self.ID == Result.ID) {
					// Текущее значение таймера лобби.
					self.Timer = Result.Timer;
					// Статус активности лобби.
					self.Active = Result.Active;
					// Список подключенных к лобби игроков.
					self.PlayersList = Result.PlayersList;
					// Если задана callback-функция:
					if (typeof Callback === 'function')
						Callback();
				}
			}
			// Если срок действия лобби истек или ошибка данных:
			else if (Result.Error == self.ErrorTypes.ExpireError || Result.Error == self.ErrorTypes.DataError) {
				
			}
			// Если неизвестная ошибка:
			else
				console.log(self.URL.Base + self.URL.Result + ' : Error = ' + Result.Error);
		},
		'json'
	)
}
/**
 *	Возвращает список игроков, подключившихся к лобби.
 *
 */
modelLobby.prototype.GetPlayersList = function () {
	// console.log('modelLobby.prototype.GetPlayersList');
	return this.PlayersList;
}
/**
 *	Возвращает идентификатор автора лобби.
 *
 */
modelLobby.prototype.GetCreatorID = function () {
	// console.log('modelLobby.prototype.GetCreatorID');
	return this.CreatorID;
}
/**
 *	Проверка текущего состояния лобби.
 *	Если лобби активное (срок действия не истек), возвращает true, иначе false.
 *
 */
modelLobby.prototype.isActive = function () {
	// console.log('modelLobby.prototype.isActive');
	return this.Active;
}
/**
 *	Проверка подключились ли все игроки к лобби.
 *	Если все игроки подключились к лобби, возвращает true, иначе false.
 *
 */
modelLobby.prototype.isFullPlayersList = function () {
	// console.log('modelLobby.prototype.isFullPlayersList');
	if (Array.isArray(this.PlayersList) && this.PlayersList.length == this.PlayersNumber)
		return true;
	else
		return false;
}
/**
 *	Проверка подключен ли указанный игрок к лобби.
 *	Если игрок подключен к лобби, возвращает true, иначе false.
 *
 */
modelLobby.prototype.isPlayerIncluded = function (PlayerID) {
	// console.log('modelLobby.prototype.isPlayerIncluded');
	var Player = false;
	// Поиск указанного игрока в списке подключившихся к лобби игроков.
	if (Array.isArray(this.PlayersList)) {
		$.each(this.PlayersList, function(Key, Value) {
			if (Value.ID == PlayerID)
				Player = true;
		});
	}
	return Player;
}





/**
 *	Модель игрока.
 *
 */
modelPlayer = function (Settings) {
	// Если задан объект Player:
	if (typeof Settings.Player === 'object') {
		// Уникальный идентификатор игрока.
		this.ID = Settings.Player.ID;
		// Имя игрока.
		this.Name = Settings.Player.Name;
	}
	// Адреса типа контроллер / действие на сервере.
	if (typeof Settings.URL !== 'undefined' && Settings.URL !== null)
		this.URL = Settings.URL;
	// Типы ошибок.
	if (typeof Settings.ErrorTypes !== 'undefined' && Settings.ErrorTypes !== null)
		this.ErrorTypes = Settings.ErrorTypes;
	// Период таймера обновления маркера игры (секунды).
	if (typeof Settings.GameMarkerPeriod === 'number')
		this.GAME_MARKER_PERIOD = parseInt(Settings.GameMarkerPeriod, 10) * 1000;
	// Таймер обновления маркера игры.
	this.GameMarkerTimer = 0;
	// Игровые показатели игрока.
	this.Statistics = {
		TotalGames: 0,
		WinGames: 0,
		LoseGames: 0,
		DrawGames: 0,
		Rating: 0
	};
}
/**
 *	 Обновление игровых показателей игрока. Отправляется AJAX-запрос на сервер.
 *
 */
modelPlayer.prototype.StatisticsReload = function (Callback) {
	console.log('modelPlayer.prototype.StatisticsReload');
	var self = this;
	// AJAX-запрос.
	$.post(
		this.URL.Base + this.URL.PlayerStatistics,
		{
			PlayerID: this.ID
		},
		function(Result) {
			// Если нет ошибок:
			if (!Result.Error) {
				// Текущие игровые показатели.
				self.Statistics = Result;
			}
			// Если ошибка данных:
			else if (Result.Error == self.ErrorTypes.DataError) {
				
			}
			// Если неизвестная ошибка:
			else
				console.log(self.URL.Base + self.URL.PlayerStatistics + ' : Error = ' + Result.Error);
			// Если задана callback-функция:
			if (typeof Callback === 'function')
				Callback();
		},
		'json'
	)
}
/**
 *	Получение идентификатора игрока.
 *
 */
modelPlayer.prototype.GetID = function () {
	return this.ID;
}
/**
 *	Получение имени игрока.
 *
 */
modelPlayer.prototype.GetName = function () {
	return this.Name;
}
/**
 *	Установка игровых показателей игрока.
 *
 */
modelPlayer.prototype.SetStatistics = function (Statistics) {
	this.Statistics = Statistics;
}
/**
 *	Получение игровых показателей игрока.
 *
 */
modelPlayer.prototype.GetStatistics = function (Parameter) {
	// Если указанный показатель существует:
	if (typeof this.Statistics[Parameter] !== 'undefined' && this.Statistics[Parameter] !== null)
		return this.Statistics[Parameter];
	return this.Statistics;
}
/**
 *	Регистрация маркера игрока. Отправляется AJAX-запрос на сервер.
 *	Во время игры каждому игроку необходимо периодически обновлять маркер.
 *	Маркер имеет срок действия.
 *	Это позволяет контролировать выход игрока из игры.
 *	В ответе на запрос сервер передает состояния маркеров всех соперников текущей игры.
 *	Если соперник вышел из игры, состояние маркера false, иначе true.
 *
 */
modelPlayer.prototype.GameMarkerSet = function (Callback) {
	console.log('modelPlayer.prototype.GameMarkerSet');
	var self = this;
	// AJAX-запрос.
	$.post(
		this.URL.Base + this.URL.PlayerGameMarker,
		{
			PlayerID: this.ID
		},
		function(GameMarkersList) {
			// Если нет ошибок:
			if (!GameMarkersList.Error) {
				// Проверка маркеров всех игроков текущей игры.
				$.each(GameMarkersList, function(Index, Value) {
					// Если один из игроков вышел из текущей игры и задана callback-функция:
					if (!Value && typeof Callback === 'function')
						Callback();
				});
			}
			// Если неизвестная ошибка:
			else
				console.log(self.URL.Base + self.URL.PlayerGameMarker + ' : Error = ' + GameMarkersList.Error);
		},
		'json'
	)
}
/**
 *	Запуск таймера обновления списка соперников.
 *
 */
modelPlayer.prototype.GameMarkerStart = function (Callback) {
	console.log('modelPlayer.prototype.GameMarkerStart');
	// Если таймер еще не запущен:
	if (!this.GameMarkerTimer)
		// Запуск таймера.
		this.GameMarkerTimer = setInterval(this.GameMarkerSet.bind(this, Callback), this.GAME_MARKER_PERIOD);
}
/**
 *	Выключение таймера обновления списка соперников.
 *
 */
modelPlayer.prototype.GameMarkerStop = function () {
	console.log('modelPlayer.prototype.GameMarkerStop');
	clearInterval(this.GameMarkerTimer);
	this.GameMarkerTimer = 0;
}





/**
 *	Модель коллекции объектов.
 *
 */
modelCollection = function (Settings) {
	// Идентификатор игрока.
	if (typeof Settings.PlayerID !== 'undefined' && Settings.PlayerID !== null)
		this.PlayerID = parseInt(Settings.PlayerID, 10);
	// Адреса типа контроллер / действие на сервере.
	if (typeof Settings.URL !== 'undefined' && Settings.URL !== null)
		this.URL = Settings.URL;
	// Callback-функция.
	if (typeof Settings.Callback === 'function')
		this.Callback = Settings.Callback;
	// Типы ошибок.
	if (typeof Settings.ErrorTypes !== 'undefined' && Settings.ErrorTypes !== null)
		this.ErrorTypes = Settings.ErrorTypes;
	// Период таймера обновления списка объектов коллекции (секунды).
	if (typeof Settings.TimerPeriod === 'number')
		this.TIMER_PERIOD = parseInt(Settings.TimerPeriod, 10) * 1000;
	else
		this.TIMER_PERIOD = 10 * 1000;
	// Список объектов коллекции.
	this.List = [];
	// Таймер обновления коллекции.
	this.Timer = 0;
	//
	this.Index = 0;
}
/**
 *	Обновление коллекции. Отправляется AJAX-запрос на сервер.
 *
 */
modelCollection.prototype.ListReload = function () {
	var self = this;
	// AJAX-запрос.
	$.post(
		this.URL.Base + this.URL.ListGet,
		{
			PlayerID: this.PlayerID
		},
		function(List) {
			// Если нет ошибок:
			if (!List.Error) {
				// Обновление списка объектов коллекции.
				self.List = List;
				//
				self.PointerReset();
				// Если задана callback-функция:
				if (typeof self.Callback === 'function')
					self.Callback();
			}
			// Если неизвестная ошибка:
			else
				console.log(self.URL.Base + self.URL.ListGet + ' : Error = ' + List.Error);
		},
		'json'
	)
}
/**
 *	Запуск таймера обновления коллекции.
 *
 */
modelCollection.prototype.ListRefreshStart = function () {
	// Первое обновление коллекции.
	this.ListReload();
	// Если таймер еще не запущен:
	if (!this.Timer)
		// Запуск таймера обновления коллекции.
		this.Timer = setInterval(this.ListReload.bind(this), this.TIMER_PERIOD);
}
/**
 *	Выключение таймера обновления коллекции.
 *
 */
modelCollection.prototype.ListRefreshStop = function () {
	clearInterval(this.Timer);
	this.Timer = 0;
}
/**
 *	Получение списка объектов коллекции.
 *
 */
modelCollection.prototype.ListGet = function () {
	return this.List;
}
/**
 *	Получение размера коллекции.
 *
 */
modelCollection.prototype.ListSizeGet = function () {
	return this.List.length;
}
/**
 *	Сброс указателя коллекции.
 *
 */
modelCollection.prototype.PointerGet = function () {
	return this.Index;
}
/**
 *	Сброс указателя коллекции.
 *
 */
modelCollection.prototype.PointerReset = function () {
	this.Index = 0;
	return this.Index;
}
/**
 *	Сброс указателя коллекции.
 *
 */
modelCollection.prototype.PointerShift = function () {
	if (this.Index == (this.ListSizeGet() - 1))
		this.PointerReset();
	else
		this.Index = this.Index + 1;
	return this.Index;
}
/**
 *	Получение объекта коллекции по указанному идентификатору.
 *
 */
modelCollection.prototype.ItemGet = function (ID) {
	var Item = false;
	// Поиск в коллекции указанного объекта.
	if (Array.isArray(this.List)) {
		$.each(this.List, function(Key, Value) {
			if (Value.ID == ID)
				Item = Value;
		});
	}
	// Возвращение объекта коллекции.
	return Item;
}
/**
 *	Получение следующего объекта коллекции.
 *
 */
modelCollection.prototype.NextItemGet = function () {
	//
	var Index = this.PointerGet();
	//
	this.PointerShift();
	// Возвращение объекта коллекции.
	return this.List[Index];
}




/**
 *	Модель проигрывателя звуковых файлов.
 *
 */
modelSound = function () {
	this.Mute = false;
	this.Audio;
}
/**
 *	Включение воспроизведения звукового файла.
 *
 */
modelSound.prototype.Play = function (Name) {
	console.log('modelSound.prototype.Play');
	// Если звук не отключен:
	if (!this.Mute) {
		this.Audio = $('#' + Name)[0];
		this.Audio.play();
	}
}
/**
 *	Выключение воспроизведения звукового файла.
 *
 */
modelSound.prototype.Stop = function () {
	console.log('modelSound.prototype.Stop');
	this.Audio.pause();
}
/**
 *	Включение / выключение звука.
 *
 */
modelSound.prototype.Toggle = function () {
	console.log('modelSound.prototype.Toggle');
	this.Mute = !this.Mute;
}
/**
 *	Включение звука.
 *
 */
modelSound.prototype.On = function () {
	console.log('modelSound.prototype.On');
	this.Mute = false;
}
/**
 *	Выключение звука.
 *
 */
modelSound.prototype.Off = function () {
	console.log('modelSound.prototype.Off');
	this.Mute = true;
}





/**
 *	Модель таймера.
 *	Работает в двух режимах: прямой и обратный отсчет.
 *	Интервал отсчета составляет 1 секунду.
 *
 */
modelTimer = function (Settings) {
	// Настройка таймера.
	this.Set(Settings);
	this.Timer = 0;
}
/**
 *	Настройка таймера.
 *
 */
modelTimer.prototype.Set = function (Settings) {
	console.log('modelTimer.prototype.Set');
	// Если указан режим работы (направление отсчета) таймера:
	if (typeof Settings !== 'undefined' && typeof Settings.Countdown === 'boolean')
		this.CountdownMode = Settings.Countdown;
	else
		// По умолчанию устанавливается режим прямого отсчета.
		this.CountdownMode = false;
	// Если указано начальное значение таймера:
	if (typeof Settings !== 'undefined' && typeof Settings.StartValue !== 'undefined')
		this.StartValue = Settings.StartValue;
	// Если установлен режим обратного отсчета:
	else if (this.CountdownMode)
		// По умолчанию для обратного отсчета.
		this.StartValue = 60;
	else
		// По умолчанию для прямого отсчета.
		this.StartValue = 0;
}
/**
 *	Получение текущего значения таймера.
 *
 */
modelTimer.prototype.Get = function () {
	// console.log('modelTimer.prototype.Get');
	return this.Value;
}
/**
 *	Запуск таймера.
 *
 */
modelTimer.prototype.Start = function (Settings) {
	console.log('modelTimer.prototype.Start');
	// Настройка таймера.
	this.Set(Settings);
	// Установка начального значения таймера.
	this.Value = this.StartValue;
	var self = this;
	// Если таймер еще не запущен:
	if (!this.Timer)
		// Запуск таймера с интервалом отсчета 1 секунда.
		this.Timer = setInterval(function() {
			// Если режим прямого отсчета:
			if (!self.CountdownMode)
				self.Value++;
			// Если режим обратного отсчета и значение не равно нулю:
			else if (self.Value)
				self.Value--;
			else
				// Выключение таймера.
				self.Stop();
			// Если задана Callback-функция, вызов Callback-функции.
			if (typeof Settings.Callback !== 'undefined' && typeof Settings.Callback === 'function')
				Settings.Callback();
		}, 1000);
}
/**
 *	Выключение таймера.
 *
 */
modelTimer.prototype.Stop = function () {
	console.log('modelTimer.prototype.Stop');
	clearInterval(this.Timer);
	this.Timer = 0;
}
