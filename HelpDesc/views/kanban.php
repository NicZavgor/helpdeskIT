<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Задачи</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
    <style>
        .kanban-column { min-height: 500px; background: #f8f9fa; border-radius: 5px; }
        .gantt-task { height: 30px; margin: 2px 0; background: #007bff; border-radius: 3px; color: white; padding: 5px; }
        .kanban-column .drag-over {background-color: #e9ecef; border: 2px dashed #007bff; }
        .task-item {  margin-bottom: 5px; }
        .task-children { margin-left: 30px; }
        .task-item.dragging {opacity: 0.5; transform: rotate(5deg); }
        .task-item {cursor: grab; transition: all 0.2s ease; }
        .task-item:active {cursor: grabbing;}
        .drag-over {background-color: #e9ecef; border: 2px dashed #007bff;}
        .dragging {opacity: 0.5;}
        .popover-button { position: absolute; top: 5px; right: 5px; background: none; border: none; color: #6c757d; font-size: 12px; cursor: pointer; }
        .task-item { position: relative; }
    </style>


</head>
<body style="padding-left: 5%;  padding-right: 5%;">
<?php include 'menu.php';?>
<div>
    <img src="/title2.png" class="img-fluid" >
</div>

<div class="mb-3" style="padding-top: 20px;padding-left: 0%;  padding-right: 0%;">
<?php if(isAuthorized()):?>

    <div class="container-fluid">

        <div id="view-container">
            <!-- Здесь будут динамически загружаться различные представления -->
        </div>
    </div>
    <!-- Модальное окно редактирования -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Информация о текущем состоянии</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editknid">
                        <input type="hidden" id="editstatus">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Описание</label>
                            <textarea class="form-control" id="editDesc" rows="4"></textarea>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" onclick="saveState()">Сохранить изменения</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales/ru.js"></script>


<script>
    ///////////////ПОИСК//////////////////
    //аналог ф-ции в php
    function htmlspecialchars(str) {
        str = str.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#039;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return str;
    }
    document.addEventListener('DOMContentLoaded', function() {
        showKanbanView();
        });
        // Заполнение формы данными из строки таблицы
    function fillEditForm(knid, status) {
            document.getElementById('editDesc').value = '';
            if (knid!=null){

                document.getElementById('editknid').value = knid;
                document.getElementById('editstatus').value = status;
            }else{
                document.getElementById('editknd').value = "";
                document.getElementById('editstatus').value = 0;
            }
    }
// Сохранение изменений
    async function saveState() {

        $knid = htmlspecialchars(document.getElementById('editknid').value);
        $status = htmlspecialchars(document.getElementById('editstatus').value);
        $desc = htmlspecialchars(document.getElementById('editDesc').value);
        updateTaskStatus($knid, $status, $desc);
        const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
        modal.hide();
    }
    async function showKanbanView() {
        const tasks = await this.getProjectTasks();
        const html = `
            <div class="row">
                <div class="col-md-2">
                    <div class="kanban-column">
                        <h5 class="p-2 border-bottom bg-dark bg-gradient text-white">На подходе</h5>
                        <div class="p-2" id="column-0">
                            ${this.renderKanbanTasks(tasks, "0")}
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="kanban-column">
                        <h5 class="p-2 border-bottom bg-primary bg-gradient text-white">Новые</h5>
                        <div class="p-2" id="column-1">
                            ${this.renderKanbanTasks(tasks, "1")}
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="kanban-column">
                        <h5 class="p-2 border-bottom bg-secondary bg-gradient text-white">В работе</h5>
                        <div class="p-2" id="column-2">
                            ${this.renderKanbanTasks(tasks, "2")}
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="kanban-column">
                        <h5 class="p-2 border-bottom bg-info bg-gradient text-white">На паузе</h5>
                        <div class="p-2" id="column-3">
                            ${this.renderKanbanTasks(tasks, "3")}
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="kanban-column">
                        <h5 class="p-2 border-bottom bg-warning bg-gradient text-white">На проверке</h5>
                        <div class="p-2" id="column-4">
                            ${this.renderKanbanTasks(tasks, "4")}
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="kanban-column">
                        <h5 class="p-2 border-bottom bg-success bg-gradient text-white">Закрыты</h5>
                        <div class="p-2" id="column-5">
                            ${this.renderKanbanTasks(tasks, "5")}
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('view-container').innerHTML = html;
        this.setupDragAndDrop();
    }

    function setupDragAndDrop() {
        const taskItems = document.querySelectorAll('.task-item');
        const columns = document.querySelectorAll('.kanban-column > div');

        // Настройка перетаскивания для задач
        taskItems.forEach(task => {
            task.addEventListener('dragstart', (e) => {
                e.dataTransfer.setData('text/plain', task.dataset.taskId);
                task.classList.add('dragging');
            });

            task.addEventListener('dragend', (e) => {
                task.classList.remove('dragging');
            });
        });

        // Настройка зон сброса (колонки)
        columns.forEach(column => {
            column.addEventListener('dragover', (e) => {
                e.preventDefault();
                const draggingTask = document.querySelector('.dragging');
                if (draggingTask) {
                    column.classList.add('drag-over');
                }
            });

            column.addEventListener('dragenter', (e) => {
                e.preventDefault();
                column.classList.add('drag-over');
            });

            column.addEventListener('dragleave', (e) => {
                // Проверяем, что мы действительно покидаем колонку, а не переходим на дочерний элемент
                if (!column.contains(e.relatedTarget)) {
                    column.classList.remove('drag-over');
                }
            });

            column.addEventListener('drop', async (e) => {
                e.preventDefault();
                column.classList.remove('drag-over');
                desc = '';
                const taskId = e.dataTransfer.getData('text/plain');
                const newStatus = this.getStatusFromColumn(column.id);
                if (newStatus==5 || newStatus==4){
                    fillEditForm(taskId, newStatus);

                    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                    editModal.show();
                }



                if (newStatus == 0) {return}
                if (taskId && newStatus) {
                    if (newStatus==5){
                        desc = '';
                    }
                    await this.updateTaskStatus(taskId, newStatus, desc);

                    // Перезагружаем данные
                    this.showKanbanView();
                }
            });
        });
    }

    function getStatusFromColumn(columnId) {
        const statusMap = {
            'column-0': '0',
            'column-1': '1',
            'column-2': '2',
            'column-3': '3',
            'column-4': '4',
            'column-5': '5'
        };
        return statusMap[columnId];
    }

    async function updateTaskStatus(taskId, newStatus, desc) {
        try {
           const formData = new FormData();
            formData.append('taskId', taskId);
            formData.append('status', newStatus);
            formData.append('desc', desc);


            const response = await fetch('/?page=kanban&action=updateTaskStatus', {
                method: 'POST',
                body:formData
            });
            const result = await response.json();

            if (!result.success) {
                console.error('Error updating task status:', result.error);
                alert('Ошибка при обновлении статуса задачи');
            }
        } catch (error) {
            console.error('Error updating task status:', error);
            alert('Ошибка при обновлении статуса задачи');
        }
    }


    function renderKanbanTasks(tasks, status) {
        let html = '';

        function renderTask(task) {
            let lclrurgency ='';
            if (task.status === status) {
                switch (task.urgency){
                    case '1': lclrurgency = '#1d8957'; break;
                    case '2': lclrurgency = '#1372fd'; break;
                    case '3': lclrurgency = '#ffc20b'; break;
                    case '4': lclrurgency = '#dc3545'; break;
                    default: lclrurgency = "#007bff";
                }
                let btndesc = `<button type="button" class="btn btn-sm btn-link p-0 border-0" onclick="fillEditForm('${task.knid}', ${status})" data-bs-toggle="modal" data-bs-target="#editModal">
                                                           <i class="bi bi-card-text"></i>
                                                       </button>`;
                if (status<=1) {btndesc='';}
                html += `
                    <div class="task-item p-2 mb-2 bg-white rounded shadow-sm" data-task-id="${task.knid}"  draggable="true" style="border-left: 3px solid `+lclrurgency+`">
                        <div class="task-header">
                            <span class="task-title" style="font-size: 12px; font-weight: bold;"> ${task.title}</span>
                                <button class="popover-button" data-bs-toggle="popover" onclick="showTaskInfo('${task.knid}')" title="Подробнее">
                                    <i class="bi bi-info-circle"></i>
                                 </button>
                        </div>

                        <span style="font-size: 12px; font-weight: bold;"> ${task.idcategory_name}</span>
                        <p> ${task.title}</p>
                        ${btndesc}

                        <button type="button" class="btn btn-sm btn-link p-0 border-0" onclick="location.href='?page=${task.typeobject=="request"? 'request': 'task'}&id=${task.id}&rp=k'">
                           <i class="bi bi-reply"></i>
                        </button>


                    </div>

                `; /*${task.children ? `<small class="text-muted">${task.children.length} подзадач</small>` : ''}*/
            }

            if (task.children) {
                task.children.forEach(renderTask);
            }
        }

        tasks.forEach(renderTask);
        return html;
    }
    async function getProjectTasks() {
        //if (!this.currentProject) return [];

        try {
            const response = await fetch(`/?page=kanban&action=getTasks`);
            return await response.json();
        } catch (error) {
            console.error('Error loading tasks:', error);
            return [];
        }
    }
    async function getTaskInformation(taskId) {
        //if (!this.currentProject) return [];

        try {
            const response = await fetch(`/?page=kanban&action=getTasksInfo&task=${taskId}`);
            return await response.json();
        } catch (error) {
            console.error('Error loading tasks:', error);
            return [];
        }
    }


    function dtStrToFmtStr(dtStr){
        const dateObject = new Date(dtStr);
        return new Intl.DateTimeFormat('ru-RU', {
                                 year: 'numeric',
                                 month: '2-digit',
                                 day: '2-digit'
                             }).format(dateObject);
    }
    // Функция для показа информации о задаче
    async function showTaskInfo(taskId) {
        // Находим элемент задачи
        const taskElement = document.querySelector(`[data-task-id="${taskId}"]`);
        const taskInfo = await getTaskInformation(taskId);
        // Получаем данные из задачи
        const category = taskElement.querySelector('span').textContent.trim();
        const description = taskElement.querySelector('p').textContent.trim();
        urgencyColor="";
        switch (taskInfo[0].urgency){
            case "1": urgencyColor= " bg-success bg-gradient " ;break;
            case "2": urgencyColor= " bg-primary bg-gradient " ;break;
            case "3": urgencyColor= " bg-warning bg-gradient " ;break;
            case "4": urgencyColor= " bg-danger bg-gradient " ;break;
        }
        // Создаем содержимое для popover
        const popoverContent = `
            <div class="popover-content">
                <span class="badge ${urgencyColor} text-white">${taskInfo[0].urgency_name}</span>
                <strong>ID:</strong> #${taskInfo[0].id}<br>
                <strong>Категория:</strong> ${category}<br>
                <strong>Имя:</strong> ${taskInfo[0].title}<br>
                <strong>Срок:</strong> ${dtStrToFmtStr(taskInfo[0].deadline)}<br>
                <strong>Описание:</strong> <small>${taskInfo[0].main_description}</small><br>

                <strong>Последнее действие:</strong> ${dtStrToFmtStr(taskInfo[0].dt)}<br>
                <strong>Последняя отметка:</strong> <small>${taskInfo[0].description_proc}</small><br>

                <small class="text-muted">Нажмите для закрытия</small>
            </div>
        `;

        // Создаем и показываем popover
        const popover = new bootstrap.Popover(taskElement, {
            title:  taskInfo[0].typeobject=="request"? 'Информация по заявке': 'Информация по задаче',
            content: popoverContent,
            html: true,
            trigger: 'manual'
        });

        popover.show();

        // Закрываем popover при клике на него
        setTimeout(() => {
            document.addEventListener('click', function closePopover(e) {
                if (!taskElement.contains(e.target)) {
                    popover.hide();
                    document.removeEventListener('click', closePopover);
                }
            });
        }, 100);
    }

    // Функция для определения статуса задачи по колонке
    function getTaskStatus(taskElement) {
        const column = taskElement.closest('.kanban-column');
        if (!column) return 'Неизвестно';

        const columnTitle = column.querySelector('h5').textContent.trim();
        return columnTitle;
    }


</script>

<?php else: ?>

    <div class="alert alert-danger" role="alert">
        Вы не имеете доступа к данной странице - авторизируйтесь
    </div>


<?php endif;?>
</body>
</html>
