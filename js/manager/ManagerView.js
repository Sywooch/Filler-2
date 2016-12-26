
ManagerView = function () {

}
/**
 *	Обновление представления списка лобби.
 *
 */
ManagerView.prototype.mapListLoad = function (mapList) {
	console.log('ManagerView.prototype.mapListLoad');
	// Если список лобби не имеет нужный формат:
	if (!Array.isArray(mapList))
		mapList = [];
	var mapBlock = '';
	if (Array.isArray(mapList)) {
		$.each(mapList, function(index, map) {
			mapBlock = mapBlock +
				'<option style="padding: 7px 7px;" value="' + map.id + '" selected>' + map.name + '</option>';
		});
	}
	// Вывод списка лобби.
	$('#mapList').html(mapBlock);
}

ManagerView.prototype.mapGet = function () {
	return $('#mapList').val();
}

ManagerView.prototype.mapNameGet = function () {
	return $('#mapName').val();
}

ManagerView.prototype.mapNameSet = function (value) {
	$('#mapName').val(value);
}

ManagerView.prototype.mapSizeGet = function () {
	return $('#mapSize').val();
}

ManagerView.prototype.mapSizeSet = function (value) {
	$('#mapSize').val(value);
}

ManagerView.prototype.mapDescriptionGet = function () {
	return $('#mapDescription').val();
}

ManagerView.prototype.mapDescriptionSet = function (value) {
	$('#mapDescription').val(value);
}

ManagerView.prototype.mapCommentGet = function () {
	return $('#mapComment').val();
}

ManagerView.prototype.mapCommentSet = function (value) {
	$('#mapComment').val(value);
}

ManagerView.prototype.mapEnableGet = function () {
	return $('#mapEnable').is(':checked') ? 1 : 0;
}

ManagerView.prototype.mapEnableSet = function (value) {
	$('#mapEnable').prop('checked', value);
}

ManagerView.prototype.mapReset = function () {
	this.mapNameSet('');
	this.mapDescriptionSet('');
	this.mapCommentSet('');
	this.mapSizeSet(1);
	this.mapEnableSet(false);
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
		// Если указан индикатор загрузки, отображение индикатора загрузки.
		if (typeof DialogData !== 'undefined' && DialogData.Loading == true)
			this.LoadingShow();
		else
			this.LoadingHide();
		// Если указан заголовок окна, установка заголовка окна.
		if (typeof DialogData !== 'undefined' && typeof DialogData.Caption === 'string')
			this.CaptionSet(DialogData.Caption);
		// Если указан текст сообщения окна, установка сообщения окна.
		if (typeof DialogData !== 'undefined' && typeof DialogData.Message === 'string')
			this.MessageSet(DialogData.Message);
		// Если указан URL для AJAX-загрузки сообщения окна, загрузка сообщения окна.
		if (typeof DialogData !== 'undefined' && typeof DialogData.MessageURL === 'string')
			this.MessageLoad(DialogData.MessageURL);
		// Если указана кнопка положительного ответа, установка кнопки.
		if (typeof DialogData !== 'undefined' && typeof DialogData.YesButton === 'string')
			this.YesButtonSet(DialogData.YesButton);
		// Если указана кнопка отрицательного ответа, установка кнопки.
		if (typeof DialogData !== 'undefined' && typeof DialogData.NoButton === 'string')
			this.NoButtonSet(DialogData.NoButton);
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

	// Окно сообщений закрыто.
	$('#MessageDialog').on('hidden.bs.modal', function () {
		ModalControl.Hide();
	});

});
