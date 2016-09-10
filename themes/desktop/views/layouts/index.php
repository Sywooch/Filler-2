<?php
	// use Yii;
	use yii\helpers\Url;
	use yii\helpers\Html;
	// use yii\base\Widget;
	use app\components\LanguageSwitcher\LanguageSwitcherWidget;

	// use app\assets\IndexAsset;
	// use app\assets\ThemesAsset;
	// IndexAsset::register($this);
	// ThemesAsset::register($this);
	
	$this -> beginContent('@app/themes/desktop/views/layouts/main.php');
?>

<div class="row">
	<div class="col-xs-24 header">
		<?=
			// Виджет переключателя языка.
			LanguageSwitcherWidget::widget(array(
				'Languages' => Yii::$app -> params['Languages'],
				'CurrentLanguageCode' => Yii::$app -> language,
			));
		?>
		<div class="Help-Label BlueLight-Box" title="<?php echo(Yii::t('Dictionary', 'Help')); ?>"  id="Button-Help">
			<!-- <a href="#">?</a> -->
			<?= Html::a('?', Url::to('help')); ?>
		</div>
	</div>
</div>

<div id="content">
	<?php echo $content; ?>
</div><!-- content -->

<?php
	$this -> endContent();
?>
