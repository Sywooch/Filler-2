<?php
	// use Yii;
	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\base\Widget;
	use app\components\LanguageSwitcher\LanguageSwitcherWidget;
	// use app\components\FooterMenu\FooterMenuWidget;
	$this -> beginContent('@app/themes/desktop/views/layouts/main.php');
?>

<div class="row">
	<div class="col-xs-12 text-left" id="Logo">
		<?php
			// Изображение логотипа.
			// echo Html::img(
			// 	Yii::$app -> theme -> baseUrl . '/images/LogoSmall.png',
			// 	Yii::t('Dictionary', 'Filler')
			// );
		?>
	</div>
	<div class="col-xs-12 header">
		<?php
			// Виджет переключателя языка.
			// $this -> widget('ext.LanguageSwitcher.LanguageSwitcherWidget', array(
			// 	'Languages' => Yii::app() -> params['Languages'],
			// 	'CurrentLanguageCode' => Yii::app() -> getLanguage(),
			// ));
		?>
		<div class="Help-Label BlueLight-Box" title="<?php echo(Yii::t('Dictionary', 'Help')); ?>"  id="Button-Help">
			<?php echo Html::a('?', Url::to('/site/help')); ?>
		</div>
	</div>
</div>

<div id="content">
	<?php echo $content; ?>
</div><!-- content -->

<?php
	$this -> endContent();
?>
