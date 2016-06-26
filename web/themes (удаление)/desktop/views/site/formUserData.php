<?php
	// 
	$form = $this -> beginWidget('CActiveForm', array(
		'id' => 'user-form',
		'enableAjaxValidation' => true,
		'clientOptions' => array(
			'errorCssClass' => 'has-error',
			'successCssClass' => 'has-success',
			'inputContainer' => 'div.col-xs-24',
			'validateOnSubmit' => true,
			'validateOnChange' => true,
			'validateOnType' => true,
			'validationDelay' => 300,
		),
		'errorMessageCssClass' => 'error-message',
	));
	CHtml::$errorCss = '';
?>
<?php if (isset($Field['Name']) && $Field['Name']) { ?>
<div class="row">
	<div class="col-xs-24 <?php if ($Model -> hasErrors('Name')) echo('has-error'); ?>">
		<?php
			echo $form -> error($Model, 'Name');
			echo $form -> textField($Model, 'Name', array(
				'class' => 'form-control',
				'placeholder' => Yii::t('Dictionary', 'Player name')
			));
		?>
	</div>
</div>
<?php } ?>
<?php if (isset($Field['Email']) && $Field['Email']) { ?>
<div class="row">
	<div class="col-xs-24 <?php if ($Model -> hasErrors('Email')) echo('has-error'); ?>">
		<?php
			echo $form -> error($Model, 'Email');
			echo $form -> emailField($Model, 'Email', array(
				'class' => 'form-control',
				'placeholder' => Yii::t('Dictionary', 'E-mail')
			));
		?>
	</div>
</div>
<?php } ?>
<?php if (isset($Field['Password']) && $Field['Password']) { ?>
<div class="row">
	<div class="col-xs-24">
		<?php
			echo $form -> error($Model, 'Password');
			echo $form -> passwordField($Model, 'Password', array(
				'class' => 'form-control',
				'placeholder' => Yii::t('Dictionary', 'Password')
			));
		?>
	</div>
</div>
<?php } ?>
<?php if (isset($Field['ControlPassword']) && $Field['ControlPassword']) { ?>
<div class="row">
	<div class="col-xs-24">
		<?php
			echo $form -> error($Model, 'ControlPassword');
			echo $form -> passwordField($Model, 'ControlPassword', array(
				'class' => 'form-control',
				'placeholder' => Yii::t('Dictionary', 'Confirm password')
			));
		?>
	</div>
</div>
<?php } ?>
<?php if (isset($Field['ControlCode']) && $Field['ControlCode']) { ?>
<div class="row">
	<div class="col-xs-12 <?php if ($Model -> hasErrors('ControlCode')) echo('has-error'); ?>">
		<?php
			// Отключение для поля "Контрольный код" подсветки правильных данных.
			echo $form -> error($Model, 'ControlCode', array('successCssClass' => ''));
			echo $form -> textField($Model, 'ControlCode', array(
				'class' => 'form-control',
				'placeholder' => Yii::t('Dictionary', 'Control code')
			));
		?>
	</div>
	<div class="col-xs-12 cursor-pointer" title="<?php echo(Yii::t('Dictionary', 'Click to update the control code')); ?>">
		<?php
			$this -> widget('CCaptcha', array(
				'showRefreshButton' => false,
				'clickableImage' => true,
			));
		?>
	</div>
</div>
<?php } ?>
<div class="row">
	<div class="col-xs-24 indent-md text-right" id="EntryButton">
		<?php 
			echo CHtml::submitButton(($Button['Name']), array(
				'class' => 'btn btn-primary btn-lg',
				'style' => 'padding-left: 50px; padding-right: 50px;'
			));
		?>
	</div>
</div>
<?php $this -> endWidget(); ?>
