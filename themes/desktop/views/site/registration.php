<?php

	use yii\helpers\Url;
	use yii\helpers\Html;
	use app\components\FooterMenu\FooterMenuWidget;

?>

<div class="row">
	<div class="col-lg-8 col-md-12 col-sm-16 col-xs-20 col-lg-offset-8 col-md-offset-6 col-sm-offset-4 col-xs-offset-2 text-center">
		<div class="form text-left text-14">
			<div class="col-xs-24 text-left indent-top-md indent-bottom-md text-18 color-blue">
				<?php echo(Yii::t('Dictionary', 'Player registration')); ?>
			</div>
			<div class="col-xs-24 indent-bottom-md color-gray">
				<?php echo(Yii::t('Dictionary', 'To register, please complete all form fields. E-mail and password will later be used for authorization. All informational messages will be sent to the specified email address.')); ?>
			</div>			
			<?=
				$this -> render('formUserData', array(
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
	echo FooterMenuWidget::widget(array(
		'ItemList' => array(
			Yii::t('Dictionary', 'Start') => Url::to('index')
		)
	));
?>
	</div>
</div>