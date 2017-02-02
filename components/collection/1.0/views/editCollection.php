<?php $collection = self::getCollection(); ?>
<?php $isPublic = ((!empty($collection['collection_is_public']) && $collection['collection_is_public'] == 1) 
                   || (!empty($_POST['is_public']) && $_POST['is_public'] == 'tak')) ? true : false; ?>

<h2 class="container__header">Edycja zestawu fiszek</h2>

<form method="post" action="">
    
    <input type="text" name="name" value="<?= ((!empty($collection['collection_name'])) ? $collection['collection_name'] : ((!empty($_POST['name'])) ? $_POST['name'] : '')) ?>" placeholder="Nazwa" class="form__input" />
    
    <textarea name="desc" class="form__input" placeholder="Opis"><?= ((!empty($collection['collection_desc'])) ? $collection['collection_desc'] : ((!empty($_POST['desc'])) ? $_POST['desc'] : '')) ?></textarea>
    
    <input type="checkbox" name="is_public" value="yes" id="form__is-public" <?php if ($isPublic) print 'checked'; ?> /> 
    <label for="form__is-public">Zestaw dostępny publicznie</label>
    
    <div class="form__buttons mt10">
        <button name="sent" class="btn btn--blue">Aktualizuj</button>
        <a href="#" onclick="window.history.back();" class="btn btn--gray">Anuluj</a>
    </div>
</form>



