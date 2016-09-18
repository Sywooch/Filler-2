<?php

namespace app\components\FooterMenu\assets;

use yii\web\AssetBundle;



/**
 * 
 *
 */
class FooterMenuAsset extends AssetBundle {
	// 
	public $sourcePath = '@app/components/FooterMenu/views';
	// 
	public $css = [
		'FooterMenu.css',
	];
	//
	public $js = [
	];
	//
	public $depends = [
		'yii\web\YiiAsset',
	];
}
