<?php

	// use Yii;
	use yii\helpers\Url;
	use yii\helpers\Html;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php echo Html::encode(Yii::t('Dictionary', 'Filler')); ?></title>
	
	<?php
		// // Регистрация мета тега Description.
		// Yii::$app -> clientScript -> registerMetaTag(
		// 	Yii::t('Dictionary', 'Filler'),
		// 	'description'
		// );
		// // Регистрация мета тега Keywords.
		// Yii::$app -> clientScript -> registerMetaTag(
		// 	Yii::t('Dictionary', 'Filler'),
		// 	'keywords'
		// );
	?>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="language" content="<?php echo(Yii::$app -> language); ?>" />

	<link rel="stylesheet" type="text/css" href="<?php echo $this -> theme -> getUrl('css/game.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo $this -> theme -> getUrl('css/site.css'); ?>" />

	<link rel="icon" href="<?php echo Yii::$app -> request -> baseUrl; ?>/images/favicon.ico" type="image/png">
	<link rel="shortcut icon" href="<?php echo Yii::$app -> request -> baseUrl; ?>/images/favicon.ico" type="image/png">

</head>

<body>

	<div class="container-fluid">
		<?php echo $content; ?>
	</div>

	<div class="clear"></div>

	<div class="" id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by Konstantin Poluektov.<br/>
		All Rights Reserved.<br/>
	</div><!-- footer -->

</body>
</html>
