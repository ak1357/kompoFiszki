<?php $word = self::getWord(); ?>

<h2 class="container__header">Usuwanie słowa</h2>

<div class="alert alert--orange mb10"><b>Uwaga:</b> Potwierdź usunięcie wyrazu</div>

<form method="post" action="" class="form">
    <input type="hidden" name="collection" value="<?= (!empty($word['collection_id'])) ? (int) $word['collection_id'] : 0; ?> ">
    <button name="sent" class="btn btn--blue">Potwierdzam</button>
    <a href="#" onclick="window.history.back();" class="btn btn--gray">Anuluj</a>
</form>