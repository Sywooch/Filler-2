<?php

namespace app\assets;

use yii\web\AssetBundle;



/**
 * 
 *
 */
class BootstrapAsset extends AssetBundle {
	//
	public $sourcePath = '@app/components/bootstrap';
	//
	public $css = [
		'css/bootstrap.css',
	];
	//
	public $js = [
		'js/bootstrap.js',
	];
	//
	public $depends = [
		'yii\web\YiiAsset',
	];
}
