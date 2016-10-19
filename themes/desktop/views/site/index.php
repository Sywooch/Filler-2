<?php

	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\web\View;

	use app\assets\IndexAsset;
	use app\assets\ThemesAsset;

	use app\components\FooterMenu\FooterMenuWidget;

	//
	IndexAsset::register($this);
	//
	$bundle = ThemesAsset::register($this);

	$this -> registerJs(
		"var BASE_URL = '';
		var ERROR_MESSAGE = [];
		ERROR_MESSAGE[0] = '" . Yii::t('Dictionary', 'Enter a e-mail address') . "';
		ERROR_MESSAGE[1] = '" . Yii::t('Dictionary', 'Enter a password') . "';
		ERROR_MESSAGE[2] = '" . Yii::t('Dictionary', 'Incorrect e-mail address') . "';", 
		View::POS_HEAD, 
		'Authorization'
	);

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
		<img src="<?= $bundle -> baseUrl . '/images/ajax-loader.gif'; ?>" alt=""></img>
	</div>
	<div class="modal-dialog modal-sm">
		<div class="modal-content modal-background">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="PlayerAuthorizationTitle"><?= Yii::t('Dictionary', 'Player authorization'); ?></h4>
			</div>
			<form id="EmailForm" action="<?= Url::to(['site/login']); ?>" method="post">
				<input type="hidden" name="_csrf" value="<?= Yii::$app -> request -> getCsrfToken(); ?>" />
				<div class="modal-body">
					<div class="form-group">
						<div class="text-14 error-message text-left" id="AuthorizationError"></div>
						<input type="email" class="form-control" maxlength="50" name="Email" id="Email" placeholder="<?= Yii::t('Dictionary', 'E-mail'); ?>">
					</div>
					<div class="form-group">
						<input type="password" class="form-control" maxlength="50" name="Password" id="Password" placeholder="<?= Yii::t('Dictionary', 'Password'); ?>">
					</div>
					<div class="text-right standart-link">
						<?= Html::a(Yii::t('Dictionary', 'Forgot your password?'), Url::to(['site/forgot'])); ?>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary btn-block btn-lg" id="LoginButton"><?= Yii::t('Dictionary', 'Login'); ?></button>
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
		// Выводится меню "Вход | Регистрация".
		echo FooterMenuWidget::widget([
			'ItemList' => [
				Yii::t('Dictionary', 'Login') => 'javascript:LoginWindow();',
				Yii::t('Dictionary', 'Registration') => Url::to(['site/registration'])
			],
			'Style' => 2
		]);
	}
	// Если пользователь авторизован:
	else {
		// Выводится меню "Играть | Выход".
		echo FooterMenuWidget::widget([
			'ItemList' => [
				Yii::t('Dictionary', 'Play') => Url::to(['game/game']),
				Yii::t('Dictionary', 'Logout') => Url::to(['site/logout'])
			],
			'Style' => 2
		]);
	}

?>

	</div>
</div>
