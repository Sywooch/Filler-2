<?php

	use yii\helpers\Url;
	use yii\helpers\Html;
	use app\components\LanguageSwitcher\assets\LanguageSwitcherAsset;

	LanguageSwitcherAsset::register($this);
?>

<div class="Language-Label Language-BlueLight-Box" title="<?php echo(Yii::t('Dictionary', 'Language switcher')); ?>" id="Button-Language">
	<?= Html::a($LanguageName, $LanguageLink); ?>
</div>
