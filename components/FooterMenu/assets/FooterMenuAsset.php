<?php

namespace app\components\FooterMenu\assets;

use Yii;
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
	//
	public function getTheme() {
		return Yii::$app -> mobileDetect -> isMobile() ? 'FooterMenu-mobile.css' : 'FooterMenu.css';
	}
	//
	public function init() {
		parent::init();
		$this -> css = [$this -> getTheme()];
	}
}
