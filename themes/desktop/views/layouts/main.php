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
	<title><?php echo Html::encode(Yii::t('Dictionary', 'Filler')); ?></title>
	
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
	<meta name="language" content="<?php echo(Yii::$app -> language); ?>" />
	<?= Html::csrfMetaTags(); ?>

	<link rel="icon" href="<?php echo Yii::$app -> request -> baseUrl; ?>/images/favicon.ico" type="image/png">
	<link rel="shortcut icon" href="<?php echo Yii::$app -> request -> baseUrl; ?>/images/favicon.ico" type="image/png">

	<?php $this -> head() ?>
</head>

<body>
<?php $this -> beginBody() ?>

	<div class="container-fluid">
		<?php echo $content; ?>
	</div>

	<div class="clear"></div>

	<div class="" id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by Konstantin Poluektov.<br/>
		All Rights Reserved.<br/>
	</div><!-- footer -->

<?php $this -> endBody() ?>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
//	(function (d, w, c) {
//		(w[c] = w[c] || []).push(function() {
//			try {
//				w.yaCounter41440834 = new Ya.Metrika({
//					id:41440834,
//					clickmap:true,
//					trackLinks:true,
//					accurateTrackBounce:true
//				});
//			} catch(e) { }
//		});
//
//		var n = d.getElementsByTagName("script")[0],
//			s = d.createElement("script"),
//			f = function () { n.parentNode.insertBefore(s, n); };
//		s.type = "text/javascript";
//		s.async = true;
//		s.src = "https://mc.yandex.ru/metrika/watch.js";
//
//		if (w.opera == "[object Opera]") {
//			d.addEventListener("DOMContentLoaded", f, false);
//		} else { f(); }
//	})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/41440834" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

</body>
</html>
<?php $this -> endPage() ?>
