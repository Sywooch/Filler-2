
/**
 *	Основное представление игрового интерфейса работает в двух режимах:
 *		- Режим работы с лобби:
 *			- Список лобби
 *			- Список игроков
 *			- Статистика игрока
 *			- Создание или подключение к лобби
 *		- Режим игры:
 *			- Статистика игрока
 *			- Игроки текущей игры
 *			- Подсветка игрока
 *
 */
viewScoreboard = function (TextLable) {
	// Текстовые надписи.
	this.TextLable = TextLable;
}
/**
 *	Включение представления режима лобби.
 *
 */
viewScoreboard.prototype.LobbyMode = function () {
	$('#Mode-2').hide();
	$('#Mode-1').show();
}
/**
 *	Включение представления режима игры.
 *
 */
viewScoreboard.prototype.GameMode = function () {
	$('#Mode-1').hide();
	$('#Mode-2').show();
}
/**
 *	Обновление представления списка лобби.
 *
 */
viewScoreboard.prototype.LobbiesListRefresh = function (LobbiesList, Handler) {
	// console.log('viewScoreboard.prototype.LobbiesListRefresh');
	// console.time('LobbiesListRefresh');
	// Если список лобби не имеет нужный формат:
	if (!Array.isArray(LobbiesList))
		LobbiesList = Array();
	// Массив лобби (<div>-блоков).
	var LobbiesBlocks = '';
	// Форматирование полученного списка лобби.
	for (LobbyIndex = 0; LobbyIndex < LobbiesList.length; LobbyIndex++) {
		// Формирование представления списка лобби.
		LobbiesBlocks = LobbiesBlocks + 
		'<div class="Lobby" id="' + LobbiesList[LobbyIndex].ID + '" onClick="' + Handler + '(' + LobbiesList[LobbyIndex].ID + ')"> \
			<div class="Lobby-Name" title="' + this.TextLable.Name + '">' + LobbiesList[LobbyIndex].Name + '</div> \
			<div class="Lobby-Settings" title="' + this.TextLable.Features + '">' + this.TextLable.Player + ': ' + LobbiesList[LobbyIndex].PlayersNumber + '\
&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;' + this.TextLable.Color + ': ' + LobbiesList[LobbyIndex].ColorsNumber + '\
&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;' + this.TextLable.Size + ': ' + LobbiesList[LobbyIndex].SizeX + ' x ' + LobbiesList[LobbyIndex].SizeY + '</div>\
		</div>';
	}
	// Вывод списка лобби.
	$('#LobbiesList').html(LobbiesBlocks);
	// Вывод количества лобби в списке.
	$('#LobbiesQuantity').html(this.TextLable.InTotal + ': ' + LobbiesList.length);
	$('#LobbiesQuantity-xs').html(this.TextLable.InTotal + ': ' + LobbiesList.length);
	// console.timeEnd('LobbiesListRefresh');
}
/**
 *	Обновление представления списка соперников.
 *
 */
viewScoreboard.prototype.CompetitorsListRefresh = function (CompetitorsList, Handler) {
	// console.log('viewScoreboard.prototype.CompetitorsListRefresh');
	// console.time('CompetitorsListRefresh');
	// Массив соперников (<div>-блоков).
	var CompetitorsBlocks = '';
	// Стиль CSS отображения соперника. Зависит от статуса соперника.
	var CompetitorBlockCSS;
	// Название статуса соперника (свободен / в игре).
	var PlayerStatus;
	//
	var bot;
	// Форматирование полученного списка соперников.
	for (PlayerIndex = 0; PlayerIndex < CompetitorsList.length; PlayerIndex++) {
		bot = CompetitorsList[PlayerIndex].bot;
		// Если соперник свободен:
		if (CompetitorsList[PlayerIndex].Status) {
			CompetitorBlockCSS = bot ? 'Bot Bot-Active' : 'Player Player-Active';
			PlayerStatus = this.TextLable.Free;
			PlayerStatusCSS = bot ? 'Bot-Status-Active' : 'Player-Status-Active';
		}
		// Если соперник занят:
		else {
			CompetitorBlockCSS = bot ? 'Bot Bot-Active' : 'Player Player-NotActive';
			PlayerStatus = this.TextLable.Busy;
			PlayerStatusCSS = '';
		}

		// if (CompetitorsList[PlayerIndex].bot)
			// CompetitorBlockCSS = bot ? 'Bot' : 'Player';

		// Формирование представления списка соперников.
		CompetitorsBlocks = CompetitorsBlocks + 
		'<div class="' + CompetitorBlockCSS + '" id="' + CompetitorsList[PlayerIndex].ID + '" onClick="' + Handler + '(' + CompetitorsList[PlayerIndex].ID + ')"> \
			 <div class="Player-Name" title="' + this.TextLable.PlayerName + '">' + CompetitorsList[PlayerIndex].Name + '</div> \
			 <div class="Player-WinningStreak" title="' + this.TextLable.WinningStreak + '">&#9733; ' + CompetitorsList[PlayerIndex].WinningStreak + '</div> \
			 <div class="Player-Rating" title="' + this.TextLable.Rating + '">' + CompetitorsList[PlayerIndex].Rating + '</div> \
			 <div class="Player-Status ' + PlayerStatusCSS + '" title="' + this.TextLable.PlayerStatus + '">&nbsp;' + PlayerStatus + '</div> \
		</div>';
	}
	// Вывод списка соперников.
	$('#PlayersList').html(CompetitorsBlocks);
	// Вывод количества соперников.
	$('#PlayersQuantity').html(this.TextLable.InTotal + ': ' + CompetitorsList.length);
	// console.timeEnd('CompetitorsListRefresh');
}
/**
 *	Выключение отображения всех игроков текущей игры.
 *
 */
