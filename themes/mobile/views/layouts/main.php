<?php

	use yii\helpers\Url;
	use yii\helpers\Html;
	use app\assets\ThemesAsset;
	use app\assets\MobileAsset;
	use app\assets\BootstrapAsset;

	//Yii::$app -> mobileDetect -> isMobile() ? MobileAsset::register($this) : ThemesAsset::register($this);
	// ThemesAsset::setTheme();
	// ThemesAsset::register($this);

?>
<?php $this -> beginPage() ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?= Html::encode(Yii::t('Dictionary', 'Filler')); ?></title>

	<?php
		// Регистрация мета тега Description.
		$this -> registerMetaTag([
			'name' => 'description', 
			'content' => Yii::t('Dictionary', 'Filler')
		]);
		// Регистрация мета тега Keywords.
		$this -> registerMetaTag([
			'name' => 'keywords', 
			'content' => Yii::t('Dictionary', 'Filler')
		]);
	?>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="language" content="<?= Yii::$app -> language; ?>" />
	<?= Html::csrfMetaTags(); ?>

	<link rel="icon" href="<?= Yii::$app -> request -> baseUrl; ?>/images/favicon.ico" type="image/png">
	<link rel="shortcut icon" href="<?= Yii::$app -> request -> baseUrl; ?>/images/favicon.ico" type="image/png">

	<?php $this -> head() ?>
</head>



<body>
<?php $this -> beginBody() ?>

	<div class="container-fluid">
		<?= $content; ?>
	</div>

	<div class="clear"></div>

	<div class="" id="footer">
		Copyright &copy; <?= date('Y'); ?> by Konstantin Poluektov.<br/>
		All Rights Reserved.<br/>
	</div><!-- footer -->

<?php $this -> endBody() ?>
</body>
</html>
<?php $this -> endPage() ?>
