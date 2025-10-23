<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
            .compact-table td, .compact-table th {
                padding-top: 0.0rem;
                padding-bottom: 0.0rem;
            }
            .compact-table thead th {
                border-bottom-width: 1px;
            }
                .modal-table-container {
                    max-height: 400px;
                    overflow-y: auto;
                }

                .table-fixed {
                    table-layout: fixed;
                }

                .loading-spinner {
                    display: none;
                    text-align: center;
                    padding: 20px;
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

                .project-number {
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

                .project-name {
                    color: #212529;
                    font-weight: 500;
                }

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
    .card {
                border: 1px solid #dee2e6;
                border-radius: 0.375rem;
                box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
                margin-bottom: 1rem;
            }
            .card-body {
                padding: 1rem;
            }
            .date-container {
                display: flex;
                gap: 1rem;
            }
            .date-card {
                flex: 1;
            }
            .modal-body {
                padding: 1rem;
            }
            .badge {
                font-size: 0.875em;
            }

    </style>
</head>
<body style="padding-left: 5%;  padding-right: 5%;">
<?php include 'menu.php';?>
<div>
    <img src="/title2.png" class="img-fluid" >
</div>
<?php if(isAuthorized()):?>
    <form >
        <p style="color: red" id="message"></p>
        <?php if(!empty($message)):?>
            <h5><?=$message?></h5>
        <?php else: ?>
            <h5></h5>
        <?php endif;?>
            <div class="container">
                  <div class="row">
                      <div class="col-12">
                          <div class="container">
                              <div class="row bg-primary bg-gradient text-white  p-1 rounded" >
                                <div class="col-11 p-2">
                                  <div class="col" id="projectId" projectId="<?=$project[0]['id']?>">
                                      Данные по проекту №<?=$project[0]['id']?>
                                  </div>
                                </div>
                                <div class="col-1">
                                    <a href="?page=projectedit&action=edit&id=<?=$project[0]['id']?>" class="btn btn-outline-light">
                                         <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                              </div>
                          </div>
                          <div class="container">
                            <div class="row">
                              <div class="col-2">
                                <div class="p-0 mb-0 text-dark"><small></small></div>
                                    <div class="input-group mb-3">


                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="p-0 mb-0 text-dark"><small>Приоритет</small></div>
                                    <?php if($project[0]['urgency']==1): ?>
                                        <div class="badge bg-success bg-gradient text-white "> <?=$project[0]['urgency_name']?></div>
                                    <?php elseif($project[0]['urgency']==2): ?>
                                        <div class="badge bg-primary bg-gradient text-white "> <?=$project[0]['urgency_name']?></div>
                                    <?php elseif($project[0]['urgency']==3): ?>
                                       <div class="badge bg-warning bg-gradient text-white "> <?=$project[0]['urgency_name']?></div>
                                    <?php elseif($project[0]['urgency']==4): ?>
                                        <div class="badge bg-danger bg-gradient text-white"> <?=$project[0]['urgency_name']?> </div>
                                    <?php else:?>
                                        <div> <?=$project[0]['urgency_name']?> </div>
                                    <?php endif;?>
                                </div>
                                <div class="col-3">
                                    <div class="p-0 mb-0 text-dark"><small>Начало</small></div>
                                    <p><?=(new DateTime($project[0]['start_date']))->format('d.m.Y')?></p>
                                </div>
                                <div class="col-2">
                                    <div class="p-0 mb-0 text-dark"><small>Окончание</small></div>
                                    <p><?=(new DateTime($project[0]['end_date']))->format('d.m.Y')?></p>
                                </div>
                                <div class="col-2">
                                    <div class="p-0 mb-0 text-dark"><small>Категория</small></div>
                                    <p><?=$project[0]['category_name']?></p>
                                </div>
                              </div>
                            </div>

                          <div class="container">
                            <div class="row bg-primary bg-gradient text-white  p-1 rounded" >
                                <div class="col-10 p-2">
                                     Наименование
                                </div>
                            </div>
                          </div>
                          <div class="container">
                            <div class="row">
                                <p><?=$project[0]['title']?></p>
                            </div>
                          </div>
                          <div class="container">
                            <div class="row bg-primary bg-gradient text-white  p-1 rounded" >
                                <div class="col-10 p-2">
                                     Описание
                                </div>
                            </div>
                          </div>
                          <div class="container">
                            <div class="row">
                                <p><?=$project[0]['description']?></p>
                            </div>
                          </div>
                          <div class="container">
                            <div class="row bg-primary bg-gradient text-white  p-1 rounded" >




                                <div class="col-11 p-2">
                                  <div class="col" id="projectId" projectId="<?=$project[0]['id']?>">
                                      Задачи
                                  </div>
                                  </div>
                                <div class="col-1">
                                    <a href="javascript:showEditTask(0,0)" class="btn btn-outline-light">
                                        <i class="bi bi-plus"></i>
                                    </a>
                                </div>





                            </div>
                          </div>
                          <div class="container">
                            <div class="row">
                                <div class="alert alert-primary overflow-auto" style="max-height: 400px;">

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
                          </div>
                          <div class="container">
                            <div class="row">
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-outline-secondary" onclick="location.href='?page=projects&pg=1&curpg=1'">Вернуться</button>
                                    </div>
                            </div>
                          </div>


                      </div>
                  </div>
            </div>



<!-- Модальное окно просмотра-->
    <div class="modal fade" id="showTaskModal" tabindex="-1" aria-labelledby="showTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showTaskModalLabel">Информация о задаче</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="showForm">
                        <input type="hidden" id="showId">
                        <div class="card">
                            <div class="card-body">
                                <label for="showTaskIdParent" class="form-label">Родитель:</label>
                                <label id="showTaskNameParent"></label>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <label for="showTaskName" class="form-label">Имя:</label>
                                <label id="showTaskName"></label>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <label for="showTaskDesc" class="form-label">Описание</label>
                                <p id="showTaskDesc"></p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                      <div class="col-6">
                                          <label for="showTaskStart" class="form-label">Начало с</label>
                                          <!---<?=(new DateTime($request[0]['deadline']))->format('yyyy.mm.dd')?>-->
                                          <label id="showTaskStart"></label>
                                      </div>
                                      <div class="col-6">
                                          <label for="showTaskEnd" class="form-label">Окончание по</label>
                                          <!---<?=(new DateTime($request[0]['deadline']))->format('yyyy.mm.dd')?>-->
                                          <label id="showTaskEnd"></label>
                                      </div>
                                  </div>
                            </div>
                        </div>
                        <div class="card">
                             <div class="card-body">
                                    <label for="showTaskPriority" class="form-label">Приоритет</label>
                                    <label>
                                    <div id="showTaskPriority"></div>
                                    </label>
                             </div>
                         </div>
                        <div class="card">
                            <div class="card-body">
                                <label for="showTaskNameUser" class="form-label">Задача поручена: </label>
                                <label id="showTaskNameUser"></label>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <label for="editTaskDesc" class="form-label">Изменить статус</label>
                                    <div class="row">
                                        <div class="col-7">
                                            <div >
                                                <textarea class="form-control" id="editTaskDescStatus" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <button type="button" class="btn btn-secondary mb-1" onclick="sendStatus(2); result=false;">В работу</button>
                                            <button type="button" class="btn btn-info" onclick="sendStatus(3); result=false;">На паузу</button>
                                        </div>
                                        <div class="col-3">
                                            <button type="button" class="btn btn-warning mb-1" onclick="sendStatus(4); result=false;">На проверку</button>
                                            <button type="button" class="btn btn-success" onclick="sendStatus(5); result=false;">Завершить</button>
                                        </div>
                                    </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                          <div class="container">
                            <div class="row">
                                <div class="col-2">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Закрыть</button>
                                </div>
                            </div>
                          </div>

                </div>
            </div>
        </div>
    </div>


<!-- Модальное окно редактирования-->
    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaskModalLabel">Информация о задаче</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editId">
                        <input type="hidden" id="editProjectId">
                        <input type="hidden" id="editOriginalIdUser">
                        <input type="hidden" id="editOriginalStatus">
                        <div class="mb-3">
                            <label for="editTaskIdParent" class="form-label">Родитель</label>
                                  <select class="form-select form-select-sm status-select"  id="editTaskIdParent">

                                  </select>
                        </div>
                        <div class="mb-3">
                            <label for="editTaskName" class="form-label">Имя</label>
                            <input type="text" class="form-control" id="editTaskName" required >
                        </div>
                        <div class="mb-3">
                          <label for="editTaskDesc" class="form-label">Описание</label>
                          <textarea class="form-control" id="editTaskDesc" rows="4"></textarea>

                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                              <div class="col-6">
                                  <label for="editTaskStart" class="form-label">Начало с</label>
                                  <!---<?=(new DateTime($request[0]['deadline']))->format('yyyy.mm.dd')?>-->
                                  <input type="date" class="form-control" id="editTaskStart" required>
                              </div>
                              <div class="col-6">
                                  <label for="editTaskEnd" class="form-label">Окончание по</label>
                                  <!---<?=(new DateTime($request[0]['deadline']))->format('yyyy.mm.dd')?>-->
                                  <input type="date" class="form-control" id="editTaskEnd" required >
                              </div>
                        </div>
                        <div class="mb-3">
                              <label for="editTaskPriority" class="form-label">Приоритет</label>
                              <select class="form-select form-select-sm status-select"  id="editTaskPriority">
                                    <?php foreach ($AllPriorities as $one): ?>
                                        <option value="<?= $one['id'] ?>"  >
                                           <?= $one['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                              </select>
                        </div>
                        <div class="mb-3">
                             <label for="editTaskItUser" class="form-label">Задача поручена</label>
                            <select class="form-select form-select-sm status-select"  id="editTaskItUser">
                                <?php foreach ($allUsers as $one): ?>
                                <option value="<?= $one['id'] ?>"  >
                                   <?= $one['name'] ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" onclick="saveTaskChanges()">Сохранить изменения</button>
                </div>
            </div>
        </div>
    </div>

<!-- Модальное окно истории статуса-->
<div class="modal fade" id="dataModal" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dataModalLabel">История статусов задачи</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Индикатор загрузки -->
                <div class="loading-spinner" id="loadingSpinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Загрузка...</span>
                    </div>
                    <p class="mt-2">Загрузка данных...</p>
                </div>

                <!-- Контейнер для таблицы -->
                <div class="modal-table-container" id="tableContainer">
                    <table class="table table-light table-fixed">
                        <thead class="table-primary sticky-top">
                            <tr>
                                <th scope="col">Дата</th>
                                <th scope="col">Имя</th>
                                <th scope="col">Описание</th>
                                <th scope="col">Статус</th>
                            </tr>
                        </thead>
                        <tbody id="dataTableBody">
                            <!-- Данные будут загружены через JavaScript -->
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>


<?php else: ?>

    <div class="alert alert-danger" role="alert">
        Вы не имеете доступа к данной странице - авторизируйтесь
    </div>    
    
<?php endif;?>


    <!-- Инициализируем popover -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function htmlspecialchars(str) {
            str = str.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#039;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            return str;
        };


        $(document).ready(function() {
            // Загрузка дерева при загрузке страницы
            loadDepartmentsTree();

            // Обработчик кнопки обновления
            $('#refreshBtn').click(function() {
                loadDepartmentsTree();
            });
        });

        function loadDepartmentsTree() {//id="projectId" data-id="<?=$project[0]['id']?>"
            elem = document.getElementById('projectId');
            const projectId = elem.getAttribute('projectId');
            $('#treeContainer').hide();
            $('#loadingSpinner').show();

            $.ajax({
                url: '/?page=project&action=getTasks&id='+projectId.toString(),
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
                urgencyColor="";
                switch (item.urgency){
                    case "1": urgencyColor= " bg-success bg-gradient " ;break;
                    case "2": urgencyColor= " bg-primary bg-gradient " ;break;
                    case "3": urgencyColor= " bg-warning bg-gradient " ;break;
                    case "4": urgencyColor= " bg-danger bg-gradient " ;break;
                }
                statusColor="";
                switch (item.status_proc){
                    case "1": statusColor= " bg-primary bg-gradient " ;break;
                    case "2": statusColor= " bg-secondary bg-gradient " ;break;
                    case "3": statusColor= " bg-info bg-gradient " ;break;
                    case "4": statusColor= " bg-warning bg-gradient " ;break;
                    case "5": statusColor= " bg-success bg-gradient " ;break;
                }
                treeHtml +=
                 `<li class="d-flex justify-content-between align-items-end">
                     <a href="javascript:showTask(${item.id})" data-id="${item.id}" class="flex-grow-1 me-2">
                      <div class="d-flex align-items-end">
                          <i class="bi bi-list-task me-2"></i>
                          <span class="badge ${statusColor} text-white me-2">${item.status_proc_name} </span>
                          <span class="badge ${urgencyColor} text-white me-2">${item.urgency_name} </span>
                          ${item.name}
                          Период: ${item.start_date} -  ${item.end_date}  Исполнитель: ${item.iduser_proc_name}
                      </div>
                    </a>
                  <div class="d-flex align-items-end">
                      <button type="button" class="btn btn-info btn-sm me-2 mb-1" data-bs-toggle="modal" data-bs-target="#dataModal" onclick="loadHistoryData(${item.id})" >
                          <i class="bi bi-clock-history"></i>
                      </button>
                      <button type="button" class="btn btn-primary btn-sm me-2 mb-1" onclick="showEditTask(${item.id},${item.parent_id})" >
                          <i class="bi bi-pencil"></i>
                      </button>
                      <button type="button" class="btn btn-primary btn-sm me-2 mb-1" onclick="showEditTask(0,${item.id})">
                          <i class="bi bi-arrow-return-right"></i>
                      </button>
                      <button type="button" class="btn btn-danger btn-sm me-2 mb-1" onclick="deleteTask(${item.id})">
                          <i class="bi bi bi-x-square"></i>
                      </button>
                  </div>
                   </li>`;
                 /*`<li>
                    <a href="#" data-id="${item.id}">

                        <i class="bi bi-list-task"></i>
                        <span class="badge ${urgencyColor} text-white">${item.urgency_name}</span>
                        ${item.name}
                        Период ${item.start_date} -  ${item.end_date}

                        <button type="button" class="btn btn-primary "  data-bs-dismiss="modal"><i class="bi bi-pencil"></i></button>
                    </a>`;*/

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

        async function getTaskInformation(taskId) {
            //if (!this.currentProject) return [];

            try {
                const response = await fetch(`/?page=project&action=getTasksInfo&id=${taskId}`);
                return await response.json();
            } catch (error) {
                console.error('Error loading tasks:', error);
                return [];
            }
        }
        async function getAllTaskTree(exceptTaskId) {
            //if (!this.currentProject) return [];

            try {
                const response = await fetch(`/?page=project&action=getAllTaskTree&id=${exceptTaskId}`);
                return await response.json();
            } catch (error) {
                console.error('Error loading tasks:', error);
                return [];
            }
        }
        async function getAllTaskTreeByProject(idproject) {
            //if (!this.currentProject) return [];

            try {
                const response = await fetch(`/?page=project&action=getAllTaskTreeByProject&id=${idproject}`);
                return await response.json();
            } catch (error) {
                console.error('Error loading tasks:', error);
                return [];
            }
        }

    async function showTask(taskId){
        const taskInfo = await getTaskInformation(taskId);
        document.getElementById('showId').value = taskId;
        document.getElementById('showTaskName').textContent = taskInfo[0].title;
        document.getElementById('showTaskStart').textContent =  taskInfo[0].start_date;
        document.getElementById('showTaskEnd').textContent = taskInfo[0].end_date;
        document.getElementById('showTaskDesc').textContent = taskInfo[0].task_description;
        urgencyColor = "";
        switch (taskInfo[0].urgency){
                    case "1": urgencyColor= " bg-success bg-gradient " ;break;
                    case "2": urgencyColor= " bg-primary bg-gradient " ;break;
                    case "3": urgencyColor= " bg-warning bg-gradient " ;break;
                    case "4": urgencyColor= " bg-danger bg-gradient " ;break;
        }
        document.getElementById('showTaskPriority').innerHTML = `<span class="badge ${urgencyColor} text-white">${taskInfo[0].urgency_name} </span>`;
        document.getElementById('showTaskNameUser').textContent = taskInfo[0].iduser_proc_name;
        if(taskInfo[0].title_parent) { document.getElementById('showTaskNameParent').textContent = taskInfo[0].title_parent;}
        else {document.getElementById('showTaskNameParent').textContent = "<Корень>"}


        const showModal = new bootstrap.Modal(document.getElementById('showTaskModal'));
        showModal.show();
    }
    function sendStatus(statKod) {
        taskId = document.getElementById('showId').value;
        desc = htmlspecialchars(document.getElementById('editTaskDescStatus').value);
        updateTaskStatus(taskId, statKod, desc);
        const showModal = bootstrap.Modal.getInstance(document.getElementById('showTaskModal'));
        showModal.hide();
        loadDepartmentsTree() ;
    }
    function feelTreeElement(selEl, taskTreeExceptTaskId){
            if (!selEl) {
                console.error('Элемент select не найден');
                return;
            }
            // Очищаем существующие опции
            selEl.innerHTML = '';

            const optionElement = document.createElement('option');
            optionElement.value = 0;
            optionElement.textContent = '<Корень>';
            selEl.appendChild(optionElement);
            // Добавляем новые опции
            taskTreeExceptTaskId.forEach(taskTree => {
                const optionElement = document.createElement('option');
                optionElement.value = taskTree.id;
                optionElement.textContent = taskTree.title;
                selEl.appendChild(optionElement);
            });
                //if (taskTree.value === selectedValue) {
                //    optionElement.selected = true;
                //}
    }
    async function showEditTask(taskId, idparent){
        elem = document.getElementById('projectId');
        const projectId = elem.getAttribute('projectId');
        document.getElementById('editProjectId').value = projectId;

        if (taskId!==0){
            const taskInfo = await getTaskInformation(taskId);
            const taskTreeExceptTaskId = await getAllTaskTree(taskId);
            let selEl = document.getElementById('editTaskIdParent');
            feelTreeElement(selEl, taskTreeExceptTaskId);
            document.getElementById('editId').value = taskId;
            document.getElementById('editOriginalIdUser').value = taskInfo[0].iduser_proc;
            document.getElementById('editOriginalStatus').value = taskInfo[0].status_proc;
            document.getElementById('editTaskName').value = taskInfo[0].title;
            document.getElementById('editTaskStart').value =  taskInfo[0].start_date;
            document.getElementById('editTaskEnd').value = taskInfo[0].end_date;
            document.getElementById('editTaskDesc').value = taskInfo[0].task_description;
            document.getElementById('editTaskPriority').value = taskInfo[0].urgency;
            document.getElementById('editTaskItUser').value = taskInfo[0].iduser_proc;

            if (taskInfo[0].idparent==null) {selEl.value = 0;}
            else {selEl.value = taskInfo[0].idparent;}
        }
        else{
            //const taskInfo = await getTaskInformation(taskId);
            const taskTree = await getAllTaskTreeByProject(projectId);
            let selEl = document.getElementById('editTaskIdParent');
            feelTreeElement(selEl, taskTree);
            now = new Date();
            document.getElementById('editId').value = 0;
            document.getElementById('editOriginalIdUser').value = 0;
            document.getElementById('editOriginalStatus').value = 1;//новый
            document.getElementById('editTaskName').value = "";
            document.getElementById('editTaskStart').value =  now.toISOString().slice(0, 10);
            document.getElementById('editTaskEnd').value = now.toISOString().slice(0, 10);
            document.getElementById('editTaskDesc').value = "";
            document.getElementById('editTaskPriority').value = 1;
            document.getElementById('editTaskItUser').value = 0;
            if (idparent==null) {selEl.value = 0;}
            else {selEl.value = idparent;}
        }
        const editModal = new bootstrap.Modal(document.getElementById('editTaskModal'));
        editModal.show();
    }
   async function saveTaskChanges() {

        taskId = document.getElementById('editId').value;
        parentId = document.getElementById('editTaskIdParent').value;
        projectId = document.getElementById('editProjectId').value;
        taskName = htmlspecialchars(document.getElementById('editTaskName').value);
        taskStart =document.getElementById('editTaskStart').value;
        taskEnd = document.getElementById('editTaskEnd').value;
        taskDesc =  htmlspecialchars(document.getElementById('editTaskDesc').value);
        taskPriority = document.getElementById('editTaskPriority').value;
        taskIdUser = document.getElementById('editTaskItUser').value;
        taskOriginalIdUser = document.getElementById('editOriginalIdUser').value;
        taskOriginalStatus = document.getElementById('editOriginalStatus').value;

        try {
            const formData = new FormData();
            formData.append('taskId', taskId);
            formData.append('parentId', parentId);
            formData.append('projectId', projectId);
            formData.append('taskName', taskName);
            formData.append('taskStart', taskStart);
            formData.append('taskEnd', taskEnd);
            formData.append('taskDesc', taskDesc);
            formData.append('taskPriority', taskPriority);
            formData.append('taskIdUser', taskIdUser);
            formData.append('taskStatus', taskOriginalStatus);
            formData.append('taskChangeStatus', taskOriginalIdUser != taskIdUser);

            const showModal = bootstrap.Modal.getInstance(document.getElementById('editTaskModal'));
            showModal.hide();


            const response = await fetch('/?page=project&action=updateTask', {
                method: 'POST',
                body:formData
            });
            const result = await response.json();

            if (!result.success) {
                console.error('Error updating task status:', result.error);
                alert('Ошибка при обновлении статуса задачи');
            }
            loadDepartmentsTree() ;

        } catch (error) {
            console.error('Error updating task status:', error);
            alert('Ошибка при обновлении статуса задачи');
        }
   }
    async function updateTaskStatus(taskId, newStatus, desc) {
        try {
            const formData = new FormData();
            formData.append('taskId', taskId);
            formData.append('status', newStatus);
            formData.append('desc', desc);


            const response = await fetch('/?page=project&action=updateTaskStatus', {
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
       async function deleteTask(taskId) {
            if (confirm('Вы уверены, что хотите удалить эту задачу?')) {
                const formData = new FormData();
                formData.append('taskId', taskId);
                const response = await fetch('/?page=project&action=deleteTask', {
                                        method: 'POST',
                                        body: formData
                            });
                //console.log(response);
                const result = await response.json();
                //console.log(result);

                if (result.success===true) {
                    alert('Данные успешно удалены!');
                    loadDepartmentsTree() ;
                    //document.location.href = '?page=allrequests&pg=1&curpg=1';

                } else {
                    alert('Ошибка: ' + result.message);
                }

            }
        }

        // Функция для загрузки данных
        async function loadHistoryData(taskId) {
            const tableBody = document.getElementById('dataTableBody');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const tableContainer = document.getElementById('tableContainer');

            // Показываем индикатор загрузки
            loadingSpinner.style.display = 'block';
            tableContainer.style.display = 'none';

            try {
                // Загрузка данных с сервера
                const response = await fetch('?page=project&action=history&id='+taskId); // Замените на ваш URL
                const data = await response.json();

                // Очищаем таблицу
                tableBody.innerHTML = '';

                // Заполняем таблицу данными
                data.data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.dt}</td>
                        <td>${item.user_name}</td>
                        <td>${item.description}</td>
                        <td>${item.status_name }</td>
                    `;
                    tableBody.appendChild(row);
                });

            } catch (error) {
                console.error('Ошибка загрузки данных:', error);
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center text-danger">
                            Ошибка загрузки данных
                        </td>
                    </tr>
                `;
            } finally {
                // Скрываем индикатор и показываем таблицу
                loadingSpinner.style.display = 'none';
                tableContainer.style.display = 'block';
            }
        }


    </script>



</body>
</html>