viewScoreboard.prototype.GamePlayersHide = function () {
	$('#PlayersList-1').html('');
	$('#PlayersList-2').html('');
}
/**
 *	Включение отображение всех игроков текущей игры.
 *
 */
viewScoreboard.prototype.GamePlayersShow = function (PlayersList) {
	// console.log('viewScoreboard.prototype.GamePlayersShow');
	//
	$('#PlayersList-1').html('');
	$('#PlayersList-2').html('');
	var self = this;
	// Создание представлений для всех игроков текущей игры.
	if (Array.isArray(PlayersList)) {
		PlayersList.forEach(function(Item, Index) {
			// Размещение представлений игроков слева и справа от игрового поля.
			if ((Index / 2) == Math.floor(Index / 2))
				$('#PlayersList-1').html($('#PlayersList-1').html() + self.GamePlayerAdd(Item));
			else
				$('#PlayersList-2').html($('#PlayersList-2').html() + self.GamePlayerAdd(Item));
		});
	}
}
/**
 *	Добавление в игру представления указанного игрока.
 *
 */
viewScoreboard.prototype.GamePlayerAdd = function (Player) {
	return '<div class="col-xs-24 Player-Box" id="PlayerID-' + Player.ID + '"> \
				<div class="col-xs-16 Player-Box-1" id="NamePlayerID-' + Player.ID + '">' + Player.Name + '</div> \
				<div class="col-xs-8 Player-Box-2" id="PortionPlayerID-' + Player.ID + '">0%</div> \
				<div class="col-xs-18 Player-Box-3" id="PointsPlayerID-' + Player.ID + '">' + this.TextLable.Point + ': 0</div> \
				<div class="col-xs-6 Player-Box-4" id="IncrementPlayerID-' + Player.ID + '"></div> \
				<div class="col-xs-24 Player-Box-5" id="TimePlayerID-' + Player.ID + '"></div> \
			</div>';
}
/**
 *	Выделение представления указанного игрока (делающего ход).
 *
 */
viewScoreboard.prototype.GamePlayerHighlight = function (PlayerID) {
	// Выключение индикатора текущего хода всех игроков.
	$('.NextMoveIndicator').removeClass('NextMoveIndicator');
	// Включение индикатора текущего хода указанного игрока.
	$('#PlayerID-' + PlayerID).addClass('NextMoveIndicator');
	// Выключение индикатора времени всех игроков.
	$('.Player-Box-5').html('');
}
/**
 *	Блокирование представления указанного игрока (признавшего поражение).
 *
 */
viewScoreboard.prototype.GamePlayerOff = function (PlayerID, Status) {
	// 
	$('#PlayerID-' + PlayerID).addClass('Player-Box-Off');
	$('#NamePlayerID-' + PlayerID).addClass('Player-Lable-Off');
	$('#PortionPlayerID-' + PlayerID).addClass('Player-Lable-Off');
	$('#PointsPlayerID-' + PlayerID).addClass('Player-Lable-Off');
	$('#IncrementPlayerID-' + PlayerID).html(Status);
	$('#IncrementPlayerID-' + PlayerID).addClass('Player-Status-Off');
}
/**
 *	Обновление представления игровых показателей всех игроков для текущей игры.
 *
 */
