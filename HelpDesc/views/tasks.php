<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Задачи</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
    <style>
        .task-item { border-left: 3px solid #007bff; margin-bottom: 5px; }
        .task-children { margin-left: 30px; }
        .kanban-column { min-height: 500px; background: #f8f9fa; border-radius: 5px; }
        .waterfall-phase { background: white; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .gantt-task { height: 30px; margin: 2px 0; background: #007bff; border-radius: 3px; color: white; padding: 5px; }
        .kanban-column .drag-over {background-color: #e9ecef; border: 2px dashed #007bff; }
        .task-item.dragging {opacity: 0.5; transform: rotate(5deg); }
        .task-item {cursor: grab; transition: all 0.2s ease; }
        .task-item:active {cursor: grabbing;}
        .gantt-chart {background: white; border-radius: 5px; padding: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);}
        .gantt-task-row {border-bottom: 1px solid #eee;  padding: 5px 0;}
        .gantt-timeline-bar {background: #f8f9fa;  border-radius: 3px;}
        .gantt-task-bar {background: linear-gradient(90deg, #007bff, #0056b3); box-shadow: 0 1px 3px rgba(0,0,0,0.2);}
        .gantt-timeline-label {flex: 1; border-left: 1px solid #dee2e6; height: 20px;}
        .drag-over {background-color: #e9ecef; border: 2px dashed #007bff;}
        .dragging {opacity: 0.5;}
    </style>


</head>
<body style="padding-left: 5%;  padding-right: 5%;">
<?php include 'menu.php';?>
<div>
    <img src="/title2.png" class="img-fluid" >
</div>

<div class="mb-3" style="padding-top: 20px;padding-left: 0%;  padding-right: 0%;">
<?php if(isAuthorized()):?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Планировщик проектов</a>
            <div class="navbar-nav">
                <a class="nav-link view-mode" data-mode="day">День</a>
                <a class="nav-link view-mode" data-mode="week">Неделя</a>
                <a class="nav-link view-mode" data-mode="month">Месяц</a>
                <a class="nav-link view-mode" data-mode="kanban">Канбан</a>
                <a class="nav-link view-mode" data-mode="gantt">Гант</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <!-- Выбор проекта -->
        <div class="row mb-3">
            <div class="col-md-6">
                <select id="projectSelect" class="form-select">
                    <option value="">Выберите проект</option>
                </select>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#taskModal">
                    <i class="bi bi-plus"></i> Новая задача
                </button>
            </div>
        </div>

        <!-- Контейнер для различных представлений -->
        <div id="view-container">
            <!-- Здесь будут динамически загружаться различные представления -->
        </div>
    </div>

    <!-- Модальное окно для создания/редактирования задачи -->
    <div class="modal fade" id="taskModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Новая задача</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="taskForm">
                        <input type="hidden" id="taskId">
                        <input type="hidden" id="projectId">
                        <div class="mb-3">
                            <label class="form-label">Название задачи</label>
                            <input type="text" class="form-control" id="taskTitle" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Описание</label>
                            <textarea class="form-control" id="taskDescription" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Дата начала</label>
                                <input type="date" class="form-control" id="taskStartDate">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Дата окончания</label>
                                <input type="date" class="form-control" id="taskEndDate">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" id="saveTask">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales/ru.js"></script>
    <script src="app.js"></script>

    <script>
    ///////////////ПОИСК//////////////////
    //аналог ф-ции в php
    function htmlspecialchars(str) {
        str = str.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#039;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return str;
    }

    </script>
<?php else: ?>

    <div class="alert alert-danger" role="alert">
        Вы не имеете доступа к данной странице - авторизируйтесь
    </div>


<?php endif;?>
</body>
</html>
