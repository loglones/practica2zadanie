<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?? 'Pop it MVC' ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<header class="contForBackgroundPhoto">
    <div class="wraper firstBlockOfHeader">
        <div class="contForButtonLogin">
            <?php if (app()->auth::check()): ?>
                <button class="headerButtonLogin" onclick="window.location.href='<?= app()->route->getUrl('/logout') ?>'">Выход</button>
            <?php else: ?>
                <button class="headerButtonLogin" onclick="window.location.href='<?= app()->route->getUrl('/login') ?>'">Вход</button>
            <?php endif; ?>
        </div>
        <div class="mainButtonsForSearchAndMakeStudents">
            <div class="upperRowForButtons">
                <div class="contForButtonMakeStudents">
                    <button class="headerButton" onclick="window.location.href='<?= app()->route->getUrl('/makeStudents') ?>'">Создать студента</button>
                </div>
                <div class="contForButtonMakeStudents">
                    <button class="headerButton" onclick="window.location.href='<?= app()->route->getUrl('/makeGroup') ?>'">Создание группы</button>
                </div>
                <div class="contForButtonMakeStudents">
                    <button class="headerButton" onclick="window.location.href='<?= app()->route->getUrl('/makeDisciplene') ?>'">Создание дисциплины</button>
                </div>
            </div>
            <div class="lowerRowForButtons">
                <div class="contForButtonCheckMarkStudent">
                    <button class="headerButton" onclick="window.location.href='<?= app()->route->getUrl('/checkMarkForDiscipleneAndHours') ?>'">Выбрать успеваемость студента</button>
                </div>
                <div class="contForButtonCheckMarkGroupOrDisciplene">
                    <button class="headerButton" onclick="window.location.href='<?= app()->route->getUrl('/checkStudentForGroupAndDisciplene') ?>'">Выбрать успеваемость по группам</button>
                </div>
                <div class="contForButtonMakeGroup">
                    <button class="headerButton" onclick="window.location.href='<?= app()->route->getUrl('/checkDisciplene') ?>'">Выбрать дисциплину</button>
                </div>
            </div>
        </div>
        <div class="contForLogo">
            <a href="<?= app()->route->getUrl('/') ?>"><img class="logo" src="/image/graduation.png" alt="Логотип"></a>
        </div>
    </div>
</header>
<main>
    <?= $content ?? '' ?>
</main>
</body>
</html>