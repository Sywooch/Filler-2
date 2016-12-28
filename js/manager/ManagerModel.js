
/**
 *  Модель карты.
 *
 */
ManagerModel = function (settings) {
	// Адреса типа контроллер / действие на сервере.
	if (typeof settings.URL !== 'undefined' && settings.URL !== null)
		this.URL = settings.URL;
	// Типы ошибок.
	if (typeof settings.errorTypes !== 'undefined' && settings.errorTypes !== null)
		this.errorTypes = settings.errorTypes;
	//
	this.ID = 0;
}
/**
 *	Сброс параметров карты.
 *
 */
ManagerModel.prototype.reset = function () {
	// 
	this.ID = null;
	// 
	this.matrix = [];
	// 
	this.name = null;
	// 
	this.sizeX = null;
	// 
	this.sizeY = null;
	// 
	this.description = null;
	// 
	this.comment = null;
	// 
	this.enable = null;
}
/**
 *  Установка параметров карты.
 *
 */
ManagerModel.prototype.set = function (map) {
	console.log('modelMap.prototype.map');
	// Если не передан объект лобби:
	if (typeof map === 'undefined' || typeof map !== 'object')
		return;

	//
	this.ID = this.ID ? this.ID : 0;
	this.name = map.name;
	this.matrix = map.matrix;
	this.sizeX = map.sizeX;
	this.sizeY = map.sizeY;
	this.description = map.description;
	this.comment = map.comment;
	this.enable = map.enable;
}
/**
 *
 *
 */
ManagerModel.prototype.save = function (callback, errorCallback) {
	//
	var self = this;
	// AJAX-запрос.
	$.post(
		this.URL.base + this.URL.mapSave,
		{
			id: this.ID,
			name: this.name,
			matrix: JSON.stringify(this.matrix),
			sizeX: this.sizeX,
			sizeY: this.sizeY,
			description: this.description,
			comment: this.comment,
			enable: this.enable
		},
		function(result) {
			if (!result.error) {
				// Если задана callback-функция:
				if (typeof callback === 'function')
					callback();
			}
			else if (result.error == self.errorTypes.dataError) {
				// Если задана errorCallback-функция:
				if (typeof errorCallback === 'function')
					errorCallback();
			}
		},
		'json'
	)
}

/**
 *
 *
 */
ManagerModel.prototype.load = function (mapID, callback, errorCallback) {
	//
	var self = this;
	// AJAX-запрос.
	$.post(
		this.URL.base + this.URL.mapLoad,
		{
			mapID: mapID
		},
		function(result) {
			if (!result.error) {
				//
				self.ID = result.ID;
				self.name = result.name;
				self.matrix = result.matrix;
				self.sizeX = result.sizeX;
				self.sizeY = result.sizeY;
				self.description = result.description;
				self.comment = result.comment;
				self.enable = result.enable;
				// Если задана callback-функция:
				if (typeof callback === 'function')
					callback();
			}
			else if (result.error == self.errorTypes.dataError) {
				// Если задана errorCallback-функция:
				if (typeof errorCallback === 'function')
					errorCallback();
			}
		},
		'json'
	)
}



/**
 *	Модель коллекции объектов.
 *
 */
modelCollection = function (settings) {
	// Адреса типа контроллер / действие на сервере.
	if (typeof settings.URL !== 'undefined' && settings.URL !== null)
		this.URL = settings.URL;
	// Callback-функция.
	if (typeof settings.callback === 'function')
		this.callback = settings.callback;
	// Типы ошибок.
	if (typeof settings.errorTypes !== 'undefined' && settings.errorTypes !== null)
		this.errorTypes = settings.errorTypes;
	// Период таймера обновления списка объектов коллекции (секунды).
	if (typeof settings.TimerPeriod === 'number')
		this.TIMER_PERIOD = parseInt(settings.timerPeriod, 10) * 1000;
	else
		this.TIMER_PERIOD = 10 * 1000;
	// Список объектов коллекции.
	this.list = [];
	// Таймер обновления коллекции.
	this.timer = 0;
}
/**
 *	Обновление коллекции. Отправляется AJAX-запрос на сервер.
 *
 */
modelCollection.prototype.listLoad = function (type, mapSize, errorCallback) {
	var self = this;
	// AJAX-запрос.
	$.post(
		this.URL.base + this.URL.listLoad,
		{
			type: type,
			sizeX: mapSize.sizeX,
			sizeY: mapSize.sizeY
		},
		function(list) {
			if (!list.error) {
				// Обновление списка объектов коллекции.
				self.list = list;
				// Если задана callback-функция:
				if (typeof self.callback === 'function')
					self.callback();
			}
			else if (result.error == self.errorTypes.dataError) {
				// Если задана errorCallback-функция:
				if (typeof errorCallback === 'function')
					errorCallback();
			}
		},
		'json'
	)
}
/**
 *	Запуск таймера обновления коллекции.
 *
 */
modelCollection.prototype.listRefreshStart = function () {
	console.log('modelCollection.prototype.ListRefreshStart');
	// Первое обновление коллекции.
	this.listLoad();
	// Если таймер еще не запущен:
	if (!this.timer)
	// Запуск таймера обновления коллекции.
		this.timer = setInterval(this.listLoad.bind(this), this.TIMER_PERIOD);
}
/**
 *	Выключение таймера обновления коллекции.
 *
 */
modelCollection.prototype.listRefreshStop = function () {
	console.log('modelCollection.prototype.ListRefreshStop');
	clearInterval(this.timer);
	this.timer = 0;
}
/**
 *	Получение списка объектов коллекции.
 *
 */
modelCollection.prototype.listGet = function () {
	console.log('modelCollection.prototype.listGet');
	return this.list;
}
/**
 *	Получение объекта коллекции по указанному идентификатору.
 *
 */
modelCollection.prototype.itemGet = function (ID) {
	console.log('modelCollection.prototype.itemGet');
	var item = false;
	// Поиск в коллекции указанного объекта.
	if (Array.isArray(this.list)) {
		$.each(this.list, function(index, value) {
			if (value.ID == ID)
				item = value;
		});
	}
	// Возвращение объекта коллекции.
	return item;
}
