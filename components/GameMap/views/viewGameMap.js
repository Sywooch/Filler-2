
/**
 *	Представление игрового поля.
 *	Зависит от файлов GameBoard.php, GameBoard.css / GameBoard-mobile.css, 
 *
 */
viewGameMap = function (Settings) {
	// Если задан идентификатор DOM-элемента игрового поля:
	if (typeof Settings.GameMapID !== 'undefined' && Settings.GameMapID !== null)
		this.GameMapID = Settings.GameMapID;
	// Префиксы идентификаторов основных DOM-элементов.
	this.Prefix = {
		FieldContainer: 'FieldContainer',
		GamePad: 'GamePad',
		Cell: 'c-',
		Button: 'Color'
	};
	this.color = {
		selectedCell: 'e70',
		unselectedCell: '303030',
	}
	// Если не задан нестандартный список игровых цветов:
	if (!this.ColorsListSet(Settings.Colors)) {
		// Стандартный список игровых цветов:
		this.ColorsListSet(Array(
			'FF0000', '00FF00', '3355FF', 'FFFF00',
			'FF00FF', 'FFFFFF', '990000', '3E5900',
			'333399', 'EE7700', '888890', '9900DD',
			'FFBB88', '00FFBB', '4A4A4F', '7C4600'
		));
	}
	// Размеры игрового поля.
	if (typeof Settings.Size !== 'undefined' && Settings.Size !== null)
		this.Size = Settings.Size;
	else
		this.Size = {
			X: null,
			Y: null
		};
	this.CellSize;
	this.ColorMatrix;
}
/**
 *	Установка размера игрового поля. Измеряется в ячейках X * Y.
 *	По указанным размерам формируется игровое поле из ячеек.
 *
 */
viewGameMap.prototype.FieldSizeSet = function (SizeX, SizeY) {
	console.log('viewGameMap.prototype.FieldSizeSet : ' + SizeX + ' x ' + SizeY);
	// Массив ячеек (<div>-блоков).
	var CellsBlock = '';
	// Если заданы размеры игрового поля:
	if ((typeof SizeX !== 'undefined' && SizeX !== null) && (typeof SizeY !== 'undefined' && SizeY !== null)) {
		this.Size.X = parseInt(SizeX, 10);
		this.Size.Y = parseInt(SizeY, 10);
		// Формирование игрового поля из заданного количества ячеек.
		for (var Index = 1; Index <= (SizeX * SizeY); Index++)
			// Добавление ячейки с указанным идентификатором.
			CellsBlock += '<div class="cell " id="' + this.Prefix.Cell + Index + '"></div>';
	}
	// Отображение массива ячеек.
	$('#' + this.Prefix.FieldContainer).html(CellsBlock);
}
/**
 *	Установка списка игровых цветов.
 *
 */
viewGameMap.prototype.ColorsListSet = function (ColorsList) {
	console.log('viewGameMap.prototype.ColorsListSet');
	// Если список игровых цветов имеет правильный формат:
	if (typeof ColorsList !== 'undefined' && Array.isArray(ColorsList) && ColorsList.length >= 6) {
		this.Colors = ColorsList;
		return true;
	}
	else
		return false;
}
/**
 *	Получение списка игровых цветов.
 *
 */
viewGameMap.prototype.ColorsListGet = function () {
	console.log('viewGameMap.prototype.ColorsListGet');
	return this.Colors;
}
/**
 *	Перекрашивание всего игрового поля. Обновление кнопок-образцов цветов.
 *
 */
viewGameMap.prototype.repaint = function (matrix) {
	var self = this;
	if (Array.isArray(matrix)) {
		$.each(matrix, function(cellIndex, value) {
			if (value)
				self.cellColorSet(cellIndex + 1);
			else
				self.cellColorReset(cellIndex + 1);
		});
	}
}

/**
 *
 *
 */
viewGameMap.prototype.reset = function () {
	var self = this;
	$('.cell').each(function () {
		self.cellColorReset(this);
	});
}

/**
 *	Установка цвета указанной ячейки.
 *
 */
viewGameMap.prototype.cellColorSet = function (cellIndex) {
	// $('#' + this.Prefix.Cell + CellIndex).css({'background-color': '#' + Color});
	// $('#' + this.Prefix.Cell + cellIndex).css({'background-color': '#' + this.color.selectedCell});
	$('#' + this.Prefix.Cell + cellIndex).addClass('cell-on');
}
/**
 *	Установка цвета указанной ячейки.
 *
 */
viewGameMap.prototype.cellColorReset = function (cell) {
	// $('#' + this.Prefix.Cell + CellIndex).css({'background-color': '#' + Color});
	if (typeof cell !== 'object')
		cell = '#' + this.Prefix.Cell + cell;
	// $(cell).css({'background-color': '#' + this.color.unselectedCell});
	$(cell).removeClass('cell-on');
}
/**
 *	Получение цвета указанной ячейки.
 *
 */
