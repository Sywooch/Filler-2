<?php
	$this -> beginContent('//layouts/main');
?>

<div class="row">
	<div class="col-xs-12 text-left" id="Logo">
		<?php
			// Изображение логотипа.
			echo CHtml::image(
				Yii::app() -> theme -> baseUrl . '/images/LogoSmall-mobile.png',
				Yii::t('Dictionary', 'Filler')
			);
		?>
	</div>
	<div class="col-xs-12 header">
		<?php
			// Виджет переключателя языка.
			$this -> widget('ext.LanguageSwitcher.LanguageSwitcherWidget', array(
				'Languages' => Yii::app() -> params['Languages'],
				'CurrentLanguageCode' => Yii::app() -> getLanguage(),
			));
		?>
		<div class="Help-Label BlueLight-Box" title="<?php echo(Yii::t('Dictionary', 'Help')); ?>"  id="Button-Help">
			<!-- <a href="#">?</a> -->
			<?php echo CHtml::link('?', $this -> createUrl("/site/help")); ?>
		</div>
	</div>
</div>

<div id="content">
	<?php echo $content; ?>
</div><!-- content -->

<?php
	$this -> endContent();
?>
