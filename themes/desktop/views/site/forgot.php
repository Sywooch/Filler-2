<?php

	use yii\helpers\Url;
	use yii\helpers\Html;
	use app\components\FooterMenu\FooterMenuWidget;

	if ($Result == TRUE) {

?>

<div class="row">
	<div class="col-lg-8 col-md-12 col-sm-16 col-xs-20 col-lg-offset-8 col-md-offset-6 col-sm-offset-4 col-xs-offset-2 text-center">

		<div class="col-xs-24 text-left indent-top-lg indent-bottom-md text-18 color-blue">
			<?= Yii::t('Dictionary', 'The password has been reset successfully!'); ?>
		</div>

		<div class="col-xs-24 text-left indent-bottom-lg color-gray text-14">
			<?= Yii::t('Dictionary', 'To the following email address sent an email with further instructions to restore your access. The email has a limited lifetime. We recommend you to complete the procedure right now.'); ?>
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

			<div class="col-xs-24 text-left indent-top-lg indent-bottom-md text-18 color-blue">
				<?= Yii::t('Dictionary', 'Access recovery') . ' : ' . Yii::t('Dictionary', 'Step {i} of {n}', ['i' => 1, 'n' => 2]); ?>
			</div>

			<div class="col-xs-24 indent-bottom-md color-gray">
				<?= Yii::t('Dictionary', 'If you have already registered but have forgotten your password, enter your e-mail address provided during registration. To your address will be sent an e-mail with a link to reset the forgotten and the new password setup.'); ?>
			</div>

			<?=
				$this -> render('formUserData', [
					'Model' => $Model,
					'Field' => [
						'Email' => true
					],
					'Button' => [
						'Name' => Yii::t('Dictionary', 'Send'),
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
