<?php

	use yii\helpers\Url;
	use yii\helpers\Html;
	use app\components\FooterMenu\FooterMenuWidget;

	if ($Result == TRUE) {

?>

<div class="row">
	<div class="col-lg-8 col-md-12 col-sm-16 col-xs-20 col-lg-offset-8 col-md-offset-6 col-sm-offset-4 col-xs-offset-2 text-center">
		<div class="col-xs-24 text-left indent-top-lg indent-bottom-md text-18 color-blue">
			<?= Yii::t('Dictionary', 'The new password is successfully set!'); ?>
		</div>
		<div class="col-xs-24 text-left indent-bottom-lg color-gray text-14">
			<?= Yii::t('Dictionary', 'Your access restored successfully! The new password is already in force and a copy sent to your e-mail.'); ?>
		</div>
	</div>
</div>

<?php

	}
	else {

?>

<div class="row">
	<div class="col-lg-8 col-md-12 col-sm-16 col-xs-20 col-lg-offset-8 col-md-offset-6 col-sm-offset-4 col-xs-offset-2 text-center">
		<div class="form text-left text-14">
			<div class="col-xs-24 text-left indent-top-md indent-bottom-md text-18 color-blue">
				<?= Yii::t('Dictionary', 'Access recovery') . ' : ' . Yii::t('Dictionary', 'Step {i} of {n}', ['i' => 2, 'n' => 2]); ?>
			</div>
			<div class="col-xs-24 indent-bottom-md color-gray">
				<?= Yii::t('Dictionary', 'To reset a forgotten password and setting a new, enter the new password twice. Copy the new password will be sent to your email.'); ?>
			</div>
			<?=
				$this -> render('formUserData', [
					'Model' => $Model,
					'Field' => [
						'Password' => true,
						'ControlPassword' => true,
					],
					'Button' => [
						'Name' => Yii::t('Dictionary', 'Save'),
					],
				]);
			?>
		</div><!-- form -->
	</div>
</div>

<?php
	
	}

?>

<div class="row">
	<div class="col-md-24 IndexFooterMenuBlock ">
<?=
	// Выводится меню "В начало".
	FooterMenuWidget::widget([
		'ItemList' => [
			Yii::t('Dictionary', 'Start') => Url::to(['site/index'])
		],
		'Style' => 2
	]);
?>
	</div>
</div>