viewGameMap.prototype.CellColorGet = function (CellIndex) {
	return $('#' + this.Prefix.Cell + CellIndex).css('background-color');
}
/**
 *	Установка формы указанной ячейки.
 *
 */
viewGameMap.prototype.CellShapeSet = function (CellIndex) {
	$('#' + this.Prefix.Cell + CellIndex).css({'border-radius': '0px'});
}
/**
 *	Установка формы указанной стартовой (домашней) ячейки.
 *
 */
viewGameMap.prototype.HomeCellShapeSet = function (HomeCellIndex) {
	// Если домашняя ячейка в верхнем левом углу:
	if (HomeCellIndex == 1)
		$('#' + this.Prefix.Cell + HomeCellIndex).css({'border-top-left-radius': '100%'});
	// Если домашняя ячейка в верхнем правом углу:
	else if (HomeCellIndex == this.Size.X)
		$('#' + this.Prefix.Cell + HomeCellIndex).css({'border-top-right-radius': '100%'});
	// Если домашняя ячейка в нижнем левом углу:
	else if (HomeCellIndex == (this.Size.X * this.Size.Y) - this.Size.X + 1)
		$('#' + this.Prefix.Cell + HomeCellIndex).css({'border-bottom-left-radius': '100%'});
	// Если домашняя ячейка в нижнем правом углу:
	else if (HomeCellIndex == this.Size.X * this.Size.Y)
		$('#' + this.Prefix.Cell + HomeCellIndex).css({'border-bottom-right-radius': '100%'});
}
/**
 *	Сброс формы указанной ячейки.
 *
 */
viewGameMap.prototype.CellShapeReset = function (CellIndex) {
	$('#' + this.Prefix.Cell + CellIndex).css({'border-radius': '4px'});
}
/**
 *	Установка панели кнопок-образцов цвета.
 *
 */
viewGameMap.prototype.GamePad = function (ColorsNumber, Handler) {
	console.log('GameBoard.GamePad(' + ColorsNumber + ')');
	// Массив кнопок (<div>-блоков).
	var GamePad = '';
	$('#' + this.Prefix.GamePad).html('');
	// Создание на игровой панели кнопок-образцов используемых цветов.
	for (Index = 1; Index <= ColorsNumber; Index++) {
		GamePad += '<div class="GameButton" id="' + this.Prefix.Button + Index + 
		'" onClick="' + Handler + '(' + Index + ')">' + 
		'<div class="GameButton-Progress"></div></div>';
	}
	$('#' + this.Prefix.GamePad).html(GamePad);
	// Установка для всех кнопок игровых цветов.
	for (Index = 1; Index <= ColorsNumber; Index++)
		$('#' + this.Prefix.Button + Index).css({'background-color': '#' + this.Colors[Index - 1]});
}
/**
 *	Обновление состояния игровой панели.
 *	Отключение кнопок с занятыми цветами.
 *
 */
viewGameMap.prototype.GamePadRefresh = function (DisabledColors) {
	console.log('viewGameMap.prototype.GamePadRefresh');
	var self = this;
	// Если задан список блокированных индексов цветов:
	if (typeof DisabledColors !== 'undefined' && Array.isArray(DisabledColors)) {
		$('.GameButton-Disabled').removeClass('GameButton-Disabled');
		$.each(DisabledColors, function(Key, Value) {
			$('#' + self.Prefix.Button + Value).addClass('GameButton-Disabled');
		});
	}
}
/**
 *	Обновление цифровых значений ярлыков прогресса кнопок игровой панели.
 *
 */
viewGameMap.prototype.GamePadProgressLablesRefresh = function (ProgressByColorsList) {
	var self = this;
	$.each(ProgressByColorsList, function(Key, Value) {
		$('#' + self.Prefix.Button + (Key + 1) + ' > div').html('+' + Value);
	});
}
/**
 *	Получение кода цвета указанной кнопки-образца.
 *
 */
viewGameMap.prototype.ButtonColorGet = function (ColorIndex) {
	return $('#' + this.Prefix.Button + ColorIndex).css('background-color');
}
/**
 *	Установка размера игрового поля и ячеек.
 *
 */
viewGameMap.prototype.SizeSet = function (SizeX, SizeY) {
	console.log('viewGameMap.prototype.SizeSet(' + SizeX + ' x ' + SizeY + ')');
	// Вычисление размера ячейки.
	var CellSize = Math.floor(($('#' + this.GameMapID).width() - 20) / SizeX);
	this.CellSize = CellSize - 2;
	// Вычисление размера контейнера игрового поля.
	var GameBoardWidth = (CellSize * SizeX) + 20;
	var GameBoardHeight = (CellSize * SizeY) + 20;
	// Установка размера контейнера игрового поля.
	$('#' + this.Prefix.FieldContainer).css({
		'width': GameBoardWidth + 'px',
		'height': GameBoardHeight + 'px'
	});
	// Установка размера всех ячеек.
	$('.cell').css({
		'width': CellSize - 2 + 'px',
		'height': CellSize - 2 + 'px'
	});
}
