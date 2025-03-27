<h2>Регистрация нового пользователя</h2>
<h3><?= $message ?? ''; ?></h3>
<form method="post"  action="<?= app()->route->getUrl('/signup') ?>">
    <label>Имя <input type="text" name="name"></label>
    <label>Логин <input type="text" name="login"></label>
    <label>Пароль <input type="password" name="password"></label>
    <button>Зарегистрироваться</button>
</form>
