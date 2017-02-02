<?php global $context; ?>

<h2 class="container__header">Logowanie</h2>


<?php if (in_array('loginFailed', self::getErrors())) { ?>
<div class="alert alert--red">
    <b>Uwaga:</b> Logowanie nie powiodło się
</div>    
<?php } ?>

<form method="post" action="" class="form">
    
    <input type="text" name="login" placeholder="Login" class="form__input" />
    <input type="password" name="password" placeholder="Hasło" class="form__input" />
    
    <div class="form__buttons">
        <button name="sent" class="btn btn--blue">Zaloguj</button>
        <a href="<?= $context; ?>/user/register" class="form__register-user">Chcę założyć konto</a>
    </div>
    
</form>

<style>
    .container {
        width: 300px;
    }
</style>