viewScoreboard.prototype.PlayersScoreRefresh = function (PlayersList, ColorsList) {
	// console.log('viewScoreboard.prototype.PlayersScoreRefresh');
	var self = this;
	if (Array.isArray(PlayersList)) {
		PlayersList.forEach(function(Player, Index) {
			// Отображение количества баллов текущего игрока.
			self.PlayerPointsRefresh(Player);
			// Отображение доли игрового поля текущего игрока.
			self.PlayerPortionRefresh(Player);
			// Отображение результата последнего хода.
			self.PlayerIncrementRefresh(Player, ColorsList);
			
			// // Отображение количества баллов текущего игрока.
			// $('#PointsPlayerID-' + Player.ID).html(self.TextLable.Point + ': ' + Player.Points);
			// // Отображение доли игрового поля текущего игрока.
			// $('#PortionPlayerID-' + Player.ID).html(Player.Portion + '%');
			// //
			// var MoveResult = '';
			// // Если известен результат последнего хода текущего игрока:
			// if (Player.LastMove)
			// 	MoveResult = '+' + Player.LastMove.CellNumber;
			// // Отображение результата последнего хода.
			// $('#IncrementPlayerID-' + Player.ID).html(MoveResult + '<div class="Player-Color" id="Player-Color-' + Player.ID + '"></div>');
			// // Установка меркеру текущего цвета игрока указанного цвета.
			// $('#Player-Color-' + Player.ID).css({'background-color': '#' + ColorsList[Player.ColorIndex]});
		});
	}
}
/**
 *
 *
 */
viewScoreboard.prototype.PlayerPointsRefresh = function (Player) {
	// Отображение количества баллов текущего игрока.
	$('#PointsPlayerID-' + Player.ID).html(this.TextLable.Point + ': ' + Player.Points);
}
/**
 *
 *
 */
viewScoreboard.prototype.PlayerPortionRefresh = function (Player) {
	// Отображение доли игрового поля текущего игрока.
	$('#PortionPlayerID-' + Player.ID).html(Player.Portion + '%');
}
/**
 *
 *
 */
viewScoreboard.prototype.PlayerIncrementRefresh = function (Player, ColorsList) {
	//
	var MoveResult = '';
	// Если известен результат последнего хода текущего игрока:
	if (Player.LastMove)
		MoveResult = '+' + Player.LastMove.CellNumber;
	// Отображение результата последнего хода.
	$('#IncrementPlayerID-' + Player.ID).html(MoveResult + '<div class="Player-Color" id="Player-Color-' + Player.ID + '"></div>');
	// Установка меркеру текущего цвета игрока указанного цвета.
	$('#Player-Color-' + Player.ID).css({'background-color': '#' + ColorsList[Player.ColorIndex]});
}
/**
 *	Обновление представления таймера хода указанного игрока.
 *	Входной параметр Timer в секундах.
 *
 */
viewScoreboard.prototype.PlayerTimerRefresh = function (PlayerID, Timer) {
	// Вычисление значений минут и секунд.
	var D = new Date(Timer * 1000);
	var Minutes = D.getMinutes();
	Minutes = Minutes < 10 ? '0' + Minutes : Minutes;
	var Seconds = D.getSeconds();
	Seconds = Seconds < 10 ? '0' + Seconds : Seconds;
	// Отображение значения таймера в формате 00:00.
	$('#TimePlayerID-' + PlayerID).html(Minutes + ':' + Seconds);
}
/**
 *	Обновление иконки состояния переключателя звука.
 *
 */
viewScoreboard.prototype.SoundIconRefresh = function (Mute) {
	// Если звук включен:
	if (!Mute) {
		$('#Button-Sound').removeClass('Sound-Label-Off');
		$('#Button-Sound').addClass('Sound-Label-On');
	}
	// Если звук выключен:
	else {
		$('#Button-Sound').removeClass('Sound-Label-On');
		$('#Button-Sound').addClass('Sound-Label-Off');
	}
}





/**
 *	Представление собственного игрока.
 *
 */
viewPlayer = function () {

}
/**
 *	Обновление статистики собственного игрока.
 *
 */
viewPlayer.prototype.StatisticsRefresh = function (Statistics) {
	// Если игровой показатель "Количество игр" имеет правильный формат:
	if (typeof Statistics.TotalGames !== 'undefined' && Statistics.TotalGames !== null) {
		$('#PlayerGames').html(Statistics.TotalGames);
		$('#PlayerGames-xs').html(Statistics.TotalGames);
	}
	// Если игровой показатель "Количество побед" имеет правильный формат:
	if (typeof Statistics.WinGames !== 'undefined' && Statistics.WinGames !== null) {
		$('#PlayerWinnings').html(Statistics.WinGames);
		$('#PlayerWinnings-xs').html(Statistics.WinGames);
	}
	// Если игровой показатель "Рейтинг" имеет правильный формат:
	if (typeof Statistics.Rating !== 'undefined' && Statistics.Rating !== null) {
		$('#PlayerRating').html(Statistics.Rating);
		$('#PlayerRating-xs').html(Statistics.Rating);
	}
}





/**
 *	Контроллер поочередного отображения диалоговых окон.
 *
 */
