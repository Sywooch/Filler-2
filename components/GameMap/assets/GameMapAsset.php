<?php

namespace app\components\GameMap\assets;

use Yii;
use yii\web\AssetBundle;



/**
 *
 *
 */
class GameMapAsset extends AssetBundle {
	//
	public $sourcePath = '@app/components/GameMap/views';
	//
	public $css = [
		YII_DEBUG ? 'GameMap.css' : 'GameMap.min.css'
	];
	//
	public $js = [
		YII_DEBUG ? 'modelGameMap.js' : 'modelGameMap.min.js',
		YII_DEBUG ? 'viewGameMap.js' : 'viewGameMap.min.js'
	];
	//
	public $depends = [
		'yii\web\YiiAsset',
	];
	//
	public function getTheme() {
		return YII_DEBUG ? 'GameMap.css' : 'GameMap.min.css';
	}
	//
	public function init() {
		parent::init();
		$this -> css = [$this -> getTheme()];
	}
}
