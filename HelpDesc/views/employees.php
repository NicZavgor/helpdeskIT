<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
         .activity-item {
                    border: 1px solid #dee2e6;
                    border-radius: 0.375rem;
                    margin-bottom: 0.5rem;
                    transition: all 0.2s ease;
                    cursor: pointer;
                }

                .activity-item:hover {
                    background-color: #f8f9fa;
                    border-color: #0d6efd;
                    transform: translateY(-1px);
                    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
                }

                .activity-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 0.5rem;
                }

                .request-number {
                    font-weight: 600;
                    color: #0d6efd;
                }

                .status {
                    padding: 0.25rem 0.5rem;
                    border-radius: 0.25rem;
                    font-size: 0.875rem;
                    font-weight: 500;
                }

                .status-new {
                    background-color: #d1e7dd;
                    color: #0f5132;
                }

                .status-in-progress {
                    background-color: #fff3cd;
                    color: #664d03;
                }

                .status-completed {
                    background-color: #d1e7dd;
                    color: #0f5132;
                }

                .time {
                    color: #6c757d;
                    font-size: 0.875rem;
                }

                .request-name {
                    color: #212529;
                    font-weight: 500;
                }
    </style>
</head>
<body style="padding-left: 5%;  padding-right: 5%;">
<?php include 'menu.php';?>
<div>
    <img src="/title2.png" class="img-fluid" >
</div>

<div class="mb-3" style="padding-top: 20px;padding-left: 10%;  padding-right: 10%;">
<?php if(isAuthorized()):?>
    <div class="row g-7">
        <div class="col-sm-12">
            <form class="form-group row" action="?page=employees"  method="post" name="Search" id="Search">
                <h3  class="col-sm-2">Сотрудники</h3>
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
    </div>




    <hr class="border border-primary border-3 opacity-75">
    <table  class="table" >
        <thead ><tr>

            <th scope="col">ФИО</th>
            <th scope="col">Должность</th>
            <th scope="col">Отдел</th>
            <th scope="col">Учетная запись</th>

            <th scope="col"></th>
        </tr></thead>
        <tbody >
        <?php foreach ($AllEmployees as $OneEmployee): ?>
                <td>
                    <?=$OneEmployee['employee_name']?>
                </td>
                <td>
                    <?=$OneEmployee['job_name']?>
                </td>
                <td>
                    <?=$OneEmployee['subdivision_name']?>
                </td>
                <td>
                    <?=$OneEmployee['account']?>
                </td>
                <td>
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal"
                                onclick="fillEditForm(this)"
                                data-id="<?=$OneEmployee['guidemployee']?>"
                                data-name="<?=$OneEmployee['employee_name']?>"
                                data-job="<?=$OneEmployee['job_name']?>"
                                data-subdivision="<?=$OneEmployee['subdivision_name']?>"
                                data-account="<?=$OneEmployee['account']?>">
                            ...
                        </button>
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
            <li class="page-item"> <a class="page-link" href="?page=employees&pg=prior&curpg=<?=$newpg?>">Предыдущая</a> </li>
        <?php endif;?>


        <?php if($newpg>1):?>
            <li class="page-item"><a class="page-link" href="?page=employees&pg=<?=$newpg-1?>&curpg=<?=$newpg?>"><?=$newpg-1?></a></li>
        <?php endif;?>


        <li class="page-item active">
            <span class="page-link">
                <?=$newpg?>
            </span>
        </li>

        <?php if($newpg<=$pgcount-1):?>
            <li class="page-item"><a class="page-link"  href="?page=employees&pg=<?=$newpg+1?>&curpg=<?=$newpg?>"><?=$newpg+1?></a></li>
        <?php endif;?>

        <?php if($newpg>=$pgcount-2):?>
            <li class="page-item disabled"> <span class="page-link">Следующая</span> </li>
        <?php else: ?>
            <li class="page-item"> <a class="page-link" href="?page=employees&pg=next&curpg=<?=$newpg?>">Следующая</a> </li>
        <?php endif;?>
        </ul>
    </nav>
    <!-- Модальное окно редактирования -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Информация о сотруднике</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editId">
                        <p><strong>ФИО:</strong> <span id="modalName"></span></p>
                        <p><strong>Должность:</strong> <span id="modalJob"></span></p>
                        <p><strong>Подразделение:</strong> <span id="modalSubdivision"></span></p>

                        <div class="mb-3">
                            <label for="editAccount" class="form-label">Учетная запись</label>
                            <input type="email" class="form-control" id="editAccount" required>
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

    </div>
<?php else: ?>

    <div class="alert alert-danger" role="alert">
        Вы не имеете доступа к данной странице - авторизируйтесь
    </div>


<?php endif;?>

    <script>
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
    ///////////////////////////////////





        // Заполнение формы данными из строки таблицы
        function fillEditForm(button) {
            const rowData = button.parentNode.parentNode;

            document.getElementById('editId').value = button.getAttribute('data-id');
            document.getElementById('modalName').textContent = button.getAttribute('data-name');
            document.getElementById('modalJob').textContent = button.getAttribute('data-job');
            document.getElementById('modalSubdivision').textContent = button.getAttribute('data-Subdivision');
            document.getElementById('editAccount').value = button.getAttribute('data-account');
        }


        // Сохранение изменений
        function saveChanges() {
            const id = document.getElementById('editId').value;
            const account = document.getElementById('editAccount').value;

            // Находим кнопку в строке с соответствующим ID
            const buttons = document.querySelectorAll(`button[data-id="${id}"]`);
            if (buttons.length > 0) {
                const button = buttons[0];
                const row = button.parentNode.parentNode;

                // Обновляем данные в таблице
                row.cells[3].textContent = account;

                // Обновляем data-атрибуты кнопки
                button.setAttribute('data-account', account);

                // Закрываем модальное окно
                const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                modal.hide();
                //!!!! отправить в БД данные
                // Здесь можно добавить AJAX-запрос для сохранения на сервере
                console.log('Данные сохранены:', {id, account});
            }
        }
    </script>
</body>
</html>
