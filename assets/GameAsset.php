<?php

namespace app\assets;

use Yii;
use yii\web\AssetBundle;



/**
 * 
 *
 */
class GameAsset extends AssetBundle {

	/**
	 *
	 *
	 */
	public $sourcePath = '@app/js/game';

	/**
	 *
	 *
	 */
	public $css = [];

	/**
	 *	Подключение и публикация скриптов контроллера, модели и представления.
	 *
	 */
	public $js = [
		YII_DEBUG ? 'GameController.js' : 'GameController.min.js',
		YII_DEBUG ? 'GameModel.js' : 'GameModel.min.js',
		YII_DEBUG ? 'GameView.js' : 'GameView.min.js',
	];

	/**
	 *
	 *
	 */
	public $depends = [
		'yii\web\YiiAsset',
	];
}
