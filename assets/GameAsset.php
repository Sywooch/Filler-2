<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GameAsset extends AssetBundle {
	public $sourcePath = '@app/js/game';
	public $css = [
	];
	public $js = [
		YII_DEBUG ? 'GameController.js' : 'GameController.min.js',
		YII_DEBUG ? 'GameModel.js' : 'GameModel.min.js',
		YII_DEBUG ? 'GameView.js' : 'GameView.min.js',
	];
	public $depends = [
		'yii\web\YiiAsset',
	];
}
