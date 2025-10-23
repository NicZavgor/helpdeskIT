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
        .kanban-column {
            min-height: 500px;
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .kanban-card {
            margin-bottom: 15px;
            cursor: move;
            transition: all 0.3s;
        }
        .kanban-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .priority-high {
            border-left: 4px solid #dc3545;
        }
        .priority-medium {
            border-left: 4px solid #ffc107;
        }
        .priority-low {
            border-left: 4px solid #28a745;
        }
        .sidebar {
            height: 100vh;
            position: sticky;
            top: 0;
            background-color: #343a40;
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .stat-card {
            border-radius: 10px;
            padding: 15px;
            color: white;
            margin-bottom: 20px;
        }
    .hover-card:hover {
        background-color: rgba(0, 0, 0, 0.075);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
        transition: all 0.2s ease-in-out;
    }
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
        <div class="col-sm-10">
            <form class="form-group row" action="?page=wiki&pg=1&curpg=1"  method="post" name="Search" id="Search">
                <h3  class="col-sm-3">База знаний</h3>
                <div class="col-sm-8">
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
            <button class="btn btn-primary" value="Создать" data-bs-toggle="modal" data-bs-target="#editModal" onclick="location.href='?page=article&new'">Создать </button>
        </div>
    </div>

    <hr class="border border-primary border-3 opacity-75">

            <div class="col-md-12 p-4">
                    <div id="knowledgebase">
                        <div class="card mb-4">
                            <div class="card-body">
                                <?php for ($iRow = 0; $iRow < $rowCount; $iRow++) : ?>
                                <div class="row">
                                    <?php for ($iCol = 0; $iCol < 3; $iCol++) : ?>
                                        <?php if ($iRow*3+$iCol<$topicCount) :?>
                                            <div class="col-md-4 mb-4"  >
                                                <a href="?page=article&id=<?=$wikiTopics[($iRow*3+$iCol)]['id']?>" class="text-decoration-none">
                                                <div class="card h-100 hover-card" >
                                                    <div class="card-body">
                                                        <h5 class="card-title"><?=$wikiTopics[($iRow*3+$iCol)]['theme']?></h5>
                                                        <p class="card-text"><?=$wikiTopics[($iRow*3+$iCol)]['description']?></p>
                                                        <div class="d-flex justify-content-between">
                                                            <span class="badge bg-info"><?=$wikiTopics[($iRow*3+$iCol)]['idcategory_name']?></span>
                                                            <small class="text-muted">Обновлено <?=(new DateTime($wikiTopics[($iRow*3+$iCol)]['upd_dt']))->format('Y.m.d')?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                                </a>
                                            </div>
                                        <?php endif;?>
                                    <?php endfor; ?>
                                </div>
                                <?php endfor; ?>

                            </div>
                        </div>
                    </div>

            </div>



    <nav aria-label="...">
        <ul class="pagination">
        <?php if($newpg<=2):?>
            <li class="page-item disabled"> <span class="page-link">Предыдущая</span> </li>
        <?php else: ?>
            <li class="page-item"> <a class="page-link" href="?page=wiki&pg=prior&curpg=<?=$newpg?><?=$AddSearchStrURL?>">Предыдущая</a> </li>
        <?php endif;?>


        <?php if($newpg>1):?>
            <li class="page-item"><a class="page-link" href="?page=wiki&pg=<?=$newpg-1?>&curpg=<?=$newpg?><?=$AddSearchStrURL?>"><?=$newpg-1?></a></li>
        <?php endif;?>


        <li class="page-item active">
            <span class="page-link">
                <?=$newpg?>
            </span>
        </li>

        <?php if($newpg<=$pgcount-1):?>
            <li class="page-item"><a class="page-link"  href="?page=wiki&pg=<?=$newpg+1?>&curpg=<?=$newpg?><?=$AddSearchStrURL?>"><?=$newpg+1?></a></li>
        <?php endif;?>

        <?php if($newpg>=$pgcount-2):?>
            <li class="page-item disabled"> <span class="page-link">Следующая</span> </li>
        <?php else: ?>
            <li class="page-item"> <a class="page-link" href="?page=wiki&pg=next&curpg=<?=$newpg?><?=$AddSearchStrURL?>">Следующая</a> </li>
        <?php endif;?>
        </ul>
    </nav>

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
            s = s+"&action=search&str="+htmlspecialchars(document.getElementById('SearchText').value);
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
