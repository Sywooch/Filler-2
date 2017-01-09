
/**
 *	Представление игрового поля.
 *	Зависит от файлов GameBoard.php, GameBoard.css / GameBoard-mobile.css, 
 *
 */
viewGameBoard = function (Settings) {
	// Если задан идентификатор DOM-элемента игрового поля:
	if (typeof Settings.GameBoardID !== 'undefined' && Settings.GameBoardID !== null)
		this.GameBoardID = Settings.GameBoardID;
	// Префиксы идентификаторов основных DOM-элементов.
	this.Prefix = {
		FieldContainer: 'FieldContainer',
		GamePad: 'GamePad',
		Cell: 'c-',
		Button: 'Color'
	};
	// Если не задан нестандартный список игровых цветов:
	if (!this.ColorsListSet(Settings.Colors)) {
		// Стандартный список игровых цветов:
		this.ColorsListSet([
			'292929', // Нейтральный (не игровой) цвет #292929
			'FF0000', '00FF00', '3355FF', 'FFFF00',
			'FF00FF', 'FFFFFF', '990000', '3E5900',
			'333399', 'EE7700', '888890', '9900DD',
			'FFBB88', '00FFBB', '4A4A4F', '7C4600'
		]);
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
viewGameBoard.prototype.FieldSizeSet = function (SizeX, SizeY) {
	console.log('viewGameBoard.prototype.FieldSizeSet : ' + SizeX + ' x ' + SizeY);
	// Массив ячеек (<div>-блоков).
	var CellsBlock = '';
	// Если заданы размеры игрового поля:
	if ((typeof SizeX !== 'undefined' && SizeX !== null) && (typeof SizeY !== 'undefined' && SizeY !== null)) {
		this.Size.X = parseInt(SizeX, 10);
		this.Size.Y = parseInt(SizeY, 10);
		// Формирование игрового поля из заданного количества ячеек.
		for (var Index = 1; Index <= (SizeX * SizeY); Index++)
			// Добавление ячейки с указанным идентификатором.
			CellsBlock += '<div class="GameTile " id="' + this.Prefix.Cell + Index + '"></div>';
	}
	// Отображение массива ячеек.
	$('#' + this.Prefix.FieldContainer).html(CellsBlock);
}
/**
 *	Установка списка игровых цветов.
 *
 */
viewGameBoard.prototype.ColorsListSet = function (ColorsList) {
	console.log('viewGameBoard.prototype.ColorsListSet');
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
viewGameBoard.prototype.ColorsListGet = function () {
	console.log('viewGameBoard.prototype.ColorsListGet');
	return this.Colors;
}
/**
 *	Установка стилей преграды для игрового поля.
 *
 */
viewGameBoard.prototype.MapSet = function (ColorMatrix) {
	var self = this;
	if (Array.isArray(ColorMatrix)) {
		$.each(ColorMatrix, function(CellIndex, ColorIndex) {
			//
			if (!self.isColorCell(ColorIndex))
				self.CellWallStyleSet(CellIndex + 1);
		});
	}
}
/**
 *	Перекрашивание всего игрового поля. Обновление кнопок-образцов цветов.
 *
 */
viewGameBoard.prototype.Repaint = function (ColorMatrix, PlayerMatrix, DisabledColors, ProgressByColorsList, HomeCellIndex) {
	console.log('viewGameBoard.prototype.Repaint');
	var self = this;
	// Перекрашивание всего игрового поля.
	if (Array.isArray(ColorMatrix)) {
		$.each(ColorMatrix, function(Key, Value) {
			//
			if (self.isColorCell(Value)) {
				// if (Value)
					// Установка цвета текущей ячейки.
					self.CellColorSet(Key + 1, self.Colors[Value]);
				// else
				// 	self.CellColorSet(Key + 1, '292929');
			}
		});
	}
	// Обозначение территорий, занятых игроками.
	if (Array.isArray(PlayerMatrix)) {
		$.each(PlayerMatrix, function(Key, Value) {
			// Если текущая ячейка занята:
			if (Value)
				self.CellShapeSet(Key + 1);
			// Если текущая ячейка свободна:
			else
				self.CellShapeReset(Key + 1);
		});
	}
	// Если домашняя ячейка указана:
	if (typeof HomeCellIndex === 'number')
		// Форматирование домашней ячейки.
		this.HomeCellShapeSet(HomeCellIndex);
	// Отключение кнопок с занятыми цветами.
	this.GamePadRefresh(DisabledColors);
	// Обновление ярлыков прогресса на кнопках-образцах цвета.
	this.GamePadProgressLablesRefresh(ProgressByColorsList);
	// Выключение режима подсветки всех ячеек.
	this.CellHighlightHide();
}
/**
 *	Проверка типа ячейки.
 *
 */
viewGameBoard.prototype.isColorCell = function (ColorIndex) {
	// Если индекс цвета не найден в списке цветов:
	// this.Colors.indexOf(ColorIndex) == -1
	// Если индекс цвета входит в диапазон:
	if (ColorIndex > this.Colors.length)
		return false;
	return true;
	// return ColorIndex < this.Colors.length;
}
/**
 *	Установка стиля преграды для указанной ячейки.
 *
 */
viewGameBoard.prototype.CellWallStyleSet = function (CellIndex) {
	$('#' + this.Prefix.Cell + CellIndex).addClass('GameTile-Wall');
}
/**
 *	Установка цвета указанной ячейки.
 *
 */
viewGameBoard.prototype.CellColorSet = function (CellIndex, Color) {
	$('#' + this.Prefix.Cell + CellIndex).css({'background-color': '#' + Color});
}
/**
 *	Получение цвета указанной ячейки.
 *
 */
viewGameBoard.prototype.CellColorGet = function (CellIndex) {
	return $('#' + this.Prefix.Cell + CellIndex).css('background-color');
}
/**
 *	Установка формы указанной ячейки.
 *
 */
viewGameBoard.prototype.CellShapeSet = function (CellIndex) {
	$('#' + this.Prefix.Cell + CellIndex).css({'border-radius': '0px'});
}
/**
 *	Установка формы указанной стартовой (домашней) ячейки.
 *
 */
viewGameBoard.prototype.HomeCellShapeSet = function (HomeCellIndex) {
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
viewGameBoard.prototype.CellShapeReset = function (CellIndex) {
	$('#' + this.Prefix.Cell + CellIndex).css({'border-radius': '4px'});
}
/**
 *	Установка панели кнопок-образцов цвета.
 *
 */
viewGameBoard.prototype.GamePad = function (ColorsNumber, Handler) {
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
		$('#' + this.Prefix.Button + Index).css({'background-color': '#' + this.Colors[Index]});
}
/**
 *	Включение режима подсветки указанных ячеек.
 *
 */
viewGameBoard.prototype.CellHighlightShow = function (CellsList) {
	console.log('viewGameBoard.prototype.CellHighlightShow');
	var self = this;
	// Если задан список ячеек:
	if (typeof CellsList !== 'undefined' && Array.isArray(CellsList)) {
		$.each(CellsList, function(Key, Value) {
			// Включение режима подсветки указанной ячейки.
			$('#' + self.Prefix.Cell + Value).addClass('GameTile-Mask');
		});
	}
}
/**
 *	Выключение режима подсветки всех ячеек.
 *
 */
viewGameBoard.prototype.CellHighlightHide = function () {
	$('.GameTile').removeClass('GameTile-Mask');
}
/**
 *	Обновление состояния игровой панели.
 *	Отключение кнопок с занятыми цветами.
 *
 */
viewGameBoard.prototype.GamePadRefresh = function (DisabledColors) {
	console.log('viewGameBoard.prototype.GamePadRefresh');
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
viewGameBoard.prototype.GamePadProgressLablesRefresh = function (ProgressByColorsList) {
	var self = this;
	$.each(ProgressByColorsList, function(Key, Value) {
		$('#' + self.Prefix.Button + (Key + 1) + ' > div').html('+' + Value);
	});
}
/**
 *	Получение кода цвета указанной кнопки-образца.
 *
 */
viewGameBoard.prototype.ButtonColorGet = function (ColorIndex) {
	return $('#' + this.Prefix.Button + ColorIndex).css('background-color');
}
/**
 *	Установка размера игрового поля и ячеек.
 *
 */
viewGameBoard.prototype.SizeSet = function (SizeX, SizeY) {
	console.log('viewGameBoard.prototype.SizeSet(' + SizeX + ' x ' + SizeY + ')');
	// Вычисление размера ячейки.
	var CellSize = Math.floor(($('#' + this.GameBoardID).width() - 20) / SizeX);
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
	$('.GameTile').css({
		'width': CellSize - 2 + 'px',
		'height': CellSize - 2 + 'px'
	});
}
