
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
	modelGameMap = function (Settings) {
		// Инициализация игрового поля.
		this.Init(Settings);
	}
	/**
	 *	Инициализация.
	 *
	 */
	modelGameMap.prototype.Init = function (Settings) {
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

		// // Матрица.
		// this.matrix = [];
        //
		// var Index = this.Size.X * this.Size.Y;
		// while (Index--)
		// 	this.matrix.push(0);
		
		this.setSize(this.Size.X, this.Size.Y);

		return true;
	}

	modelGameMap.prototype.setSize = function (sizeX, sizeY) {
		//
		this.Size.X = sizeX;
		this.Size.Y = sizeY;
		// Матрица.
		this.matrix = [];
		//
		var index = this.Size.X * this.Size.Y;
		//
		while (index--)
			this.matrix.push(0);
		//
		return this.matrix.length;
	}

	// modelGameMap.prototype.mapView = function () {
	// 	console.log('modelGameMap.prototype.mapView');
	// }

	modelGameMap.prototype.getCell = function (cellIndex) {
		// console.log('modelGameMap.prototype.mapView');
		return this.matrix[cellIndex - 1];
	}

	modelGameMap.prototype.setCell = function (cellIndex) {
		// console.log('modelGameMap.prototype.mapView');
		this.matrix[cellIndex - 1] = 1;
	}

	modelGameMap.prototype.resetCell = function (cellIndex) {
		// console.log('modelGameMap.prototype.mapView');
		this.matrix[cellIndex - 1] = 0;
	}

	modelGameMap.prototype.toggleCell = function (cellIndex) {
		// console.log('modelGameMap.prototype.mapView');
		if (this.getCell(cellIndex))
			this.resetCell(cellIndex);
		else
			this.setCell(cellIndex);
		return this.getCell(cellIndex);
	}

	/**
	 *	Загрузка параметров и состояния текущей игры.
	 *
	 */
	modelGameMap.prototype.resetMatrix = function () {
		var self = this;
		if (Array.isArray(this.matrix)) {
			$.each(this.matrix, function(index, value) {
				// Установка цвета текущей ячейки.
				self.resetCell(index);
			});
		}
	}

	/**
	 *	Загрузка параметров и состояния текущей игры.
	 *
	 */
	modelGameMap.prototype.loadMatrix = function (matrix) {
		var self = this;
		if (Array.isArray(matrix)) {
			$.each(matrix, function(cellIndex, value) {
				if (value)
					self.setCell(cellIndex + 1);
				else
					self.resetCell(cellIndex + 1);
			});
		}
	}
	/**
	 *	Загрузка текущего состояния игрового поля из списка ходов.
	 *
	 */
	modelGameMap.prototype.getMatrix = function () {
		return this.matrix;
	}
	/**
	 *	Генерирование случайного целого числа в заданном диапазоне.
	 *
	 */
	modelGameMap.prototype.RandomInteger = function (Min, Max) {
		return Math.floor(Math.random() * (Max - Min + 1)) + Min;
	}
});
