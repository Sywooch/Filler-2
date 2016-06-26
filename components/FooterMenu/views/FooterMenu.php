<?php

	use yii\helpers\Url;
	use yii\helpers\Html;
	use app\components\FooterMenu\assets\FooterMenuAsset;

	// Вычисление количества пунктов в меню.
	$MenuSize = sizeof($ItemList);

	// Индекс первого пункта меню.
	$ItemIndex = 1;

	FooterMenuAsset::register($this);
?>



<div class="col-xs-<?php echo($ColumnCount); ?> menu-bottom menu-text" id="Menu-Bottom">
<?php
	// Если указан первый стиль меню:
	if ($Style == 1) {
		// 
		$ColumnSize = round($ColumnCount / $MenuSize);
		// Если массив размеров пунктов меню не задан:
		if ($MenuSize != sizeof($SizeList)) {
			// Автоматическое генерирование размеров пунктов меню.
			$SizeList = array_fill(0, $MenuSize, $ColumnSize);
			// Ширина последнего пункта меню занимает оставшуюся часть.
			$SizeList[$MenuSize - 1] = $ColumnCount - ($ColumnSize * ($MenuSize - 1));
		}
		// Генерирование разметки меню.
		foreach ($ItemList as $ItemName => $ItemLink) {
			// 
			$htmlOptions = $this -> context -> addLinkClass($ItemLink);
?>
	<div class="col-xs-<?php echo($SizeList[$ItemIndex - 1]); ?> text-center">
		<?= Html::a($ItemName, $ItemLink, $htmlOptions); ?>
	</div>
<?php
			$ItemIndex++; 
		}
	}
	// Если указан второй стиль меню:
	else {
?>
	<div class="col-xs-<?php echo($ColumnCount); ?> text-center">
<?php
		// Генерирование разметки меню.
		foreach ($ItemList as $ItemName => $ItemLink) {
			// 
			$htmlOptions = $this -> context -> addLinkClass($ItemLink);
			// 
			echo Html::a($ItemName, $ItemLink, $htmlOptions);
			// Если последний пункт меню, разделитель после него не ставится.
			if ($ItemIndex != $MenuSize)
				echo $Delimiter;
			$ItemIndex++; 
		}
?>
	</div>
<?php
	}
?>
</div>
