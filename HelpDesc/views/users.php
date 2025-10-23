<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

</head>
<body style="padding-left: 5%;  padding-right: 5%;">
<?php include 'menu.php';?>
<div>
    <img src="/title2.png" class="img-fluid" >
</div>

<div class="mb-3" style="padding-top: 20px;padding-left: 10%;  padding-right: 10%;">
    <?php if(isAuthorized()):?>
    <div class="row g-7">
        <div class="col-sm-10">
            <form class="form-group row" action="?page=users"  method="post" name="Search" id="Search">
                <h3  class="col-sm-2">Пользователи</h3>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="SearchText" placeholder="Введите строку поиска"
                    <?php if(isset($searchStr)):?>
                        value="<?=$searchStr?>"
                    <?php endif;?>
                    >
                </div>
                <div class="col-sm-1">
                    <input type="submit" name="ok" class="btn btn-outline-secondary" value="Найти" onclick="doSearch(); return false;">
                </div>
            </form>
        </div>
        <div class="col-sm-2">
            <button class="btn btn-primary" value="Создать" data-bs-toggle="modal" data-bs-target="#editModal" onclick="fillEditForm(null)">Создать </button>
        </div>
    </div>

    <hr class="border border-primary border-3 opacity-75">

    <table  class="table" >
        <thead ><tr>

            <th scope="col">Имя</th>
            <th scope="col">Роль</th>

            <th scope="col"></th>
        </tr></thead>
        <tbody >
        <?php foreach ($users as $One): ?>
            <tr  id="row-<?= $One['id'] ?>">
                <td>
                    <?=$One['name']?>
                </td>
                <td>
                    <?=$One['itrole_name']?>
                </td>
                <td>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal"
                                id="btn-<?= $One['id'] ?>"
                                onclick="fillEditForm(this)"
                                data-id="<?=$One['id']?>"
                                data-name="<?=$One['name']?>"
                                data-itrole="<?=$One['itrole']?>"
                                data-itrole_name="<?=$One['itrole_name']?>" >
                            ...
                    </button>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#exampleModal<?=$One['id']?>">
                    Х
                    </button>
                    <!-- Модальное окно -->
                    <div class="modal fade" id="exampleModal<?=$One['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel<?=$One['id']?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel<?=$One['id']?>">Запрос на удаление</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                                </div>
                                <div class="modal-body">
                                    Вы действительно хотите удалить пользователя - <?=$One['name']?> ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                    <button type="button" class="btn btn-primary" id="del_<?=$One['id']?>" onclick="deleteOne(<?=$One['id']?>)" >Удалить</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <nav aria-label="...">
        <ul class="pagination">
        <?php if($newpg<=2):?>
            <li class="page-item disabled"> <span class="page-link">Предыдущая</span> </li>
        <?php else: ?>
            <li class="page-item"> <a class="page-link" href="?page=users&pg=prior&curpg=<?=$newpg?>">Предыдущая</a> </li>
        <?php endif;?>


        <?php if($newpg>1):?>
            <li class="page-item"><a class="page-link" href="?page=users&pg=<?=$newpg-1?>&curpg=<?=$newpg?>"><?=$newpg-1?></a></li>
        <?php endif;?>


        <li class="page-item active">
            <span class="page-link">
                <?=$newpg?>
            </span>
        </li>

        <?php if($newpg<=$pgcount-1):?>
            <li class="page-item"><a class="page-link"  href="?page=users&pg=<?=$newpg+1?>&curpg=<?=$newpg?>"><?=$newpg+1?></a></li>
        <?php endif;?>

        <?php if($newpg>=$pgcount-2):?>
            <li class="page-item disabled"> <span class="page-link">Следующая</span> </li>
        <?php else: ?>
            <li class="page-item"> <a class="page-link" href="?page=users&pg=next&curpg=<?=$newpg?>">Следующая</a> </li>
        <?php endif;?>
        </ul>
    </nav>
    <!-- Модальное окно редактирования -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Информация о пользователе</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editId">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Имя пользователя</label>
                            <input type="text" class="form-control" id="editName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editItRole" class="form-label">Роль</label>
                            <select class="form-select form-select-sm status-select"  id="editItRole">
                                <?php foreach ($allroles as $one): ?>
                                <option value="<?= $one['role'] ?>">
                                   <?= $one['name'] ?>
                                </option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                            <!-- Смена пароля -->
                            <div class="mb-3">
                                <label class="form-label">Смена пароля</label>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="changePassword">
                                    <label class="form-check-label" for="changePassword">Изменить пароль</label>
                                </div>
                            </div>

                            <div id="passwordFields" style="display: none;">

                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Новый пароль</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password">
                                </div>

                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Подтвердите пароль</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                </div>
                            </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" onclick="saveChanges()">Сохранить изменения</button>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>

    <div class="alert alert-danger" role="alert">
        Вы не имеете доступа к данной странице - авторизируйтесь
    </div>


