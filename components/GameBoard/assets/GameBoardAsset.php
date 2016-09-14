<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\components\GameBoard\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GameBoardAsset extends AssetBundle {
	public $sourcePath = '@app/components/GameBoard/views';
	public $css = [
		YII_DEBUG ? 'GameBoard.css' : 'GameBoard.min.css'
	];
	public $js = [
		YII_DEBUG ? 'modelGameBoard.js' : 'modelGameBoard.min.js',
		YII_DEBUG ? 'viewGameBoard.js' : 'viewGameBoard.min.js'
	];
	public $depends = [
		'yii\web\YiiAsset',
	];
}
