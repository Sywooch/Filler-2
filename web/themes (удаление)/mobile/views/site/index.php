
<div class="row">
	<div class="col-xs-24" id="IndexShadowHeader">
	</div>
</div>
<div class="row">
	<div class="col-xs-24" id="IndexHeader">
	</div>
</div>
<div class="row">
	<div class="col-xs-24 text-center " id="Picture">
	</div>
</div>



<div class="modal fade" id="PlayerAuthorization" tabindex="-1" role="dialog" aria-labelledby="Authorization" data-backdrop="static" aria-hidden="true">
	<div id="Loading">
		<img src="<?php echo(Yii::app() -> theme -> baseUrl . '/images/ajax-loader.gif'); ?>" alt=""></img>
	</div>
	<div class="modal-dialog modal-lg" style="width:96%;">
		<div class="modal-content modal-background">
			<div class="modal-header" style="padding:30px;">
				<button type="button" class="close text-48" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title text-48" id="PlayerAuthorizationTitle"><?php echo(Yii::t('Dictionary', 'Player authorization')); ?></h4>
			</div>
			<form id="EmailForm" action="<?php echo($this -> createUrl("/site/login")); ?>" method="post">
				<div class="modal-body" style="padding:30px;">
					<div class="form-group">
						<div class="text-36 error-message text-left" id="AuthorizationError"></div>
						<input type="email" class="form-control text-48" style="height:100px; margin-bottom:40px; padding:0 30px;" maxlength="50" name="Email" id="Email" placeholder="<?php echo(Yii::t('Dictionary', 'E-mail')); ?>">
					</div>
					<div class="form-group">
						<input type="password" class="form-control text-48" style="height:100px; margin-bottom:40px; padding:0 30px;" maxlength="50" name="Password" id="Password" placeholder="<?php echo(Yii::t('Dictionary', 'Password')); ?>">
					</div>
					<div class="text-right standart-link">
						<?php echo CHtml::link(Yii::t('Dictionary', 'Forgot your password?'), $this -> createUrl("/site/forgot")); ?>
					</div>
				</div>
				<div class="modal-footer" style="padding:30px;">
					<button type="submit" class="btn btn-primary btn-block btn-lg col-xs-24 text-48" style="height:120px;" id="LoginButton"><?php echo(Yii::t('Dictionary', 'Login')); ?></button>
				</div>
			</form>
		</div>
	</div>
</div>



<div class="row">
	<div class="col-xs-24 IndexFooterMenuBlock ">

<?php

	// Если пользователь не авторизован:
	if (Yii::app() -> user -> isGuest) {
		// Выводится меню "Вход | Регистрация".
		$this -> widget('ext.FooterMenu.FooterMenuWidget', array(
			'ItemList' => array(
				Yii::t('Dictionary', 'Login') => 'javascript:LoginWindow();',
				Yii::t('Dictionary', 'Registration') => $this -> createUrl("/site/registration")
			),
			'Style' => 2
		));
	}
	// Если пользователь авторизован:
	else {
		// Выводится меню "Играть | Выход".
		$this -> widget('ext.FooterMenu.FooterMenuWidget', array(
			'ItemList' => array(
				Yii::t('Dictionary', 'Play') => $this -> createUrl("/game/game"),
				Yii::t('Dictionary', 'Logout') => $this -> createUrl("/site/logout")
			),
			'Style' => 2
		));
	}

?>

	</div>
</div>
