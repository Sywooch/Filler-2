<?php

namespace app\assets;

use Yii;
use yii\web\AssetBundle;



/**
 * 
 *
 */
class ManagerAsset extends AssetBundle {

	/**
	 *
	 *
	 */
	public $sourcePath = '@app/js/manager';

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
		YII_DEBUG ? 'ManagerController.js' : 'ManagerController.js',
		YII_DEBUG ? 'ManagerModel.js' : 'ManagerModel.js',
		YII_DEBUG ? 'ManagerView.js' : 'ManagerView.js',
	];

	/**
	 *
	 *
	 */
	public $depends = [
		'yii\web\YiiAsset',
	];
}
