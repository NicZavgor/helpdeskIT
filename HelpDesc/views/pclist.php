<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
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
        <div class="col-sm-12">
            <form class="form-group row" action="?page=allpc"  method="post" name="Search" id="Search">
                <h3  class="col-sm-2">Реестр ПК</h3>
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
            <th scope="col">№</th>
            <th scope="col">Имя ПК</th>
            <th scope="col">ОС</th>
            <th scope="col">Процессор</th>
            <th scope="col">ОЗУ</th>
            <th scope="col">Диски</th>
            <th scope="col">Видео</th>
            <th scope="col">Сеть</th>

            <th scope="col"></th>
        </tr></thead>
        <tbody >
        <?php foreach ($AllPCs as $OnePC): ?>
                <th scope="row">
                    <?=$OnePC['idpc']?>
                </th>
                <td>
                    <?=$OnePC['pcname']?>
                </td>
                <td>
                    <?=$OnePC['OS']?>
                </td>
                <td>
                    <?=$OnePC['CPU']?>
                </td>
                <td>
                    <?=$OnePC['RAM']?>
                </td>
                <td>
                    <?=$OnePC['HDD']?>
                </td>
                <td>
                    <?=$OnePC['Video']?>
                </td>
                <td>
                    <?=$OnePC['Net']?>
                </td>
                <td>


                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal"

                        onclick="fillModal(this)"
                        data-Name="<?=$OnePC['pcname']?>"
                        data-OS="<?=$OnePC['full_OS']?>"
                        data-CPU="<?=$OnePC['full_CPU']?>"
                        data-RAM="<?=$OnePC['full_RAM']?>"
                        data-HDD="<?=$OnePC['full_HDD']?>"
                        data-Video="<?=$OnePC['full_Video']?>"
                        data-Net="<?=$OnePC['full_Net']?>">
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
            <li class="page-item"> <a class="page-link" href="?page=allpc&pg=prior&curpg=<?=$newpg?>">Предыдущая</a> </li>
        <?php endif;?>


        <?php if($newpg>1):?>
            <li class="page-item"><a class="page-link" href="?page=allpc&pg=<?=$newpg-1?>&curpg=<?=$newpg?>"><?=$newpg-1?></a></li>
        <?php endif;?>


        <li class="page-item active">
            <span class="page-link">
                <?=$newpg?>
            </span>
        </li>

        <?php if($newpg<=$pgcount-1):?>
            <li class="page-item"><a class="page-link"  href="?page=allpc&pg=<?=$newpg+1?>&curpg=<?=$newpg?>"><?=$newpg+1?></a></li>
        <?php endif;?>

        <?php if($newpg>=$pgcount-2):?>
            <li class="page-item disabled"> <span class="page-link">Следующая</span> </li>
        <?php else: ?>
            <li class="page-item"> <a class="page-link" href="?page=allpc&pg=next&curpg=<?=$newpg?>">Следующая</a> </li>
        <?php endif;?>
        </ul>
    </nav>
    </div>
<?php else: ?>

    <div class="alert alert-danger" role="alert">
        Вы не имеете доступа к данной странице - авторизируйтесь
    </div>


<?php endif;?>

    <!-- Модальное окно -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Информация о ПК</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Имя:</strong> <span id="modalName"></span></p>
                    <p><strong>ОС:</strong> <span id="modalOS"></span></p>
                    <p><strong>Процессор:</strong> <span id="modalCPU"></span></p>
                    <p><strong>ОЗУ:</strong> <span id="modalRAM"></span></p>
                    <p><strong>Диски:</strong> <span id="modalHDD"></span></p>
                    <p><strong>Видео:</strong> <span id="modalVideo"></span></p>
                    <p><strong>Сеть:</strong> <span id="modalNet"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

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

        function fillModal(button) {
            document.getElementById('modalName').textContent = button.getAttribute('data-Name');
            document.getElementById('modalOS').textContent = button.getAttribute('data-OS');
            document.getElementById('modalCPU').textContent = button.getAttribute('data-CPU');
            document.getElementById('modalRAM').textContent = button.getAttribute('data-RAM');
            document.getElementById('modalHDD').textContent = button.getAttribute('data-HDD');
            document.getElementById('modalVideo').textContent = button.getAttribute('data-Video');
            document.getElementById('modalNet').textContent = button.getAttribute('data-Net');

        }
    </script>
</body>
</html>
