
<?php

	if ($Result == TRUE) {

?>

<div class="row">
	<div class="col-xs-24 text-center">
		<div class="col-xs-24 text-left indent-top-lg indent-bottom-md text-48 color-blue">
			<?php echo(Yii::t('Dictionary', 'The password has been reset successfully!')); ?>
		</div>
		<div class="col-xs-24 text-left indent-bottom-lg color-gray text-36">
			<?php echo(Yii::t('Dictionary', 'To the following email address sent an email with further instructions to restore your access. The email has a limited lifetime. We recommend you to complete the procedure right now.')); ?>
		</div>
	</div>
</div>

<?php

	}
	else {

?>

<div class="row">
	<div class="col-xs-24 text-center">
		<div class="form text-left text-36">
			<div class="col-xs-24 text-left indent-top-lg indent-bottom-md text-48 color-blue">
				<?php echo(Yii::t('Dictionary', 'Access recovery') . ' : ' . Yii::t('Dictionary', 'Step {i} of {n}', array('{i}' => 1, '{n}' => 2))); ?>
			</div>

			<div class="col-xs-24 indent-bottom-md color-gray">
				<?php echo(Yii::t('Dictionary', 'If you have already registered but have forgotten your password, enter your e-mail address provided during registration. To your address will be sent an e-mail with a link to reset the forgotten and the new password setup.')); ?>
			</div>

			<?php

				$this -> renderPartial('formUserData', array(
					'Model' => $Model,
					'Field' => array(
						'Email' => true
					),
					'Button' => array(
						'Name' => Yii::t('Dictionary', 'Send'),
					),
				));

			?>

		</div><!-- form -->
	</div>
</div>

<?php
	
	}

?>

<div class="row">
	<div class="col-xs-24 IndexFooterMenuBlock ">

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
