<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\components\FooterMenu\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class FooterMenuAsset extends AssetBundle
{
	// public $basePath = '@webroot';
	public $sourcePath = '@app/components/FooterMenu/views';
	// public $baseUrl = '@web';
	public $css = [
		'FooterMenu.css',
	];
	public $js = [
	];
	public $depends = [
		'yii\web\YiiAsset',
		// 'yii\bootstrap\BootstrapAsset',
		// 'app\assets\BootstrapAsset'
	];
}
