<?php
if (self::isAdmin() && !empty($uriParams[1])) {
    $tmpUserId = $this->userId;
    $this->userId = $uriParams[1];
    $user = self::getUser();
    $this->userId = $tmpUserId;
} else
    $user = self::getUser();
?>

<h2 class="container__header">Edycja konta</h2>

<form method="post" action="" class="form">
   
    <input type="text" name="login" value="<?= (!empty($_POST['login']) ? $_POST['login'] : ((!empty($user['user_login'])) ? $user['user_login'] : '')); ?>" title="Login" placeholder="Login" class="form__input" />
    <input type="password" name="password_c" value="<?= (!empty($_POST['password_c']) ? $_POST['password_c'] : '') ?>" title="Stare hasło" placeholder="Stare hasło" class="form__input" />
    <input type="password" name="password_n" value="<?= (!empty($_POST['password_n']) ? $_POST['password_n'] : '') ?>" title="Nowe hasło" placeholder="Nowe hasło" class="form__input" />
    <input type="password" name="password_r" value="<?= (!empty($_POST['password_r']) ? $_POST['password_r'] : '') ?>" title="Powtórz nowe hasło" placeholder="Powtórz nowe hasło" class="form__input" />
    
    <em>* Zostaw hasło puste, jeśli nie chcesz go zmieniać</em>
    
    <div class="form__buttons mt10">
        <button name="sent" class="btn btn--blue">Aktualizuj</button>
        <a href="#" onclick="window.history.back();" class="btn btn--gray">Anuluj</a>
        <a href="<?= $context; ?>/user/delete<?= (!empty($uriParams[1])) ? '/' . $uriParams[1] : '' ; ?>" class="ml10">Usuń konto</a> 
    </div>
    
</form>


