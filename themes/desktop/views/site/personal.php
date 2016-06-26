
<div class="row">
	<div class="col-lg-8 col-md-12 col-sm-16 col-xs-20 col-lg-offset-8 col-md-offset-6 col-sm-offset-4 col-xs-offset-2 text-center">
		<div class="form text-left text-14">
			<div class="col-xs-24 text-left indent-top-md indent-bottom-md text-18 color-blue">
				<?php 
					if ($Result)
						echo(Yii::t('Dictionary', 'Personal data saved successfully!'));
					else
						echo(Yii::t('Dictionary', 'Personal data'));
				?>
			</div>

			<div class="col-xs-24 indent-bottom-md color-gray">
				<?php echo(Yii::t('Dictionary', 'You can change the personal data. If you don\'t want to edit the current password, just leave the corresponding fields empty.')); ?>
			</div>

			<?php

				$this -> renderPartial('formUserData', array(
					'Model' => $Model,
					'Field' => array(
						'Name' => true,
						'Email' => true,
						'Password' => true,
						'ControlPassword' => true,
					),
					'Button' => array(
						'Name' => Yii::t('Dictionary', 'Save'),
					),
				));

			?>

		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-24 IndexFooterMenuBlock ">

<?php

	// Выводится меню "Играть | Выход".
	$this -> widget('ext.FooterMenu.FooterMenuWidget', array(
		'ItemList' => array(
			Yii::t('Dictionary', 'Play') => $this -> createUrl("/game/game"),
			Yii::t('Dictionary', 'Logout') => $this -> createUrl("/site/logout")
		),
		'Style' => 2
	));

?>

	</div>
</div>
