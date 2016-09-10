<?php

	use yii\helpers\Url;
	use yii\helpers\Html;
	use app\components\FooterMenu\FooterMenuWidget;

?>

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

			<?=

				$this -> render('formUserData', array(
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

<?=
	// Выводится меню "Играть | Выход".
	FooterMenuWidget::widget(array(
		'ItemList' => array(
			Yii::t('Dictionary', 'Play') => Url::to('/game/game'),
			Yii::t('Dictionary', 'Logout') => Url::to('/site/logout')
		),
		'Style' => 2
	));
?>

	</div>
</div>
