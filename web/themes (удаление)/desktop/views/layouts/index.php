<?php
	// use Yii;
	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\base\Widget;
	// use app\components\FooterMenu\FooterMenuWidget;
	$this -> beginContent('@app/themes/desktop/views/layouts/main.php');
	// '@webroot/themes/desktop/views/layouts/main.php'
?>

<div class="row">
	<div class="col-xs-24 header">
		<?php
			// Виджет переключателя языка.
			// FooterMenuWidget::widget(array(
			// 	'Languages' => Yii::$app -> params['Languages'],
			// 	'CurrentLanguageCode' => Yii::$app -> language,
			// ));
		?>
		<div class="Help-Label BlueLight-Box" title="<?php echo(Yii::t('Dictionary', 'Help')); ?>"  id="Button-Help">
			<!-- <a href="#">?</a> -->
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
