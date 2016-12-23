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
		YII_DEBUG ? 'GameController.js' : 'GameController.js',
		YII_DEBUG ? 'GameModel.js' : 'GameModel.js',
		YII_DEBUG ? 'GameView.js' : 'GameView.js',
	];

	/**
	 *
	 *
	 */
	public $depends = [
		'yii\web\YiiAsset',
	];
}
