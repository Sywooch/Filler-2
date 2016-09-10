<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\components\LanguageSwitcher\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class LanguageSwitcherAsset extends AssetBundle
{
	public $sourcePath = '@app/components/LanguageSwitcher/views';
	public $css = [
		'LanguageSwitcher.css',
		// 'LanguageSwitcher-mobile.css',
	];
	public $js = [
	];
	public $depends = [
		'yii\web\YiiAsset',
		// 'yii\bootstrap\BootstrapPluginAsset',
		// 'app\assets\BootstrapAsset'
	];
}
