
<div class="row">
	<div class="col-xs-24" id="header">
		<div class="col-xs-24 text-left" id="Logo">
			<?php
				// Изображение логотипа.
				echo CHtml::image(
					Yii::app() -> request -> baseUrl . '/images/LogoSmall.png',
					Yii::t('Dictionary', 'Filler')
				);
			?>
		</div>
	</div>
</div>
