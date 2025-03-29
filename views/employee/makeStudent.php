<div class="mainContForFormAndTitles wraper">
    <div class="contForTitleForm">
        <p class="titleMakeStudent">Создание студента</p>
    </div>
    <div class="contForFormMakeStudent">
        <form class="MakeStudent" method="post">
            <div class="classForSettingForm">
                <label for="surname">Фамилия</label>
                <input class="inputForm" type="text" id="surname" name="surname" placeholder="Введите фамилию">
            </div>
            <div class="classForSettingForm">
                <label for="name">Имя</label>
                <input class="inputForm" type="text" id="name" name="name" placeholder="Введите имя">
            </div>
            <div class="classForSettingForm">
                <label for="thirdname">Отчество</label>
                <input class="inputForm" type="text" id="thirdname" name="thirdname" placeholder="Введите Отчество">
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
                <input class="inputForm" type="date" id="dateBirthday" name="dateBirthday">
            </div>
            <div class="classForSettingForm">
                <label for="address">Введите адрес проживания</label>
                <input class="inputForm" type="text" id="address" name="address" placeholder="Введите адрес проживания">
            </div>
            <div class="classForSettingForm">
                <label for="group">Выберите группу:</label>
                <select class="inputForm" name="group" id="group">
                    <?php foreach ($groups as $group): ?>
                        <option value="<?= $group->id ?>"><?= $group->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <button type="submit">Создать</button>
            </div>
        </form>
    </div>
</div>