var ModalControl = {
	State: true,
	ID: null,
	Show: function (ID) {
		console.log('ModalControl.Show : #' + ID);
		if (this.State) {
			console.log('if (this.State == true) : #' + ID);
			this.State = false;
			$('#' + ID).modal('show');
		}
		else {
			console.log('if (this.State == false) : #' + ID);
			this.ID = ID;
		}
	},
	Hide: function () {
		console.log('ModalControl.Hide');
		if (this.ID) {
			console.log('if (this.ID == true) : #' + this.ID);
			$('#' + this.ID).modal('show');
			this.ID = null;
		}
		else {
			console.log('if (this.ID == false)');
			this.State = true;
		}
	}
};





/**
 *	Диалоговое окно.
 *
 */
DialogWindow = function(ID, Options, ElementsList) {
	// Идентификаторы.
	this.ID = ID;
	// Callback-функция.
	this.Callback;
	// Опции.
	this.Options = Options;
	// 
	if (Array.isArray(ElementsList)) {
		$.each(ElementsList, function(Key, ElementID) {
			$('#' + ElementID).hide();
		});
	}
}
/**
 *	Открытие диалогового окна.
 *
 */
DialogWindow.prototype.Show = function () {
	// Контроллер поочередного отображения диалоговых окон.
	ModalControl.Show(this.ID.Window);
}
/**
 *	Закрытие диалогового окна.
 *
 */
DialogWindow.prototype.Hide = function () {
	$('#' + this.ID.Window).modal('hide');
}
/**
 *	Установка заголовка диалогового окна.
 *
 */
DialogWindow.prototype.CaptionSet = function (Caption) {
	$('#' + this.ID.Caption).html(Caption);
}
/**
 *	Установка сообщения диалогового окна.
 *
 */
DialogWindow.prototype.MessageSet = function (Message) {
	$('#' + this.ID.Message).html(Message);
}
/**
 *	Установка названия указанной кнопки.
 *	Если название кнопки не указано, кнопка не отображается.
 *
 */
DialogWindow.prototype.ButtonSet = function (ButtonID, Caption) {
	$('#' + ButtonID).html(Caption);
	if (Caption)
		$('#' + ButtonID).show();
	else
		$('#' + ButtonID).hide();
}
/**
 *	Включение отображения индикатора загрузки.
 *
 */
DialogWindow.prototype.LoadingShow = function () {
	$('#' + this.ID.Loading).show();
}
/**
 *	Выключение отображения индикатора загрузки.
 *
 */
DialogWindow.prototype.LoadingHide = function () {
	$('#' + this.ID.Loading).hide();
}





