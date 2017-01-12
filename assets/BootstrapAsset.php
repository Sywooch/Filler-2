<?php

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;



/**
 * 
 *
 */
class BootstrapAsset extends AssetBundle {
	//
	public $sourcePath = '@app/components/bootstrap';
	// Подключение и публикация стиля.
	public $css = [
		YII_DEBUG ? 'css/bootstrap.css' : 'css/bootstrap.css',
	];
	// Подключение и публикация общей библиотеки.
	public $js = [
		YII_DEBUG ? 'js/bootstrap.js' : 'js/bootstrap.min.js',
	];
	//
	public $depends = [
		'yii\web\YiiAsset',
	];
	//
	public function init() {
		$this -> jsOptions['position'] = View::POS_BEGIN;
		parent::init();
	}
}
