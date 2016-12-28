
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

	ManagerController.MapListMode();


	

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
	// Получение идентификатора выбранной в списке карты.
	// var mapID = window.ManagerView.mapGet();



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
	console.log('ManagerController.MapListLoad');
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
	// window.ManagerView.mapListLoad(window.mapCollectionModel.listGet());


	var table;

	if ($.fn.dataTable.isDataTable('#mapList')) {
		table = $('#mapList').DataTable();
		table.destroy();
	}

	// table = $('#mapList').DataTable();
	// table.destroy();

	table = $('#mapList').DataTable({
		paging: false,
		lengthChange: false,
		dom: 't',
		columnDefs: [
			{ "width": "40%", "targets": 0 }
		],
		data: window.mapCollectionModel.listGet(),
		rowId: 'id',
		columns: [
			{
				data: 'name',
				title: 'Название',
				width: '20%'
			},
			{
				data: 'sizeX',
				title: 'Размер (ширина)',
				width: '5%'
			},
			{
				data: 'sizeY',
				title: 'Размер (высота)',
				width: '10%'
			},
			{
				data: 'description',
				title: 'Описание',
				width: '35%'
			},
			{
				data: 'type',
				title: 'Тип',
				width: '10%'
			},
			{
				data: 'comment',
				title: 'Комментарий',
				width: '20%'
			}
		]
	});

	// $('#mapList').css( 'width', '100%' );
	// table.columns.adjust().draw();

	// table.ajax.url( 'newData.json' ).load();


	
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
	window.ManagerModel.reset();
	window.GameMap.resetMatrix();
	window.GameMapView.reset();
	window.ManagerView.mapReset();
	ManagerController.MapListMode();
}

/**
 *
 *
 */
ManagerController.SizeSet = function() {
	var sizeType = window.ManagerView.mapSizeGet();
	var sizeX = Application.mapSize[sizeType].sizeX;
	var sizeY = Application.mapSize[sizeType].sizeY;
console.log('ManagerController.SizeSet ' + sizeX + ' - '+ sizeY);
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

	//
	$('#create').click(function() {
		ManagerController.MapEditMode();		
		ManagerController.SizeSet();
		ManagerController.MapCreate();
	});
	
	
	
	// Сохранение.
	$('#save').click(function() {
		ManagerController.MapSave();
		ManagerController.MapEditCancel();
	});



	// Отмена.
	$('#cancel').click(function() {
		ManagerController.MapEditCancel();
	});



	// Размер.
	$('#size').change(function() {
		ManagerController.SizeSet();
	});



	$('#mapSizeFilter, #mapTypeFilter').change(function() {
		// Загрузка списка карт.
		ManagerController.MapListLoad();
	});

	

	//
	// $('#mapList').change(function() {
	// 	ManagerController.MapLoad();
	// });



	// $('#tableTest').click(function(event) {
	// 	console.log('#tableTest');
	// });



	$(document).on('click', '#mapList tbody tr', function() {
		var table = $('#mapList').DataTable();
		ManagerController.MapEditMode();
		console.log('Клик по строке таблицы ' + table.row(this).id());
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



	// Окно сообщений закрыто.
	$('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
		// e.target // activated tab
		// e.relatedTarget // previous tab
		// console.log('t4');
		ManagerController.SizeSet();
	});

	// $('.nav-tabs').click(function() {
		// console.log('t4');
		// window.GameMapView.SizeSet(window.GameMap.Size.X, window.GameMap.Size.Y);
	// });




	// var table = $('#tableTest').DataTable();

	// table.row.add([
	// 	"Константин", "Программист", "Москва", "1980", "2016/12/25", "$7,300"
	// ]).draw();
	// {
	// 	"Name":       "Константин",
	// 	"Position":   "Программист",
	// 	"Office":     "Москва",
	// 	"Extn.":       "1980",
	// 	"Start date": "2016/12/25",
	// 	"Salary":     "$7,300"
	// }).draw();


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
