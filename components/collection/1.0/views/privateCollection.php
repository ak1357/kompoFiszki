<?php global $context; ?>

<h2 class="container__header">Moje zestawy fiszek</h2>

<table class="mb10">

   <thead>
       <tr>
           <th width="30" class="align--center">Lp.</th>
           <th width="300" class="align--left">Nazwa</th>
           <th width="350" class="align--left">Komentarz</th>
           <th width="70" class="align--center">Publiczny</th>
           <th width="200" class="align--center">Opcje</th>
       </tr>
   </thead>
   
   <tbody>
        
        <?php
        $i = 0; 
        $collections = self::getCollections();
        
        if (is_array($collections) && sizeof($collections) > 0) {
            foreach ($collections as $collectionId => $collection) {
                $i++;
                ?>
               
                <tr>
                    <td class="align--center"><?= $i; ?></td>
                    <td class="align--left">
	                    <a href="<?= $context; ?>/collection/view/<?= $collectionId; ?>"><?= $collection['collection_name']; ?></a>
                    </td>
                    <td class="align--left"><?= $collection['collection_desc']; ?></td>
                    <td class="align--center"><?= (($collection['collection_is_public'] == 1) ? 'tak' : '-'); ?></td>
                    <td class="align--center">
                        <a href="<?= $context; ?>/quiz/collection/<?= $collectionId; ?>">Quiz</a>
						<a href="<?= $context; ?>/sheet/collection/<?= $collectionId; ?>">Drukuj</a>
                        <a href="<?= $context; ?>/collection/edit/<?= $collectionId; ?>">Edytuj</a>
                        <a href="<?= $context; ?>/collection/delete/<?= $collectionId; ?>">Usuń</a>
                    </td>
                </tr>
                
                <?php
            }
        } else { ?>
            
            <tr>
                <td colspan="5" class="align--center">
                    <em>Brak zestawów do wyświetlenia</em>
                </td>
            </tr>
            
        <?php } ?>
    </tbody>
</table>

<a href="<?= $context; ?>/collection/add" class="btn btn--blue">+ Nowy zestaw</a>

