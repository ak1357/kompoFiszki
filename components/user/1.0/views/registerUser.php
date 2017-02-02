<h2 class="container__header">Rejestracja konta</h2>

<form method="post" action="" class="form">
   
    <input type="text" name="login" value="<?= (!empty($_POST['login']) ? $_POST['login'] : '') ?>" placeholder="Login" class="form__input" />
    <input type="password" name="password" value="<?= (!empty($_POST['password']) ? $_POST['password'] : '') ?>" placeholder="Hasło" class="form__input" />
    <input type="password" name="password_r" value="<?= (!empty($_POST['password_r']) ? $_POST['password_r'] : '') ?>" placeholder="Powtórz hasło" class="form__input" />
    
    <div class="form__buttons">
        <button name="sent" class="btn btn--blue">Załóż konto</button>
    </div>
</form>

<style>
    .container {
        width: 300px;
    }
</style>