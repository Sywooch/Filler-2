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
		<div class="Help-Label BlueLight-Box" title="<?php echo(Yii::t('Dictionary', 'Help')); ?>"  id="Button-Help">
			<?= Html::a('?', Url::to(['site/help'])); ?>
		</div>
	</div>
</div>

<div id="content">
	<?= $content; ?>
</div><!-- content -->

<?php
	$this -> endContent();
?>
