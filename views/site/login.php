 <h2>Авторизация</h2>
 <h3><?= $message ?? ''; ?></h3>

<?php if (!app()->auth::check()): ?>
    <form method="post" class="loginForm">
        <?php $view = new Src\View(); ?>
        <?= $view->generateCsrfField() ?>
        <input class="inputLogin" type="text" name="login" placeholder="Введите ваш логин">
        <input class="inputLogin" type="password" name="password" placeholder="Введите ваш пароль">
        <button class="btnLogin">Вход</button>
    </form>
<?php endif; ?>