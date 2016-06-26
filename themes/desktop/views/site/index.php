<?php

	use yii\helpers\Url;
	use yii\helpers\Html;
	// use yii\base\Widget;
	use app\components\FooterMenu\FooterMenuWidget;

	// use app\assets\IndexAsset;
	// use app\assets\ThemesAsset;
	// IndexAsset::register($this);
	// ThemesAsset::register($this);

?>

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
		<img src="<?php echo($this -> theme -> getUrl('/images/ajax-loader.gif')); ?>" alt=""></img>
	</div>
	<div class="modal-dialog modal-sm">
		<div class="modal-content modal-background">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="PlayerAuthorizationTitle"><?php echo(Yii::t('Dictionary', 'Player authorization')); ?></h4>
			</div>
			<form id="EmailForm" action="<?php echo Url::to(['site/login']); ?>" method="post">
				<div class="modal-body">
					<div class="form-group">
						<div class="text-14 error-message text-left" id="AuthorizationError"></div>
						<input type="email" class="form-control" maxlength="50" name="Email" id="Email" placeholder="<?php echo(Yii::t('Dictionary', 'E-mail')); ?>">
					</div>
					<div class="form-group">
						<input type="password" class="form-control" maxlength="50" name="Password" id="Password" placeholder="<?php echo(Yii::t('Dictionary', 'Password')); ?>">
					</div>
					<div class="text-right standart-link">
						<?php echo Html::a(Yii::t('Dictionary', 'Forgot your password?'), Url::to('site/forgot')); ?>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary btn-block btn-lg" id="LoginButton"><?php echo(Yii::t('Dictionary', 'Login')); ?></button>
				</div>
			</form>
		</div>
	</div>
</div>



<div class="row">
	<div class="col-md-24 IndexFooterMenuBlock ">

<?php
	
	// Если пользователь не авторизован:
	if (Yii::$app -> user -> isGuest) {
?>
<?=
		// Выводится меню "Вход | Регистрация".
		FooterMenuWidget::widget(array(
			'ItemList' => array(
				Yii::t('Dictionary', 'Login') => 'javascript:LoginWindow();',
				Yii::t('Dictionary', 'Registration') => Url::to('registration')
			),
			'Style' => 2
		));
?>
<?php
	}
	// Если пользователь авторизован:
	else {
		// Выводится меню "Играть | Выход".
		echo FooterMenuWidget::widget(array(
			'ItemList' => array(
				Yii::t('Dictionary', 'Play') => Url::to('game'),
				Yii::t('Dictionary', 'Logout') => Url::to('logout')
			),
			'Style' => 2
		));
	}

?>

	</div>
</div>
