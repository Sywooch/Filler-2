<?php

	use yii\widgets\ActiveForm;
	use yii\helpers\Html;
	use yii\captcha\Captcha;

	// 
	$form = ActiveForm::begin([
		'id' => 'user-form',
		// 'options' => ['class' => 'form-horizontal'],
		// 'action' => \yii\helpers\Url::toRoute('/site/test'),
		// 'enableClientValidation' => true,
		// 'enableAjaxValidation' => true,
		// 'action' => 'save-url',
		// 'enableAjaxValidation' => true,
		// 'validationUrl' => 'validation-rul',
		// 'clientOptions' => [
			// 'errorCssClass' => 'has-error',
			// 'successCssClass' => 'has-success',
			// 'inputContainer' => 'div.col-xs-24',
			'validateOnBlur' => true,
			// 'validateOnSubmit' => true,
			'validateOnChange' => true,
			'validateOnType' => true,
			'validationDelay' => 300,
		// ],
		// 'fieldConfig' => ['template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}"]
		// 'errorMessageCssClass' => 'error-message',
	]);
?>

<?php if (isset($Field['Name']) && $Field['Name']) { ?>
	<div class="row">
		<?=
			$form -> field($Model, 'Name', [
				'template' => '<div class="col-xs-24">{error}{input}</div>',
				'inputOptions' => [
					'class' => 'form-control',
					'placeHolder' => Yii::t('Dictionary', 'Player name'),
				],
			]) -> textInput();
		?>
	</div>
<?php } ?>

<?php if (isset($Field['Email']) && $Field['Email']) { ?>
	<div class="row">
		<?=
			$form -> field($Model, 'Email', [
				'template' => '<div class="col-xs-24">{error}{input}</div>',
				'inputOptions' => [
					'class' => 'form-control',
					'placeHolder' => Yii::t('Dictionary', 'E-mail')
			]]) -> input('email');
		?>
	</div>
<?php } ?>

<?php if (isset($Field['Password']) && $Field['Password']) { ?>
	<div class="row">
		<?=
			$form -> field($Model, 'Password', [
				'template' => '<div class="col-xs-24">{error}{input}</div>', 
				'inputOptions' => [
					'class' => 'form-control',
					'placeHolder' => Yii::t('Dictionary', 'Password')
			]]) -> passwordInput();
		?>
	</div>
<?php } ?>

<?php if (isset($Field['ControlPassword']) && $Field['ControlPassword']) { ?>
	<div class="row">
		<?=
			$form -> field($Model, 'ControlPassword', [
				'template' => '<div class="col-xs-24">{error}{input}</div>',
				'inputOptions' => [
					'class' => 'form-control',
					'placeHolder' => Yii::t('Dictionary', 'Confirm password')
			]]) -> passwordInput();
		?>
	</div>
<?php } ?>

<?php if (isset($Field['ControlCode']) && $Field['ControlCode']) { ?>
	<div class="row">
		<?=
			$form -> field($Model, 'ControlCode', ['template' => '{error}{input}']) -> widget(Captcha::className(), [
				'options' => [
					'clickableImage' => true,
					'class' => 'form-control',
					'placeholder' => Yii::t('Dictionary', 'Control code'),
				],
				'template' => '<div class="col-xs-12">{input}</div><div class="col-xs-12 cursor-pointer">{image}</div>',
			]);
		?>
	</div>
<?php } ?>

<div class="row">
	<div class="col-xs-24 indent-md text-right" id="EntryButton">
		<?= 
			Html::submitButton(($Button['Name']), [
				'class' => 'btn btn-primary btn-lg',
				'style' => 'padding-left: 50px; padding-right: 50px;'
			]);
		?>
	</div>
</div>

<?php ActiveForm::end(); ?>