<?php endif;?>

    <script>

        // Показать/скрыть поля для смены пароля
        document.getElementById('changePassword').addEventListener('change', function() {
            const passwordFields = document.getElementById('passwordFields');
            passwordFields.style.display = this.checked ? 'block' : 'none';

            // Сделать поля обязательными при включении
            const passwordInputs = passwordFields.querySelectorAll('input');
            passwordInputs.forEach(input => {
                input.required = this.checked;
            });
        });


    ///////////////ПОИСК//////////////////
    //аналог ф-ции в php
    function htmlspecialchars(str) {
        str = str.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#039;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return str;
    }
    //поиск
    function doSearch(){
        if (htmlspecialchars(document.getElementById('SearchText').value) != "") {
            var s = document.getElementById('Search').action;
            s = s+"&pg=1&curpg=1&action=search&str="+htmlspecialchars(document.getElementById('SearchText').value);
            document.getElementById('Search').action=s;
        }
        document.getElementById("Search").submit();
    }

    // Заполнение формы данными из строки таблицы
    function fillEditForm(button) {
            if (button!=null){
                const rowData = button.parentNode.parentNode;

                document.getElementById('editId').value = button.getAttribute('data-id');
                document.getElementById('editName').value = button.getAttribute('data-name');
                document.getElementById('editItRole').value = button.getAttribute('data-itrole');
                document.getElementById('new_password').value = '';
                document.getElementById('confirm_password').value = '';
                document.getElementById('changePassword').checked = false;
            const passwordFields = document.getElementById('passwordFields');
            passwordFields.style.display = 'none';

            }else{
                document.getElementById('editId').value = 0;
                document.getElementById('editName').value = '';
                document.getElementById('editItRole').value = '';
                document.getElementById('new_password').value = '';
                document.getElementById('confirm_password').value = '';
                document.getElementById('changePassword').checked = true;
            const passwordFields = document.getElementById('passwordFields');
            passwordFields.style.display = 'block';

            }
    }
    async function deleteOne($id) {
        try {
             const response = await fetch('?page=users&api&action=delete', {
                                 method: 'POST',
                                 body: JSON.stringify({id: $id})
                             });
            console.log(response);
            const result = await response.json();
            console.log(result);

            if (result.success===true) {
                document.getElementById("row-" + $id).remove();
                location.reload();
            } else {
                alert('Ошибка: ' + result.message);
            }
        } catch (error) {
            console.error('Ошибка:', error);
            alert('Произошла ошибка при удалении данных');
        }
    }


    // Сохранение изменений
    async function saveChanges() {

        $id = document.getElementById('editId').value;
        $name = htmlspecialchars(document.getElementById('editName').value);
        $ItRole = htmlspecialchars(document.getElementById('editItRole').value);
        $ItRole_name = htmlspecialchars(document.getElementById('editItRole').selectedOptions[0].text);
        //$pass = htmlspecialchars(document.getElementById('pass').value);

            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const changePassword = document.getElementById('changePassword').checked;

            if (changePassword && newPassword !== confirmPassword) {

                alert('Пароли не совпадают!');
                return false;
            }

            if (changePassword && newPassword.length < 6) {

                alert('Пароль должен содержать минимум 6 символов!');
                return false;
            }

        try {
             const response = await fetch('?page=users&api&action=edit', {
                                 method: 'POST',
                                 body: JSON.stringify({id: $id,
                                    name: $name,
                                    itrole: $ItRole,
                                    itrole_name: $ItRole_name,
                                    changePassword: changePassword,
                                    pass: newPassword
                                 })
                             });
            console.log(response);

            const result = await response.json();
            console.log(result);

            if (result.success===true) {
                const row = document.getElementById('row-' + result.id);
                if (row) {
                    row.cells[0].textContent = result.name;
                    row.cells[1].textContent = $ItRole_name;
                }
                const btn = document.getElementById('btn-' + result.id);
                if (btn) {
                    btn.dataset.name = result.name;
                    btn.dataset.itrole = result.itrole;
                    btn.dataset.itrole_name = $ItRole_name;
                }

                // Закрываем
                const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                modal.hide();
                if (result.isNew ===true){
                    location.reload();
                }
                alert('Данные успешно сохранены!');
            } else {
                alert('Ошибка: ' + result.message);
            }
        } catch (error) {
            console.error('Ошибка:', error);
            alert('Произошла ошибка при сохранении данных');
        }
    }
    </script>
</body>
</html>
