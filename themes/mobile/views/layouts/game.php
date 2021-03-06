<?php
	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\base\Widget;
	use app\components\LanguageSwitcher\LanguageSwitcherWidget;
	use app\assets\ThemesAsset;

	$bundle = ThemesAsset::register($this);

	$this -> beginContent('@app/themes/mobile/views/layouts/main.php');
?>

<div class="row">
	<div class="col-xs-24 delimiter-bottom" id="header">
		<div class="col-xs-12 text-left" id="Logo">
			<?=
				// Изображение логотипа.
				Html::img(
					$bundle -> baseUrl . '/images/LogoSmall-mobile.png'
					// Yii::t('Dictionary', 'Filler')
				);
			?>
		</div>
		<div class="col-xs-12 header">
			<?=
				// Виджет переключателя языка.
				LanguageSwitcherWidget::widget(array(
					'Languages' => Yii::$app -> params['Languages'],
					'CurrentLanguageCode' => Yii::$app -> language,
				));
			?>
			<div class="Help-Label BlueLight-Box" title="<?= Yii::t('Dictionary', 'Game rules'); ?>"  id="Button-Help">
				<a href="#">?</a>
			</div>
			<div class="Sound-Label Sound-Label-On BlueLight-Box" title="<?= Yii::t('Dictionary', 'Sound switcher'); ?>" id="Button-Sound">
			</div>
		</div>
	</div><!-- header -->
	<div class="col-xs-24 text-center TopMenu delimiter-bottom" id="TopMenu-xs">
		<div class="col-xs-8 text-center text-36 color-gray"><?= Yii::t('Dictionary', 'Games'); ?></div>
		<div class="col-xs-8 text-center text-36 color-gray"><?= Yii::t('Dictionary', 'Winnings'); ?></div>
		<div class="col-xs-8 text-center text-36 color-gray"><?= Yii::t('Dictionary', 'Rating'); ?></div>
		<div class="col-xs-8 text-center color-white text-48 text-bold title-sm" id="PlayerGames">0</div>
		<div class="col-xs-8 text-center color-white text-48 text-bold title-sm" id="PlayerWinnings">0</div>
		<div class="col-xs-8 text-center color-white text-48 text-bold title-sm" id="PlayerRating">0 %</div>
	</div>
</div>
<div id="content">
	<?= $content; ?>
</div><!-- content -->

<?php
	$this -> endContent();
?>
