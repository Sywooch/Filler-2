
// Типы ошибок сервера.
var SERVER_ERROR = {
	dataError: 'DATA_ERROR',
	unknownError: '0'
}

// Настройки приложения.
var Application = {
	mapSize: [
		{
			sizeX: 18,
			sizeY: 12
		},
		{
			sizeX: 24,
			sizeY: 16
		},
		{
			sizeX: 30,
			sizeY: 20
		}
	]
}

/**
 *	Основной контроллер. Управляет всем приложением.
 *	Для начала работы контроллера необходимо вызвать действие Run.
 *
 */
ManagerController = function() {

}

ManagerController.Init = function() {

	//
	window.ManagerModel = new ManagerModel({
		// Адреса типа контроллер / экшен на сервере.
		URL: {
			base: BASE_URL,
			mapSave: '/manager/mapsave',
			mapListLoad: '/manager/maplistload',
			mapLoad: '/manager/mapload'
		},
		// Типы ошибок сервера.
		errorTypes: SERVER_ERROR
	});



	//
	window.ManagerView = new ManagerView({

	});



	// Список карт.
	window.mapCollectionModel = new modelCollection({
		// Адреса типа контроллер / экшен на сервере.
		URL: {
			base: BASE_URL,
			listLoad: '/manager/maplistload'
		},
		// Типы ошибок сервера.
		errorTypes: SERVER_ERROR,
		// Период обновления (секунды).
		timerPeriod: 5,
		// Callback-функция.
		callback: ManagerController.MapListReady
	});



	// Игровое поле с заданными параметрами.
	window.GameMap = new modelGameMap({
		// Идентификатор собственного игрока.
		PlayerID: 0,
		// Адреса типа контроллер / экшен на сервере.
		URL: {
			Base: BASE_URL,
			GameStart: '/game/gamestart',
			ColorMatrixGet: '/game/colormatrixget'
		},
		// Типы ошибок сервера.
		ErrorTypes: SERVER_ERROR,
		// Размер игрового поля.
		Size: {
			X: GameMapSettings.SizeX,
			Y: GameMapSettings.SizeY
		}
	});



	// Представление игрового поля.
	window.GameMapView = new viewGameMap({
		//
		GameMapID: 'GameBoardDiv'
	});



	// Установка начального размера игрового поля.
	ManagerController.SizeSet();

}

/**
 *	Запуск приложения. Инициализация контроллера.
 *
 */
ManagerController.Run = function() {
	console.log('ManagerController.Run');
	// Инициализация всех компонентов.
	ManagerController.Init();
	// Загрузка списка карт.
	ManagerController.MapListLoad();
}

/**
 *
 *
 */
ManagerController.MapLoad = function() {
	// Получение идентификатора выбранной в списке карты.
	var mapID = window.ManagerView.mapGet();
	// Загрузка карты из БД
	window.ManagerModel.load(
		mapID,
		function(){ManagerController.MapLoadReady()},
		function(){MessageDialog.Show(DIALOG.ErrorMapSave)}
	);
}

/**
 *
 *
 */
ManagerController.MapListLoad = function() {
	console.log('ManagerController.MapListLoad');
	//
	window.mapCollectionModel.listLoad(1, 1);
}

/**
 *
 *
 */
ManagerController.MapListReady = function() {
	console.log('ManagerController.MapListReady');
	// Обновление представления списка карт.
	window.ManagerView.mapListLoad(window.mapCollectionModel.listGet());
}

/**
 *
 *
 */
ManagerController.MapLoadReady = function() {
	// Установка размерности игрового поля.
	window.GameMapView.FieldSizeSet(window.ManagerModel.sizeX, window.ManagerModel.sizeY);
	// Загрузка матрицы карты в модель карты.
	window.GameMap.loadMatrix(window.ManagerModel.matrix);
	// Установка размера ячеек и игрового поля.
	window.GameMapView.SizeSet(window.GameMap.Size.X, window.GameMap.Size.Y);
	// Вывод карты на игровое поле.
	window.GameMapView.repaint(window.ManagerModel.matrix);
	//
	window.ManagerView.mapNameSet(window.ManagerModel.name);
	//
	window.ManagerView.mapDescriptionSet(window.ManagerModel.description);
	//
	window.ManagerView.mapCommentSet(window.ManagerModel.comment);
}

