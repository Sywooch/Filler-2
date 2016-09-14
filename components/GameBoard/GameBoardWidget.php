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
	public $Size = array();

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
		// Если текущая тема для мобильных устройств, подключение соответствующих стилей.
		// if (Yii::app() -> theme -> getName() == 'mobile')
		// 	$fileCSS = '/GameBoard-mobile.css';
		// else
		// 	$fileCSS = '/GameBoard.css';
		// // Подключение и публикация стиля.
		// Yii::app() -> clientScript -> registerCssFile(
		// 	Yii::app() -> assetManager -> publish(
		// 		Yii::getPathOfAlias('ext.GameBoard.views') . $fileCSS
		// 	)
		// );
		// Подключение и публикация общей библиотеки.
		// Yii::app() -> clientScript -> registerScriptFile(
		// 	Yii::app() -> assetManager -> publish(
		// 		Yii::getPathOfAlias('ext.JS') . (YII_DEBUG ? '/jquery.js' : '/jquery.min.js')
		// 	)
		// );
		// Подключение и публикация скрипта виджета.
		// Yii::app() -> clientScript -> registerScriptFile(
		// 	Yii::app() -> assetManager -> publish(
		// 		Yii::getPathOfAlias('ext.GameBoard.views') . (YII_DEBUG ? '/modelGameBoard.js' : '/modelGameBoard.min.js')
		// 	)
		// );
		// Yii::app() -> clientScript -> registerScriptFile(
		// 	Yii::app() -> assetManager -> publish(
		// 		Yii::getPathOfAlias('ext.GameBoard.views') . (YII_DEBUG ? '/viewGameBoard.js' : '/viewGameBoard.min.js')
		// 	)
		// );
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
