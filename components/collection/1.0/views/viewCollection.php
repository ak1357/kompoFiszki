<?php global $context; ?>
<?php $collection = self::getCollection(); ?>

<h2 class="container__header">Podgląd zestawu fiszek</h2>

<table class="mb10">
	<thead>
    	<tr>
        	<th width="30" class="align--center">Lp.</th>
            <th width="400" class="align--left">Awers</th>
            <th width="400" class="align--left">Rewers</th>
            <th width="90" class="align--center">Opcje</th>
		</tr>
    </thead>
    <tbody>
		
        <?php
        $i = 0;
        $words = self::getCollectionWords($uriParams[1]);
        
        if (is_array($words) && sizeof($words) > 0) {
            foreach ($words as $wordId => $word) {
				$i++; 
				?>
                
				<tr>
                	<td class="align--center"><?= $i; ?></td>
                    <td class="align--left"><?= $word['word_name']; ?></td>
                    <td class="align--left"><?= $word['word_translation']; ?></td>
                    <td class="align--center">
                       <?php if ($collection) { ?>
                        <a href="<?= $context; ?>/word/edit/<?= $wordId; ?>">Edytuj</a>
                        <a href="<?= $context; ?>/word/delete/<?= $wordId; ?>">Usuń</a>
                        <?php } ?>
                    </td>
                </tr>
                
                <?php
            }
        } else {
		?>
            
            <tr>
            	<td colspan="4" class="align--center">
            	    <em>Brak fiszek do wyświetlenia</em>
                </td>
			</tr>
            
        <?php } ?>
    </tbody>
</table>

<?php if ($collection !== false) { ?>
<a href="<?= $context; ?>/word/add/<?= $collection['collection_id']; ?>" class="btn btn--blue">+ Nowa fiszka</a>
<?php } ?>