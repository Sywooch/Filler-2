<?php
	$this -> beginContent('//layouts/main');
?>

<div class="row">
	<div class="col-xs-24 header">
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
