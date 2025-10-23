<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .tree ul {
            padding-left: 20px;
            list-style-type: none;
        }
        .tree li {
            margin: 5px 0;
            position: relative;
        }
        .tree li:before {
            content: "";
            position: absolute;
            top: -5px;
            left: -15px;
            border-left: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            width: 15px;
            height: 15px;
        }
        .tree li:after {
            position: absolute;
            content: "";
            top: 10px;
            left: -15px;
            border-left: 1px solid #ccc;
            width: 15px;
            height: 100%;
        }
        .tree li:last-child:after {
            display: none;
        }
        .tree li a {
            display: block;
            padding: 5px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #93c0ff;
            text-decoration: none;
            color: #333;
        }
        .tree li a:hover {
            background: #1372fd;
        }
    </style>

</head>
<body style="padding-left: 5%;  padding-right: 5%;">
<?php include 'menu.php';?>
<div>
    <img src="/title2.png" class="img-fluid" >
</div>

<?php if(isAuthorized()):?>

    <div class="container mt-4">
        <h1 class="mb-4">Организационная структура</h1>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-0">Дерево подразделений</h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <button class="btn btn-sm btn-primary" id="refreshBtn">
                            <i class="fas fa-sync-alt"></i> Обновить
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="loadingSpinner" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Загрузка...</span>
                    </div>
                </div>
                <div id="treeContainer" class="tree"></div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS и зависимости -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery для AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Загрузка дерева при загрузке страницы
            loadDepartmentsTree();

            // Обработчик кнопки обновления
            $('#refreshBtn').click(function() {
                loadDepartmentsTree();
            });
        });

        function loadDepartmentsTree() {
            $('#treeContainer').hide();
            $('#loadingSpinner').show();

            $.ajax({
                url: './models/get_subdivisions.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#loadingSpinner').hide();
                    $('#treeContainer').html(renderTree(data));
                    $('#treeContainer').show();
                },
                error: function(xhr, status, error) {
                    $('#loadingSpinner').hide();
                    $('#treeContainer').html('<div class="alert alert-danger">Ошибка загрузки данных: ' + error + '</div>');
                    $('#treeContainer').show();
                }
            });
        }

        function renderTree(data, parentId = null) {
            let treeHtml = '<ul>';
            const children = data.filter(item => item.parent_id == parentId);

            children.forEach(item => {
                treeHtml += `<li>
                    <a href="#" data-id="${item.id}">

                        <i class="fa-solid fa-users"></i>
                        ${item.name}

                    </a>`;

                // Рекурсивно отображаем дочерние элементы
                const hasChildren = data.some(child => child.parent_id == item.id);
                if (hasChildren) {
                    treeHtml += renderTree(data, item.id);
                }

                treeHtml += '</li>';
            });

            treeHtml += '</ul>';
            return treeHtml;
        }
    </script>


<?php else: ?>

    <div class="alert alert-danger" role="alert">
        Вы не имеете доступа к данной странице - авторизируйтесь
    </div>


<?php endif;?>

</body>
</html>
