
<div class="row">
	<div class="col-xs-24 text-center">
		<div class="form text-left text-36">
			<div class="col-xs-24 text-left indent-top-md indent-bottom-md text-48 color-blue">
				<?php echo(Yii::t('Dictionary', 'Player registration')); ?>
			</div>
			<div class="col-xs-24 indent-bottom-md color-gray">
				<?php echo(Yii::t('Dictionary', 'To register, please complete all form fields. E-mail and password will later be used for authorization. All informational messages will be sent to the specified email address.')); ?>
			</div>			
			<?php
				$this -> renderPartial('formUserData', array(
					'Model' => $Model,
					'Field' => array(
						'Name' => true,
						'Email' => true,
						'Password' => true,
						'ControlPassword' => true,
						'ControlCode' => true,
					),
					'Button' => array(
						'Name' => Yii::t('Dictionary', 'Registration'),
					),
				));
			?>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-24 IndexFooterMenuBlock ">
<?php
	// Выводится меню "В начало".
	$this -> widget('ext.FooterMenu.FooterMenuWidget', array(
		'ItemList' => array(
			Yii::t('Dictionary', 'Start') => $this -> createUrl("/site/index")
		)
	));
?>
	</div>
</div>
