<?php $collections = self::getWordCollections(); ?>

<h2 class="container__header">Dodawanie słowa</h2>

<form method="post" action="" class="form">
    <input type="text" name="name" value="<?= (!empty($_POST['name']) ? $_POST['name'] : '') ?>" placeholder="Wyraz" class="form__input" />
    <input type="text" name="translation" value="<?= (!empty($_POST['translation']) ? $_POST['translation'] : '') ?>" placeholder="Tłumaczenie" class="form__input" />
    
    <select name="collection" class="form__input">
        <?php
        foreach (((is_array($collections)) ? $collections : []) as $collectionId => $collection) {
            $selected = (((!empty($_POST['collection']) && $_POST['collection'] == $collectionId) 
                            || (!empty($word['collection_id']) && $word['collection_id'] == $collectionId)
                            || (!empty($uriParams[1]) && $uriParams[1] == $collectionId))
                            ? 'selected' : '');
            
            print '<option value="' . $collectionId . '" ' . $selected . '>' . $collection['collection_name'] . '</option>';
        }
        ?>
    </select>
    
    <div class="form__buttons">
        <button name="sent" class="btn btn--blue">Dodaj</button>
        <a href="#" onclick="window.history.back();" class="btn btn--gray">Anuluj</a>
    </div>
</form>