<?php

namespace app\components\GameBoard\assets;

use yii\web\AssetBundle;



/**
 *
 *
 */
class GameBoardAsset extends AssetBundle {
	//
	public $sourcePath = '@app/components/GameBoard/views';
	//
	public $css = [
		YII_DEBUG ? 'GameBoard.css' : 'GameBoard.min.css'
	];
	//
	public $js = [
		YII_DEBUG ? 'modelGameBoard.js' : 'modelGameBoard.min.js',
		YII_DEBUG ? 'viewGameBoard.js' : 'viewGameBoard.min.js'
	];
	//
	public $depends = [
		'yii\web\YiiAsset',
	];
}
