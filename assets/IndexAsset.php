<?php

namespace app\assets;

use yii\web\AssetBundle;



/**
 * 
 *
 */
class IndexAsset extends AssetBundle {
	//
	public $sourcePath = '@app/js/site';
	//
	public $css = [
	];
	//
	public $js = [
		YII_DEBUG ? 'index.js' : 'index.js',
		'jquery.validate.js',
		'jquery.form.js',
	];
	//
	public $depends = [
		'yii\web\YiiAsset',
	];
}
