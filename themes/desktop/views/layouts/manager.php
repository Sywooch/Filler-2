<?php

	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\base\Widget;

	use app\components\LanguageSwitcher\LanguageSwitcherWidget;
	use app\assets\ThemesAsset;

	$bundle = ThemesAsset::register($this);

	$this -> beginContent('@app/themes/desktop/views/layouts/main.php');
?>

<div class="row">
	<div id="Logo" class="col-xs-24 text-center" style="text-align: center;">
		<?=
			// Изображение логотипа.
			Html::img(
				$bundle -> baseUrl . '/images/LogoSmall.png'
				// ['style' => 'text-align: center;']
				// Yii::t('Dictionary', 'Filler')
			);
		?>
	</div>
</div>

<div id="content">
	<?= $content; ?>
</div><!-- content -->

<?php
	$this -> endContent();
?>