$(document).ready(function () {

	/**
	 *	Представление диалогового окна просмотра лобби.
	 *
	 */
	LobbyDialog = new DialogWindow(
		// Идентификаторы.
		{
			Window: 'LobbyDialog', 
			Caption: 'LobbyTitle', 
			Settings: 'LobbyDialog-LobbySettings', 
			Loading: 'LobbyDialog-Loading'
		},
		// Опции.
		{
			TextLable: DIALOG.LobbyViewDialog
		}
	);
	/**
	 *	Включение режима автора лобби.
	 *
	 */
	LobbyDialog.MasterModeOn = function () {
		$('#GameStartButton').show();
		$('#LobbyJoinButton').hide();
	}
	/**
	 *	Выключение режима автора лобби.
	 *
	 */
	LobbyDialog.MasterModeOff = function () {
		$('#GameStartButton').hide();
		$('#LobbyJoinButton').show();
	}
	/**
	 *	Включение режима истекшего срока действия лобби.
	 *
	 */
	LobbyDialog.ExpireMode = function () {
		$('#GameStartButton').hide();
		$('#LobbyJoinButton').hide();
	}
	/**
	 *	Обновление представления списка подключенных игроков лобби.
	 *
	 */
	LobbyDialog.PlayersListSet = function (PlayersList) {
		// Представления списка игроков.
		var PlayersBlocks = '';
		// Если в списке подключенных несколько игроков:
		if (Array.isArray(PlayersList)) {
			PlayersList.forEach(function(Item, Index) {
				// Добавление текущего игрока в представление списка игроков.
				PlayersBlocks = PlayersBlocks + (Index + 1) + '. '+ Item.Name + '<br><br>';
			});
			PlayersBlocks = PlayersBlocks + '<br>';
		}
		// Если в списке подключенных один игрок:
		else
			PlayersBlocks = '1. ' + PlayersList + '<br><br><br>';
		// Вывод списка игроков.
		$('#LobbyPlayersList').html(PlayersBlocks);
	}
	/**
	 *	Обновление представления подсказки лобби.
	 *
	 */
	LobbyDialog.TipSet = function (Tip) {
		$('#LobbyTip').html(Tip);
	}
	/**
	 *	Обновление представления параметров лобби.
	 *
	 */
	LobbyDialog.Configuration = function (PlayersNumber, ColorsNumber, SizeX, SizeY) {
		$('#' + this.ID.Settings).html(
			// Количество игроков.
			this.Options.TextLable.Settings.Player + ': ' + PlayersNumber + 
			'&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;' + 
			// Количество цветов.
			this.Options.TextLable.Settings.Color + ': ' + ColorsNumber + 
			'&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;' + 
			// Размер игрового поля.
			this.Options.TextLable.Settings.Size + ': ' + SizeX + ' x ' + SizeY
		);
	}
	/**
	 *	Установка состояния указанной кнопки.
	 *
	 */
	LobbyDialog.ButtonSet = function (ID, State) {
		$('#' + ID).attr('disabled', State);
	}
	/**
	 *	Активация указанной кнопки.
	 *
	 */
	LobbyDialog.ButtonEnabled = function (ID) {
		$('#' + ID).attr('disabled', false);
	}
	/**
	 *	Блокирование указанной кнопки.
	 *
	 */
	LobbyDialog.ButtonDisabled = function (ID) {
		$('#' + ID).attr('disabled', true);
	}
	/**
	 *	Обновление данных и элементов управления диалогового окна просмотра лобби.
	 *
	 */
	LobbyDialog.Refresh = function (Lobby, PlayerID) {
		// Если не передан объект лобби:
		if (typeof Lobby === 'undefined' || typeof Lobby !== 'object')
			return;
		// Установка заголовка окна просмотра лобби.
		this.CaptionSet(Lobby.Name);
		// Установка параметров лобби.
		this.Configuration(Lobby.PlayersNumber, Lobby.ColorsNumber, Lobby.SizeX, Lobby.SizeY);
		// Обновление списка игроков в окне лобби.
		this.PlayersListSet(Lobby.GetPlayersList());
		// Если срок действия лобби истек:
		if (!Lobby.Active) {
			// Установка соответствующей подсказки.
			this.TipSet(this.Options.TextLable.Tip.Expire);
			// Включение режима истекшего срока действия лобби.
			this.ExpireMode();
			// Активация кнопки закрытия окна.
			this.ButtonEnabled('LobbyClose');
			// Выключение отображения таймера лобби.
			this.TimerRefresh();
		}
		// Если игрок является автором лобби:
		else if (Lobby.GetCreatorID() == PlayerID) {
			// Если подключились все игроки:
			if (Lobby.isFullPlayersList()) {
				this.TipSet(this.Options.TextLable.Tip.Start);
				this.ButtonEnabled('GameStartButton');
			}
			// Если подключились не все игроки:
			else {
				this.TipSet(this.Options.TextLable.Tip.WaitPlayers);
				this.ButtonDisabled('GameStartButton');
			}
			// Блокирование кнопки закрытия окна.
			this.ButtonDisabled('LobbyClose');
			// Включение режима автора лобби.
			this.MasterModeOn();
		}
		// Если игрок не является автором лобби:
		else {
			// Если игрок подключился к лобби:
			if (Lobby.isPlayerIncluded(PlayerID)) {
				// Если подключились все игроки:
				if (Lobby.isFullPlayersList()) {
					this.TipSet(this.Options.TextLable.Tip.WaitStart);
				}
				// Если подключились не все игроки:
				else {
					this.TipSet(this.Options.TextLable.Tip.WaitPlayers);
				}
				// Блокирование кнопок подключения к лобби и закрытия окна.
				this.ButtonDisabled('LobbyJoinButton');
				this.ButtonDisabled('LobbyClose');
			}
			// Если игрок не подключился к лобби:
			else {
				// Если подключились все игроки:
				if (Lobby.isFullPlayersList()) {
					this.TipSet(this.Options.TextLable.Tip.NoPlace);
					this.ButtonDisabled('LobbyJoinButton');
				}
				// Если подключились не все игроки:
				else {
					this.TipSet(this.Options.TextLable.Tip.WaitPlayers);
					this.ButtonEnabled('LobbyJoinButton');
				}
				// Активация кнопки Закрыть.
				this.ButtonEnabled('LobbyClose');
				// Выключение отображения таймера лобби.
				this.TimerRefresh();
			}
			// Выключение режима автора лобби.
			this.MasterModeOff();
		}
	}
	/**
	 *	Обновление представления таймера лобби.
	 *
	 */
	LobbyDialog.TimerRefresh = function (Timer) {
		// Если значение таймера лобби не задано:
		if (typeof Timer === 'undefined') {
			// Выключение отображения таймера лобби.
			$('#LobbyTimer').html('');
			return;
		}
		// Перевод значения таймера из секунд в формат минуты:секунды (00:00).
		var D = new Date(Timer * 1000);
		var Minutes = D.getMinutes();
		Minutes = Minutes < 10 ? '0' + Minutes : Minutes;
		var Seconds = D.getSeconds();
		Seconds = Seconds < 10 ? '0' + Seconds : Seconds;
		// Обновление представления таймера лобби.
		$('#LobbyTimer').html(Minutes + ':' + Seconds);
	}





	/**
	 *	Представление универсального окна сообщения.
	 *
	 */
	MessageDialog = new DialogWindow(
		// Идентификаторы.
		{
			Window: 'MessageDialog',
			Caption: 'MessageDialog-Caption', 
			Message: 'MessageDialog-Message',
			YesButton: 'MessageDialog-YesButton',
			NoButton: 'MessageDialog-NoButton',
			Loading: 'MessageDialog-Loading'
		},
		// Опции.
		{
			TextLable: DIALOG.MessageDialog
		}
	);
	/**
	 *	Открытие универсального окна сообщения.
	 *
	 */
	MessageDialog.Show = function (DialogData, Callback) {
		// Установка параметров универсального окна сообщения.
		this.Set(DialogData, Callback);
		ModalControl.Show(this.ID.Window);
	}
	/**
	 *	Закрытие универсального окна сообщения.
	 *
	 */
	MessageDialog.Hide = function () {
		$('#' + this.ID.Window).modal('hide');
	}
	/**
	 *	Установка параметров универсального окна сообщения.
	 *
	 */
	MessageDialog.Set = function (DialogData, Callback) {
		if (typeof DialogData !== 'undefined') {
			//
			if (typeof DialogData.Format === 'string')
				this.FormatSet(DialogData.Format);
			else
				this.FormatSet('');
			// Если указан индикатор загрузки, отображение индикатора загрузки.
			if (DialogData.Loading == true)
				this.LoadingShow();
			else
				this.LoadingHide();
			// Если указан заголовок окна, установка заголовка окна.
			if (typeof DialogData.Caption === 'string')
				this.CaptionSet(DialogData.Caption);
			// Если указан текст сообщения окна, установка сообщения окна.
			if (typeof DialogData.Message === 'string')
				this.MessageSet(DialogData.Message);
			// Если указан URL для AJAX-загрузки сообщения окна, загрузка сообщения окна.
			if (typeof DialogData.MessageURL === 'string')
				this.MessageLoad(DialogData.MessageURL);
			// Если указана кнопка положительного ответа, установка кнопки.
			if (typeof DialogData.YesButton === 'string')
				this.YesButtonSet(DialogData.YesButton);
			// Если указана кнопка отрицательного ответа, установка кнопки.
			if (typeof DialogData.NoButton === 'string')
				this.NoButtonSet(DialogData.NoButton);
		}
		// Установка Callback-функции.
		this.Callback = Callback;
	}
	/**
	 *	Загрузка текста сообщения с сервера по указанному URL. Отправляется AJAX-запрос на сервер.
	 *
	 */
	MessageDialog.MessageLoad = function (URL) {
		// Удаление сообщения диалогового окна.
		this.MessageSet('');
		// Включение отображения индикатора загрузки.
		this.LoadingShow();
		var self = this;
		// AJAX-запрос.
		$.post(
			URL,
			{

			},
			function(Message) {
				// Выключение отображения индикатора загрузки.
				self.LoadingHide();
				// Установка сообщения диалогового окна.
				self.MessageSet(Message);
			},
			'text'
		)
	}
	/**
	 *	Установка названия кнопки "Да".
	 *
	 */
	MessageDialog.YesButtonSet = function (Caption) {
		this.ButtonSet(this.ID.YesButton, Caption);
	}
	/**
	 *	Установка названия кнопки "Нет".
	 *
	 */
	MessageDialog.NoButtonSet = function (Caption) {
		this.ButtonSet(this.ID.NoButton, Caption);
	}
	/**
	 *	Получение представления данных об указанном игроке.
	 *
	 */
	MessageDialog.PlayerViewGet = function (Handler, Player, LobbyID) {
		// Если не передан объект игрока:
		if (typeof Player === 'undefined' || typeof Player !== 'object')
			return;
		// Формирование представления данных об указанном игроке.
		var PlayerData = 
			'<div class="col-xs-10 player-data-lable">' + this.Options.TextLable.PlayerName + ':</div><div class="col-xs-14 player-data">' + Player.Name + '</div> \
			<div class="col-xs-10 player-data-lable">' + this.Options.TextLable.Rating + ':</div><div class="col-xs-14 player-data">' + Player.Rating + '</div> \
			<div class="col-xs-10 player-data-lable">' + this.Options.TextLable.GamesNumber + ':</div><div class="col-xs-14 player-data">' + Player.TotalGames + '</div> \
			<div class="col-xs-10 player-data-lable">' + this.Options.TextLable.WinsNumber + ':</div><div class="col-xs-14 player-data">' + Player.WinGames + '</div> \
			<div class="col-xs-10 player-data-lable">' + this.Options.TextLable.WinningStreak + ':</div><div class="col-xs-14 player-data">' + Player.WinningStreak + '</div>';
		// Если у игрока есть действующее лобби, вывод ссылки на лобби.
		if (LobbyID)
			PlayerData = PlayerData + '<div class="col-xs-24 indent-top-md color-orange cursor-pointer" title="Перейти к лобби игрока" onClick="' + Handler + '(' + LobbyID + ')">Открыть лобби</div>';
		return PlayerData;
	}
	/**
	 *	Нажатие кнопки "Да".
	 *
	 */
	MessageDialog.YesButtonClick = function () {
		// Если задана callback-функция:
		if (typeof this.Callback !== 'undefined' && typeof this.Callback.YesButton === 'function')
			this.Callback.YesButton();
	}
	/**
	 *	Нажатие кнопки "Нет".
	 *
	 */
	MessageDialog.NoButtonClick = function () {
		// Если задана callback-функция:
		if (typeof this.Callback !== 'undefined' && typeof this.Callback.NoButton === 'function')
			this.Callback.NoButton();
	}
	/**
	 *	Установка формата универсального окна сообщения.
	 *
	 */
	MessageDialog.FormatSet = function (Format) {
		//
		$('#MessageDialog .modal-dialog').removeClass('modal-lg modal-md modal-sm');
		$('#' + this.ID.Message).removeClass('text-center text-left text-right');
		//
		if (Format === 'Notification') {
			$('#MessageDialog .modal-dialog').addClass('modal-sm');
			$('#' + this.ID.Message).addClass('text-center');
			$('#MessageDialog .modal-footer').attr('style', 'text-align: center !important;');
		}
		else {
			$('#MessageDialog .modal-dialog').addClass('modal-md');
			$('#' + this.ID.Message).addClass('text-left');
			$('#MessageDialog .modal-footer').attr('style', '');
		}
	}





	/**
	 *	Диалоговое окно создания лобби.
	 *
	 */
	LobbyCreateDialog = new DialogWindow(
		// Идентификаторы.
		{
			Window: 'LobbyCreateDialog', 
			Caption: 'LobbyLabel'
		},
		// Опции.
		{
			// Списки количества цветов.
			ColorsNumberList: {
				x2players: {
					'6': '6',
					'7': '7',
					'8': '8',
					'9': '9',
					'10': '10',
					'11': '11',
					'12': '12'
				},
				x4players: {
					'10': '10',
					'11': '11',
					'12': '12',
					'13': '13',
					'14': '14',
					'15': '15',
					'16': '16'
				}
			},
			// Список размеров игрового поля.
			SizeList: {
				A: {
					X: 18,
					Y: 12
				},
				B: {
					X: 24,
					Y: 16
				},
				C: {
					X: 30,
					Y: 20
				}
			}
		}
	);
	/**
	 *	Инициализация диалогового окна.
	 *
	 */
	LobbyCreateDialog.Init = function (LobbyName) {
		$('#LobbyName').val(LobbyName);
		$('#LobbyName').focus();
	}
	/**
	 *	Установка списка количества цветов.
	 *
	 */
	LobbyCreateDialog.ColorsNumberListSet = function (PlayersNumber) {
		// 
		var ColorsNumber = this.ColorsNumberGet();
		// 
		var ColorsNumberList = this.Options.ColorsNumberList.x2players;
		// 
		$('#ColorsNumber').empty();
		if (PlayersNumber == 4)
			ColorsNumberList = this.Options.ColorsNumberList.x4players;
		$.each(ColorsNumberList, function(key, value) {
			$("#ColorsNumber").append('<option style="padding: 7px 7px;" value="' + key + '">' + value + '</option>');
		});
		this.ColorsNumberSet(ColorsNumber);
	}
	/**
	 *	Установка количества ботов.
	 *
	 */
	LobbyCreateDialog.botsNumberSet = function (playersNumber) {
		if (playersNumber == 2) {
			$(".bot-mode-button").addClass('bot-off');
			$(".bot-mode-button:first").removeClass('bot-off');
			$(".bot-mode-button:hidden").removeClass('selected-check-button');
			return true;
		}
		else if (playersNumber == 4) {
			$(".bot-mode-button").removeClass('bot-off');
			return true;
		}
		else
			return false;
	}
	/**
	 *	Получения количества ботов.
	 *
	 */
	LobbyCreateDialog.botsNumberGet = function () {
		return $(".bot-mode-button.selected-check-button:not(.bot-off)").length;
	}
	/**
	 *	Получения количества ботов.
	 *
	 */
	LobbyCreateDialog.botsLevelGet = function () {
		return $('#botLevel').val();
	}
	/**
	 *	Получение выбранного пользователем количества цветов.
	 *
	 */
	LobbyCreateDialog.ColorsNumberGet = function () {
		return $('#ColorsNumber').val();
	}
	/**
	 *	Установка в списке количества цветов указанного значения.
	 *
	 */
	LobbyCreateDialog.ColorsNumberSet = function (ColorsNumber) {
		$("#ColorsNumber [value='" + ColorsNumber + "']").attr("selected", "selected");
	}
	/**
	 *	Получение установленного пользователем названия лобби.
	 *
	 */
	LobbyCreateDialog.LobbyNameGet = function () {
		return $('#LobbyName').val();
	}
	/**
	 *	Получение выбранного пользователем количества игроков.
	 *
	 */
	LobbyCreateDialog.PlayersNumberGet = function () {
		// var PlayersNumber = 2;
		// if ($('#x4Players').is(':checked'))
		// 	PlayersNumber = 4;
		// return PlayersNumber;
		if ($('#x2Players.selected-radio-button').length)
			return 2;
		else if ($('#x4Players.selected-radio-button').length)
			return 4;
		return false;
	}
	/**
	 *	Получение выбранного пользователем размера игрового поля.
	 *
	 */
	LobbyCreateDialog.SizeGet = function () {
		// Если выбран минимальный размер игрового поля:
		//if ($('#SizeA').is(':checked'))
		if ($('#SizeA.selected-radio-button').length)
			return this.Options.SizeList.A;
		// Если выбран средний размер игрового поля:
		// if ($('#SizeB').is(':checked'))
		if ($('#SizeB.selected-radio-button').length)
			return this.Options.SizeList.B;
		// Если выбран максимальный размер игрового поля:
		// if ($('#SizeC').is(':checked'))
		if ($('#SizeC.selected-radio-button').length)
			return this.Options.SizeList.C;
	}
	/**
	 *	Отображение ошибки.
	 *
	 */
	LobbyCreateDialog.ErrorShow = function () {
		$('#DivLobbyName').addClass('has-error');
		$('#LobbyName').focus();
		$('#LobbyName').tooltip('show');
	}
	/**
	 *	Скрытие ошибки.
	 *
	 */
	LobbyCreateDialog.ErrorHide = function () {
		$('#DivLobbyName').removeClass('has-error');
	}



	// Скрытие ошибки при вводе названия лобби.
	$("#LobbyName").keypress(LobbyCreateDialog.ErrorHide);

	// Инициализация соответствующего списка количества цветов при выборе
	// пользователем количества игроков.
	$('#x2Players').click(function() {
		// Количество игроков: 2.
		LobbyCreateDialog.ColorsNumberListSet(2);
		LobbyCreateDialog.botsNumberSet(2);
	});

	// Инициализация соответствующего списка количества цветов при выборе
	// пользователем количества игроков.
	$('#x4Players').click(function() {
		// Количество игроков: 4.
		LobbyCreateDialog.ColorsNumberListSet(4);
		LobbyCreateDialog.botsNumberSet(4);
	});

	// Диалоговое окно создания лобби закрыто.
	$('#LobbyCreateDialog').on('hidden.bs.modal', function () {
		// Очистка поля "Название лобби".
		$('#LobbyName').val('');
		ModalControl.Hide();
	});

	// Диалоговое окно создания лобби открыто.
	$('#LobbyCreateDialog').on('shown.bs.modal', function () {
		// Установка фокуса в поле "Название лобби".
		$('#LobbyName').focus();
	});

	// Окно сообщений закрыто.
	$('#MessageDialog').on('hidden.bs.modal', function () {
		ModalControl.Hide();
	});

	// Окно просмотра лобби закрыто.
	$('#LobbyDialog').on('hidden.bs.modal', function () {
		ModalControl.Hide();
	});

	// Нажатие кнопки "Да".
	$('#MessageDialog-YesButton').click(function() {
		MessageDialog.YesButtonClick();
	});

	// Нажатие кнопки "Нет".
	$('#MessageDialog-NoButton').click(function() {
		MessageDialog.NoButtonClick();
	});

	//
	// $('#x2Players').click(function() {
	// 	$('.player-mode-button').removeClass('selected-check-button');
	// 	$(this).addClass('selected-check-button');
	// });

	// //
	// $('#x4Players').click(function() {
	// 	$('.player-mode-button').removeClass('selected-check-button');
	// 	$(this).addClass('selected-check-button');
	// });

	//
	// $('.bot-mode-button').click(function() {
	// 	$(this).toggleClass('selected-check-button');
	// });

	//
	$('.check-button').click(function() {
		$(this).toggleClass('selected-check-button');
	});

	//
	$('.radio-button').click(function() {
		var name = $(this).attr('name');
		$(".radio-button[name='" + name + "']").removeClass('selected-radio-button');
		$(this).addClass('selected-radio-button');
	});

});
