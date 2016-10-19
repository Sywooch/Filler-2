<?php

	use Yii;
	use yii\helpers\Url;
	use app\components\FooterMenu\FooterMenuWidget;

?>

<div class="row">
	<div class="col-xs-1">
	</div>
	<div class="col-xs-22">
		<div class="col-xs-24 text-center indent-top-lg indent-bottom-md text-24 color-white">
			<?= Yii::t('Dictionary', 'Something happened') . ' (((' ?>
		</div>
		<div class="col-xs-24 text-14 indent-bottom-lg text-center color-gray">
			<?= $message ?>
		</div>
	</div>
	<div class="col-xs-1">
	</div>
</div>

<div class="row">
	<div class="col-md-24 IndexFooterMenuBlock ">

<?=
	// Выводится меню "В начало".
	FooterMenuWidget::widget([
		'ItemList' => [
			Yii::t('Dictionary', 'Start') => Url::to(['site/index'])
		]
	]);
?>

	</div>
</div>
