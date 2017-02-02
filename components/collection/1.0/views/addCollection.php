<h2 class="container__header">Dodawanie zestawu fiszek</h2>

<form method="post" action="" class="form">
    
    <input type="text" name="name" value="<?= (!empty($_POST['name']) ? $_POST['name'] : '') ?>" placeholder="Nazwa" class="form__input" />
    
    <textarea name="desc" class="form__input" placeholder="Opis"><?= (!empty($_POST['desc']) ? $_POST['desc'] : '') ?></textarea>
    
    <input type="checkbox" name="is_public" value="yes" id="form__is-public" /> 
    <label for="form__is-public">Zestaw dostępny publicznie</label>
        
    <div class="form__buttons mt10">
        <button name="sent" class="btn btn--blue">Dodaj</button>
        <a href="#" onclick="window.history.back();" class="btn btn--gray">Anuluj</a>
    </div>
</form>