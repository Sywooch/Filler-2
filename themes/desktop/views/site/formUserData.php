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
		'enableAjaxValidation' => true,
		// 'action' => 'save-url',
		// 'enableAjaxValidation' => true,
		// 'validationUrl' => 'validation-rul',
		// 'clientOptions' => [
			// 'errorCssClass' => 'has-error',
			// 'successCssClass' => 'has-success',
			// 'inputContainer' => 'div.col-xs-24',
			'validateOnBlur' => true,
			'validateOnSubmit' => true,
			'validateOnChange' => true,
			'validateOnType' => true,
			'validationDelay' => 300,
		// ],
		// 'fieldConfig' => ['template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}"]
		// 'errorMessageCssClass' => 'error-message',
	]);
	// echo Html::a($errorCss = '');
?>
<?php if (isset($Field['Name']) && $Field['Name']) { ?>
<div class="row">
	<!-- <div class="col-xs-24 <?php //if ($Model -> hasErrors('Name')) echo('has-error'); ?>"> -->
		<?php //echo $form -> field($Model, 'Name') -> begin(
			//['validateOnType' => true]
		//); ?>
		<?php //echo Html::activeLabel($Model,'Name'); ?>
		<?php //echo Html::activeTextInput($Model,'Name', [
			//'class' => 'form-control',
			//'placeHolder' => Yii::t('Dictionary', 'Player name'),
		//]); ?>
		<?php //echo Html::error($Model,'Name', ['class' => 'help-block']); ?>
		<?php
			// echo $form -> error($Model);
			// echo $Model -> hasErrors('Name');
			// echo Html::error($Model, 'Name', ['class' => 'help-block']);
			echo $form -> field($Model, 'Name', [
				// 'enableAjaxValidation' => true,
				// 'validateOnChange' => true,
				// 'validateOnType' => true,
				// 'validationDelay' => 300,
				// 'template' => '{error}{input}', 
				'template' => '<div class="col-xs-24">{error}{input}</div>',
				'inputOptions' => [
					'class' => 'form-control',
					'placeHolder' => Yii::t('Dictionary', 'Player name'),
				],
				// 'feedbackIcon' => [
					// 'default' => 'envelope',
					// 'success' => 'ok',
					// 'error' => 'has-error',
					// 'defaultOptions' => ['class'=>'text-primary']
				// ]
			]) -> textInput();
			// , array(
				// 'class' => 'form-control',
				// 'placeholder' => Yii::t('Dictionary', 'Player name')
			// ));
		?>
		<?php //echo $form -> field($Model, 'Name') -> end(); ?>
	<!-- </div> -->
</div>
<?php } ?>
<?php if (isset($Field['Email']) && $Field['Email']) { ?>
<div class="row">
	<!-- <div class="col-xs-24 <?php //if ($Model -> hasErrors('Email')) echo('has-error'); ?>"> -->
		<?php
			// echo $form -> error($Model, 'Email');
			echo $form -> field($Model, 'Email', [
				// 'template' => '{error}{input}',
				'template' => '<div class="col-xs-24">{error}{input}</div>',
				'inputOptions' => [
					'class' => 'form-control',
					'placeHolder' => Yii::t('Dictionary', 'E-mail')
			]]) -> input('email');
			// echo $form -> emailField($Model, 'Email', array(
			// 	'class' => 'form-control',
			// 	'placeholder' => Yii::t('Dictionary', 'E-mail')
			// ));
		?>
	<!-- </div> -->
</div>
<?php } ?>
<?php if (isset($Field['Password']) && $Field['Password']) { ?>
<div class="row">
	<!-- <div class="col-xs-24"> -->
		<?php
			// echo $form -> error($Model, 'Password');
			echo $form -> field($Model, 'Password', [
				// 'template' => '{error}{input}',
				'template' => '<div class="col-xs-24">{error}{input}</div>', 
				'inputOptions' => [
					'class' => 'form-control',
					'placeHolder' => Yii::t('Dictionary', 'Password')
			]]) -> passwordInput();
			// echo $form -> passwordField($Model, 'Password', array(
			// 	'class' => 'form-control',
			// 	'placeholder' => Yii::t('Dictionary', 'Password')
			// ));
		?>
	<!-- </div> -->
</div>
<?php } ?>
<?php if (isset($Field['ControlPassword']) && $Field['ControlPassword']) { ?>
<div class="row">
	<!-- <div class="col-xs-24"> -->
		<?php
			// echo $form -> error($Model, 'ControlPassword');
			echo $form -> field($Model, 'ControlPassword', [
				// 'template' => '{error}{input}',
				'template' => '<div class="col-xs-24">{error}{input}</div>',
				'inputOptions' => [
					'class' => 'form-control',
					'placeHolder' => Yii::t('Dictionary', 'Confirm password')
			]]) -> passwordInput();
			// echo $form -> passwordField($Model, 'ControlPassword', array(
			// 	'class' => 'form-control',
			// 	'placeholder' => Yii::t('Dictionary', 'Confirm password')
			// ));
		?>
	<!-- </div> -->
</div>
<?php } ?>
<?php if (isset($Field['ControlCode']) && $Field['ControlCode']) { ?>
<div class="row">
	<!-- <div class="col-xs-1 <?php //if ($Model -> hasErrors('ControlCode')) echo('has-error'); ?>"> -->
		<?php
			// Отключение для поля "Контрольный код" подсветки правильных данных.
			// echo $form -> error($Model, 'ControlCode', array('successCssClass' => ''));
			// echo $form -> field($Model, 'ControlCode', [
			// 	'template' => '{error}{input}', 
			// 	'inputOptions' => [
			// 		'class' => 'form-control',
			// 		'placeHolder' => Yii::t('Dictionary', 'Control code')
			// ]]) -> textInput();
			// echo $form -> textField($Model, 'ControlCode', array(
			// 	'class' => 'form-control',
			// 	'placeholder' => Yii::t('Dictionary', 'Control code')
			// ));
		?>
	<!-- </div> -->
	<!-- <div class="col-xs-23 cursor-pointer" title="<?php //echo(Yii::t('Dictionary', 'Click to update the control code')); ?>"> -->
		<?php
			// echo Captcha::widget([
				// 'name' => 'captcha',
				// 'model' => $Model,
				// 'options' => [
					// 'template' => '{input}{image}',
					// 'placeHolder' => Yii::t('Dictionary', 'Control code')
				// ]

				// 'captchaAction' => '/index/captcha',
				// 'template' => '<div class="row"><div class="col-lg-4">{image}</div><div class="col-lg-7">{input}</div></div>',
				
				// 'inputOptions' => [
				// 	'class' => 'form-control',
					// 'placeHolder' => Yii::t('Dictionary', 'Control code')
				// ]

				// 'attribute' => 'captcha',
				//'showRefreshButton' => false,
				//'clickableImage' => true,
			// ]);
			// echo $form -> field($Model, 'Captcha') -> widget(\yii\captcha\Captcha::classname(), [
			// 	configure additional widget properties here
			// ]);
		?>
		<?= $form -> field($Model, 'ControlCode', ['template' => '{error}{input}']) -> widget(Captcha::className(), [
			// 'captchaAction' => '/site/captcha',
			'options' => [
				'clickableImage' => true,
				'class' => 'form-control',
				'placeholder' => Yii::t('Dictionary', 'Control code'),
			],
			// 'template' => '{input}{image}',
			'template' => '<div class="col-xs-12">{input}</div><div class="col-xs-12 cursor-pointer">{image}</div>',
		]); ?>
	<!-- </div> -->
</div>
<?php } ?>
<div class="row">
	<div class="col-xs-24 indent-md text-right" id="EntryButton">
		<?= 
			Html::submitButton(($Button['Name']), array(
				'class' => 'btn btn-primary btn-lg',
				'style' => 'padding-left: 50px; padding-right: 50px;'
			));
		?>
	</div>
</div>
<?php ActiveForm::end(); ?>
