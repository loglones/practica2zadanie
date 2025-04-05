<div class="mainContForFormAndTitles wraper">
    <div class="contForTitleForm">
        <p class="titleMakeStudent">Создание студента</p>
    </div>
    <div class="contForFormMakeStudent">
        <form class="MakeStudent" method="post">
            <?php $view = new Src\View(); ?>
            <?= $view->generateCsrfField() ?>
            <div class="classForSettingForm">
                <label for="surname">Фамилия</label>
                <input class="inputForm" type="text" id="surname" name="surname" placeholder="Введите фамилию"
                       value="<?= htmlspecialchars($old['surname'] ?? '') ?>"
                       pattern="[А-Яа-яЁё\s\-]{2,50}"
                       title="Только русские буквы, дефисы и пробелы">
                <?php if (isset($errors['surname'])): ?>
                    <div class="error"><?= implode('<br>', $errors['surname']) ?></div>
                <?php endif; ?>
            </div>
            <div class="classForSettingForm">
                <label for="name">Имя</label>
                <input class="inputForm" type="text" id="name" name="name" placeholder="Введите имя"
                       value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                       pattern="[А-Яа-яЁё\s\-]{2,50}"
                       title="Только русские буквы, дефисы и пробелы">
                <?php if (isset($errors['name'])): ?>
                    <div class="error"><?= implode('<br>', $errors['name']) ?></div>
                <?php endif; ?>
            </div>
            <div class="classForSettingForm">
                <label for="patronymic">Отчество</label>
                <input class="inputForm" type="text" id="patronymic" name="patronymic" placeholder="Введите Отчество"
                       value="<?= htmlspecialchars($old['patronymic'] ?? '') ?>"
                       pattern="[А-Яа-яЁё\s\-]{2,50}"
                       title="Только русские буквы, дефисы и пробелы">
                <?php if (isset($errors['patronymic'])): ?>
                    <div class="error"><?= implode('<br>', $errors['patronymic']) ?></div>
                <?php endif; ?>
            </div>
            <div class="classForSettingForm">
                <label for="gender">Выберите пол:</label>
                <select class="inputForm" name="gender" id="gender">
                    <option value="male" selected>Мужчина</option>
                    <option value="female">Женщина</option>
                </select>
            </div>
            <div class="classForSettingForm">
                <label for="dateBirthday">Введите дату рождения</label>
                <input class="inputForm" type="date" id="dateBirthday" name="date_birthday">
            </div>
            <div class="classForSettingForm">
                <label for="address">Введите адрес проживания</label>
                <input class="inputForm" type="text" id="address" name="address" placeholder="Введите адрес проживания">
            </div>
            <div class="classForSettingForm">
                <label for="group_id">Выберите группу:</label>
                <select class="inputForm" name="group_id" id="group_id" required>
                    <?php foreach ($groups as $group): ?>
                        <option value="<?= htmlspecialchars($group->id) ?>"
                            <?= (isset($old['group_id']) && $old['group_id'] == $group->id ? 'selected' : '' )?>>
                            <?= htmlspecialchars($group->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['group_id'])): ?>
                    <div class="error"><?= implode('<br>', $errors['group_id']) ?></div>
                <?php endif; ?>
            </div>
            <div>
                <button type="submit">Создать</button>
            </div>
        </form>
    </div>
</div>