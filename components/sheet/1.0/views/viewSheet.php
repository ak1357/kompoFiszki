<?php

$words = self::getSheetWords(); 


// Podział zbioru na arkusze

$i = 0;
$sheets = $tmpSheet = [];
foreach (((is_array($words)) ? $words : []) as $values) {
	
	$tmpSheet['front'][] = $values['word_name'];
	$tmpSheet['back'][] = $values['word_translation'];
	
	if (($i + 1) % 18 == 0) { 
		$sheets[] = $tmpSheet;
		$tmpSheet = [];
	}
	
	$i++;
}

if (is_array($tmpSheet) && sizeof($tmpSheet) > 0)
    $sheets[] = $tmpSheet;
	
	
// Wyodrębnianie wierszy na stronach

$tmpSheet = $tmp = $finalStructure = [];
foreach (((is_array($sheets)) ? $sheets : []) as $sheetIndex => $sheetWords) {
	
	
	// Budowanie wiersza
	
	foreach ($sheetWords as $side => $sideWords) { // odmiany
		
		$i = 0;
		$tmp['front'] = $tmp['back'] = [];
		foreach ($sideWords as $singleWord) {
			
			$tmp[$side][] = $singleWord;
			
			if (($i + 1) % 3 == 0) {
				$tmpSheet[$side][] = (($side == 'back') ? array_reverse($tmp['back']) : $tmp[$side]);
				$tmp[$side] = [];
			}
			
			$i++;
			
		}
		
		
		// Uzupełnienie arkusza o niepełne wiersze 
		
		foreach (['front', 'back'] as $side) {
			if (is_array($tmp[$side]) && sizeof($tmp[$side]) > 0) {
				
				if (sizeof($tmp[$side]) == 1)
					$tmp[$side][] = '';
					
				if (sizeof($tmp[$side]) == 2)
					$tmp[$side][] = '';
				
				$tmpSheet[$side][] = (($side == 'back') ? array_reverse($tmp['back']) : $tmp[$side]);
			}
		}
	}
	
	$finalStructure[] = $tmpSheet;
	$tmpSheet = [];
}


// Ostateczne wygenerowanie arkuszy

$pageIndex = 1;
foreach ($finalStructure as $page) {
	foreach ($page as $sideIndex => $side) {
	?>
		<div class="sheet">
			<div class="sheet__info">
				Arkusz <?= $pageIndex; ?> z <?= sizeof($page); ?>, <?= (($sideIndex == 'front') ? 'przód'  : 'tył'); ?>
			</div>
			<table class="sheet__table">
				<?php for ($rowIndex = 0; $rowIndex < 6; $rowIndex++) { ?>
					<tr>
						<?php for ($colIndex = 0; $colIndex < 3; $colIndex++) { ?>
							<td class="sheet__cell">
								<?= ((!empty($side[$rowIndex][$colIndex])) ? $side[$rowIndex][$colIndex] : ''); ?>
							</td>
						<?php } ?>
					</tr>
				<?php } ?>
			</table>
		</div>
	<?php } ?>
<?php } ?>

<style>
	
	.navbar {
		display: none;
	}
	
	.container {
		width: 100%;
		text-align: center;
		margin: 0;
	}
	
	.sheet {
		width: 210mm;
		height: 265mm;
		border: 1px solid #ccc;
		margin: 0 auto;
		margin-bottom: 5mm;
	}
	
	.sheet__info {
		text-align: left;
		margin-top: 10mm;
		margin-left: 12mm;
	}
		
	.sheet__table {
		margin: 2mm 12mm 20mm 12mm;
		border-style: dashed;
	}
	
	.sheet__cell {
		width: 60mm;
		height: 35mm;
		background: #fff;
		font-size: 1.2em;
		border-color: #000;
		border-style: dashed;
	}
	
</style>

<script>
 window.print();
</script>