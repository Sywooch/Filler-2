<?php

namespace app\components\LanguageSwitcher\assets;

use yii\web\AssetBundle;



/**
 *
 *
 */
class LanguageSwitcherAsset extends AssetBundle {
	//
	public $sourcePath = '@app/components/LanguageSwitcher/views';
	//
	public $css = [
		'LanguageSwitcher.css',
		// 'LanguageSwitcher-mobile.css',
	];
	//
	public $js = [
	];
	//
	public $depends = [
		'yii\web\YiiAsset',
	];
}
