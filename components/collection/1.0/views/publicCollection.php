<?php global $context; ?>
<?php $isAdmin = self::isAdmin(); ?>

<h2 class="container__header">Publiczne zestawy fiszek</h2>

<table class="mb10">

    <thead>
        <tr>
            <th width="30" class="align--center">Lp.</th>
            <th width="300" class="align--left">Nazwa</th>
            <th width="320" class="align--left">Komentarz</th>
            <th width="150" class="align--left">Użytkownik</th>
        	<th width="150" class="align--center">Opcje</th>
    	</tr>
	</thead>

    <tbody>
		
		<?php
		$i = 0; 
		$collections = self::getCollections(true);
        
        if (is_array($collections) && sizeof($collections) > 0) {
            foreach ($collections as $collectionId => $collection) {
                $i++; ?>
                
                <tr>
                	<td class="align--center"><?= $i; ?></td>
                    <td class="align--left">
                    	<a href="<?= $context; ?>/collection/view/<?= $collectionId; ?>"><?= $collection['collection_name']; ?></a>
                    </td>
                    <td class="align--left"><?= $collection['collection_desc']; ?></td>
                    <td class="align--left"><?= $collection['user_login']; ?></td>
                    <td class="align--center">
                        <?= (($isAdmin) ? '<a href="' . $context .'/collection/edit/' . $collectionId . '">Edytuj</a> ' : ''); ?>
                        <?= (($isAdmin) ? '<a href="' . $context . '/collection/delete/' . $collectionId . '">Usuń</a> ' : ''); ?>
                    </td>
				</tr>
				
            	<?php 
			}
        } else { ?>
            
			<tr>
				<td colspan="5" class="align--center">
					<em>Brak publicznych zestawów innych użytkowników do wyświetlenia</em>
				</td>
			</tr>
				
        <?php } ?>
        
    </tbody>
</table>