/**
 *
 *
 */
ManagerController.MapSave = function() {
	// Сбор данных карты из формы.
	var name = window.ManagerView.mapNameGet();
	var description = window.ManagerView.mapDescriptionGet();
	var comment = window.ManagerView.mapCommentGet();
	var enable = window.ManagerView.mapEnableGet();
	var matrix = window.GameMap.getMatrix();
	// Загрузка данных карты в модель карты.
	window.ManagerModel.set({
		name: name,
		matrix: matrix,
		sizeX: window.GameMap.Size.X,
		sizeY: window.GameMap.Size.Y,
		description: description,
		comment: comment,
		enable: enable
	});
	// Вставка в сообщение об успешном сохранении новой карты названия карты.
	var mapSaveMessage = $.extend({}, DIALOG.MapSave);
	mapSaveMessage.Message = mapSaveMessage.Message
		.replace('{MAP_NAME}', '«' + name + '»');
	// Сохранение новой карты в базу данных.
	window.ManagerModel.save(
		// Вывод сообщения об успешном сохранении новой карты.
		function(){MessageDialog.Show(mapSaveMessage)},
		// Вывод сообщения об ошибке.
		function(){MessageDialog.Show(DIALOG.ErrorMapSave)}
	);
}

/**
 *
 *
 */
ManagerController.MapEditCancel = function() {
	window.GameMap.resetMatrix();
	window.GameMapView.reset();
}

/**
 *
 *
 */
ManagerController.SizeSet = function() {
	var sizeType = window.ManagerView.mapSizeGet();
	var sizeX = Application.mapSize[sizeType - 1].sizeX;
	var sizeY = Application.mapSize[sizeType - 1].sizeY;

	//
	window.GameMap.setSize(sizeX, sizeY);
	window.GameMapView.FieldSizeSet(sizeX, sizeY);

	// Установка размеров представления игрового поля по умолчанию.
	window.GameMapView.SizeSet(window.GameMap.Size.X, window.GameMap.Size.Y);
}





/**
 *	Приложение начинает работать только после загрузки всех скриптов.
 *	Инициализируются все основные объекты.
 *
 */
$(window).load(function () {
	// Инициализация контроллера.
	ManagerController.Run();
});





/**
 *	Обработчики событий.
 *
 */
$(document).ready(function() {
	
	// Сохранение.
	$('#save').click(function() {
		ManagerController.MapSave();
	});



	// Отмена.
	$('#cancel').click(function() {
		ManagerController.MapEditCancel();
	});



	// Размер.
	$('#size').change(function() {
		ManagerController.SizeSet();
	});

	

	//
	$('#editMapName').change(function() {
		ManagerController.MapLoad();
	});



	// Нажатие на ячейку.
	$(document).on('click', '.cell', function(event) {
		// Получение из идентификатора индекса ячейки.
		var cellIndex = parseInt(this.id.replace(/\D+/g, ''), 10);
		// Если после изменения состояния ячейка включена:
		if (window.GameMap.toggleCell(cellIndex))
			// Включение представления ячейки.
			window.GameMapView.cellColorSet(cellIndex);
		else
			// Выключение представления ячейки.
			window.GameMapView.cellColorReset(cellIndex);
	});

});



/**
 *  Изменение позиции диалогового окна и размеров защитного фона 
 *	при изменении размеров окна.
 *
 */
$(window).resize(function() {
	// Установка позиции диалогового окна и размеров защитного фона.
	window.GameMapView.SizeSet(window.GameMap.Size.X, window.GameMap.Size.Y);
});
