<div class="contForLoginUser">
    <div class="backgroundForLogin">
        <div class="loginForm register">
            <h2>Регистрация нового сотрудника</h2>
            <h3><?= $message ?? ''; ?></h3>
            <form method="post">
                <input class="inputLogin" type="text" name="name" placeholder="Введите имя" required>
                <input class="inputLogin" type="text" name="login" placeholder="Введите логин" required>
                <input class="inputLogin" type="password" name="password" placeholder="Введите пароль (мин. 6 символов)" required minlength="6">
                <button type="submit" class="btnLogin">Зарегистрировать</button>
            </form>
        </div>
    </div>
</div>