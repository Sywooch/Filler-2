<?php

namespace app\components\GameBoard;

use Yii;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\base\Widget;



/**
 *	Виджет игрового поля размером SizeX x SizeY.
 *	Выводит представление виджета.
 *
 */
class GameBoardWidget extends Widget {

	/**
	 *	Размер игрового поля (X, Y).
	 *
	 */
	public $Size = [];

	/**
	 *	Количество цветов.
	 *
	 */
	public $ColorsNumber;

	/**
	 *	Список цветов.
	 *
	 */
	public $ColorsList;



	/**
	 *	Инициализация виджета.
	 *	Подключение библиотек, скриптов и стилей.
	 *
	 */
	public function init() {
		// Передача скрипту размера игрового поля, количества цветов и списка цветов.
		Yii::$app -> view -> registerJs(
			'var GameBoardSettings = ' . json_encode([
				'SizeX' => $this -> Size[0],
				'SizeY' => $this -> Size[1],
				'ColorsNumber' => $this -> ColorsNumber,
				'ColorsList' => $this -> ColorsList
			]) . ';',
			yii\web\View::POS_HEAD
		);
	}



	/**
	 *	Запуск виджета.
	 *	Передает представлению настройки виджета. 
	 *
	 */
	public function run() {
		return $this -> render('GameBoard', [
			'SizeX' => $this -> Size[0],
			'SizeY' => $this -> Size[1],
			'ColorsNumber' => $this -> ColorsNumber,
			'ColorsList' => $this -> ColorsList
		]);
	}

}
