
// Типы ошибок сервера.
var SERVER_ERROR = {
	dataError: 'DATA_ERROR',
	unknownError: '0'
}

// Настройки приложения.
var Application = {
	mapSize: [
		{
			value: 0,
			sizeX: 0,
			sizeY: 0
		},
		{
			value: 1,
			sizeX: 18,
			sizeY: 12
		},
		{
			value: 2,
			sizeX: 24,
			sizeY: 16
		},
		{
			value: 3,
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
	// Включение режима отображения списка карт.
	ManagerController.MapListMode();

	$('#notificationStartData').datepicker($.datepicker.regional["ru"]);
	$('#notificationEndData').datepicker({
		closeText: 'Закрыть',
		prevText: '&#x3c;Пред',
		nextText: 'След&#x3e;',
		currentText: 'Сегодня',
		monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
			'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
		monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
			'Июл','Авг','Сен','Окт','Ноя','Дек'],
		dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
		dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
		dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
		dateFormat: 'dd.mm.yy',
		firstDay: 1,
		isRTL: false
	});

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
ManagerController.MapLoad = function(mapID) {
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
ManagerController.MapEditMode = function() {
	window.ManagerView.MapEditMode();	
}

/**
 *
 *
 */
ManagerController.MapListMode = function() {
	window.ManagerView.MapListMode();
}

/**
 *
 *
 */
ManagerController.MapListLoad = function() {
	//
	var mapSizeFilter = window.ManagerView.mapSizeFilter();
	var mapTypeFilter = window.ManagerView.mapTypeFilter();
	//
	window.mapCollectionModel.listLoad(mapTypeFilter, Application.mapSize[mapSizeFilter]);
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
	//
	window.GameMap.setSize(window.ManagerModel.sizeX, window.ManagerModel.sizeY);
	// Загрузка матрицы карты в модель карты.
	window.GameMap.loadMatrix(window.ManagerModel.matrix);
	// Установка размерности игрового поля.
	window.GameMapView.FieldSizeSet(window.ManagerModel.sizeX, window.ManagerModel.sizeY);
	// Установка размера ячеек и игрового поля.
	window.GameMapView.SizeSet(window.GameMap.Size.X, window.GameMap.Size.Y);
	// Вывод карты на игровое поле.
	window.GameMapView.repaint(window.ManagerModel.matrix);
	// Инициализация формы данными карты.
	window.ManagerView.mapNameSet(window.ManagerModel.name);
	window.ManagerView.mapDescriptionSet(window.ManagerModel.description);
	window.ManagerView.mapCommentSet(window.ManagerModel.comment);
	window.ManagerView.mapSizeSet(
		Application.mapSize,
		window.ManagerModel.sizeX,
		window.ManagerModel.sizeY
	);
	window.ManagerView.mapTypeSet(window.ManagerModel.type);
	window.ManagerView.mapEnableSet(window.ManagerModel.enable);

	ManagerController.MapEditMode();
}

/**
 *
 *
 */
ManagerController.MapCreate = function() {
	window.ManagerModel.reset();
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
	var type = window.ManagerView.mapTypeGet();
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
		type: type,
		enable: enable
	});
	// Вставка в сообщение об успешном сохранении новой карты названия карты.
	var mapSaveMessage = $.extend({}, DIALOG.MapSave);
	mapSaveMessage.Message = mapSaveMessage.Message
		.replace('{MAP_NAME}', '«' + name + '»');
	// Сохранение новой карты в базу данных.
	window.ManagerModel.save(
		function(){
			// Обновление списка карт.
			ManagerController.MapListLoad();
			// Вывод сообщения об успешном сохранении новой карты.
			MessageDialog.Show(mapSaveMessage);
		},
		// Вывод сообщения об ошибке.
		function(){MessageDialog.Show(DIALOG.ErrorMapSave)}
	);
}

/**
 *	Отмена редактирования карты.
 *
 */
ManagerController.MapEditCancel = function() {
	window.ManagerModel.reset();
	window.GameMap.resetMatrix();
	window.GameMapView.reset();
	window.ManagerView.mapReset();
	ManagerController.MapListMode();
}

/**
 *	Установка размера игрового поля.
 *
 */
ManagerController.SizeSet = function() {
	// Получение выбранного размера 
	var sizeType = window.ManagerView.mapSizeGet();
	var sizeX = Application.mapSize[sizeType].sizeX;
	var sizeY = Application.mapSize[sizeType].sizeY;
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

	// Создание новой карты.
	$('#create').click(function() {
		ManagerController.MapEditMode();		
		ManagerController.SizeSet();
		ManagerController.MapCreate();
	});



	// Сохранение текущей карты.
	$('#save').click(function() {
		ManagerController.MapSave();
		ManagerController.MapEditCancel();
		// ManagerController.MapListLoad();
	});



	// Отмена изменений текущей карты.
	$('#cancel').click(function() {
		ManagerController.MapEditCancel();
	});



	// Изменение размера текущей карты.
	$('#size').change(function() {
		ManagerController.SizeSet();
	});


	
	// Изменение одного из фильтров.
	$('#mapSizeFilter, #mapTypeFilter').change(function() {
		// Загрузка списка карт.
		ManagerController.MapListLoad();
	});



	// Нажатие на строку таблицы со списком карт.
	$(document).on('click', '#mapList tbody tr', function() {
		var table = $('#mapList').DataTable();
		ManagerController.MapEditMode();
		ManagerController.MapLoad(table.row(this).id());
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



	// Отображение таблицы со списком карт.
	$('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
		ManagerController.SizeSet();
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
