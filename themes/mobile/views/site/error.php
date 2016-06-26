
<div class="row">
	<div class="col-xs-24" id="header">
		<div class="col-xs-24 text-left" id="Logo">
			<?php
				// Логотип. Для каждого языка выводится свой логотип.
				echo CHtml::image(
					Yii::app() -> request -> baseUrl . '/images/LogoSmall.png',
					// Yii::app() -> request -> baseUrl . '/images/logo/' . Yii::app() -> getLanguage() . '/Logo.gif',
					Yii::t('Dictionary', 'Filler')
				);
			?>
		</div>
	</div>
</div>

<div class="row">
	<?php
		print_r($error);
	?>
</div>

<div class="row">
	<div class="col-md-24 IndexFooterMenuBlock ">

<?php

	// Выводится меню "В начало".
	$this -> widget('ext.FooterMenu.FooterMenuWidget', array(
		'ItemList' => array(
			Yii::t('Dictionary', 'Start') => $this -> createUrl("/site/index")
		)
	));

?>

	</div>
</div>
