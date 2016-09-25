<?php

namespace app\assets;

use yii\web\AssetBundle;



/**
 * 
 *
 */
class ThemesAsset extends AssetBundle {
	//
	public $sourcePath = '@app/themes/desktop';
	//
	public $css = [
		'css/site.css',
		'css/game.css',
	];
	//
	public $js = [

	];
	//
	public $depends = [
		'yii\web\YiiAsset',
		'app\assets\BootstrapAsset'
	];
}
