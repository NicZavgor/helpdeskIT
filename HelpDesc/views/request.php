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
              .chat-container {
                                     max-width: 400px;
                                     margin: 0 auto;
                                     border: 1px solid #dee2e6;
                                     border-radius: 8px;
                                     overflow: hidden;
                                 }

                                 .chat-header {
                                     background-color: #0d6efd;
                                     color: white;
                                     padding: 10px 15px;
                                 }

                                 .chat-messages {
                                     height: 300px;
                                     overflow-y: auto;
                                     padding: 15px;
                                     background-color: #f8f9fa;
                                 }

                                 .message {
                                     margin-bottom: 10px;
                                     max-width: 80%;
                                 }

                                 .received {
                                     align-self: flex-start;
                                     background-color: #e9ecef;
                                     border-radius: 0 10px 10px 10px;
                                     padding: 8px 12px;
                                 }

                                 .sent {
                                     align-self: flex-end;
                                     background-color: #0d6efd;
                                     color: white;
                                     border-radius: 10px 0 10px 10px;
                                     padding: 8px 12px;
                                 }

                                 .chat-input {
                                     border-top: 1px solid #dee2e6;
                                     padding: 10px;
                                     background-color: white;
                                 }

                                 /* Стили для скроллбара */
                                 .chat-messages::-webkit-scrollbar {
                                     width: 6px;
                                 }

                                 .chat-messages::-webkit-scrollbar-track {
                                     background: #f1f1f1;
                                 }

                                 .chat-messages::-webkit-scrollbar-thumb {
                                     background: #888;
                                     border-radius: 3px;
                                 }
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
                      <div class="col-8">
                          <div class="container">
                              <div class="row bg-primary bg-gradient text-white  p-1 rounded" >
                                <div class="col-11 p-2">
                                  <div class="col" id="requestId" data-id="<?=$request[0]['id']?>">
                                      Данные по заявке №<?=$request[0]['id']?>
                                  </div>
                                  </div>
                                <div class="col-1">
                                    <a href="?page=requestedit&editid=<?=$request[0]['id']?>" class="btn btn-outline-light">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                        </svg>
                                    </a>
                                </div>
                              </div>
                          </div>
                          <div class="container">
                            <div class="row">
                              <div class="col-2">
                                <div class="p-0 mb-0 text-dark"><small>Статус</small></div>
                                <div class="input-group mb-3">

                                <?php if($request[0]['status_proc']==1):?>
                                     <div class="badge bg-primary bg-gradient"> <?=$request[0]['status_proc_name']?></div>
                                <?php elseif($request[0]['status_proc']==2): ?>
                                     <div class="badge bg-secondary bg-gradient text-white"> <?=$request[0]['status_proc_name']?> </div>
                                <?php elseif($request[0]['status_proc']==3): ?>
                                     <div class="badge bg-info bg-gradient text-white"> <?=$request[0]['status_proc_name']?> </div>
                                <?php elseif($request[0]['status_proc']==4): ?>
                                     <div class="badge bg-warning bg-gradient text-white"> <?=$request[0]['status_proc_name']?> </div>
                                <?php elseif($request[0]['status_proc']==5): ?>
                                     <div class="badge bg-success bg-gradient text-white"> <?=$request[0]['status_proc_name']?></div>
                                <?php endif;?>

                                </div>
                              </div>
                              <div class="col-3">
                                <div class="p-0 mb-0 text-dark"><small>Приоритет</small></div>
                                <?php if($request[0]['urgency']==1 and $request[0]['status_proc']!=5): ?>
                                    <div class="badge bg-success bg-gradient text-white "> <?=$request[0]['urgency_name']?></div>
                                <?php elseif($request[0]['urgency']==2 and $request[0]['status_proc']!=5): ?>
                                    <div class="badge bg-primary bg-gradient text-white "> <?=$request[0]['urgency_name']?></div>
                                <?php elseif($request[0]['urgency']==3 and $request[0]['status_proc']!=5): ?>
                                   <div class="badge bg-warning bg-gradient text-white "> <?=$request[0]['urgency_name']?></div>
                                <?php elseif($request[0]['urgency']==4 and $request[0]['status_proc']!=5): ?>
                                    <div class="badge bg-danger bg-gradient text-white"> <?=$request[0]['urgency_name']?> </div>
                                <?php elseif($request[0]['urgency']==1 and $request[0]['status_proc']==5): ?>
                                    <div class="badge btn btn-outline-success text-success "> <?=$request[0]['urgency_name']?></div>
                                <?php elseif($request[0]['urgency']==2 and $request[0]['status_proc']==5): ?>
                                    <div class="badge btn btn-outline-primary text-primary"> <?=$request[0]['urgency_name']?></div>
                                <?php elseif($request[0]['urgency']==3 and $request[0]['status_proc']==5): ?>
                                    <div class="badge btn btn-outline-warning text-warning"> <?=$request[0]['urgency_name']?></div>
                                <?php elseif($request[0]['urgency']==4 and $request[0]['status_proc']==5): ?>
                                    <div class="badge btn btn-outline-danger text-danger"> <?=$request[0]['urgency_name']?></div>
                                <?php else:?>
                                    <div> <?=$request[0]['urgency_name']?> </div>
                                <?php endif;?>
                              </div>
                              <div class="col-3">
                                <div class="p-0 mb-0 text-dark"><small>Создана</small></div>
                                <p><?=(new DateTime($request[0]['dt']))->format('Y.m.d H:i:s')?></p>
                              </div>
                              <div class="col-2">
                                <div class="p-0 mb-0 text-dark"><small>Срок</small></div>

                                <p><?=(new DateTime($request[0]['deadline']))->format('d.m.Y')?></p>
                              </div>
                              <div class="col-2">
                                    <?php if(!empty($request[0]['idpreviosrequest'])):?>
                                    <div class="p-0 mb-0 text-dark"><small>Исходная заявка</small></div>
                                    <p><?=$request[0]['idpreviosrequest']?></p>
                                    <?php endif;?>
                              </div>
                            </div>
                          </div>
                          <div class="container">
                            <div class="row bg-primary bg-gradient text-white  p-1 rounded" >
                                <div class="col-10 p-2">
                                     Описание проблемы
                                </div>
                                <div class="col-2">
                                     <button type="button" class="btn btn-outline-light float-end" data-bs-toggle="modal" data-bs-target="#imageModal" onclick="loadImage()">
                                                 Скриншот
                                     </button>

                                </div>
                            </div>
                          </div>
                          <div class="container">
                            <div class="row">
                                <h4><?=$request[0]['topic']?></h4>
                                <p><?=$request[0]['message']?></p>
                            </div>
                          </div>
                          <div class="container">
                            <div class="row bg-primary bg-gradient text-white  p-1 rounded" >
                                <div class="col" >
                                    Последнее действие
                                </div>
                            </div>
                          </div>
                          <div class="container">
                            <div class="row">
                                <div class="alert alert-primary overflow-auto" style="max-height: 100px;">
                                    <?=$request[0]['description_proc']?>
                                </div>
                            </div>
                          </div>
                          <div class="container">
                            <div class="row bg-primary bg-gradient text-white  p-1 rounded" >
                                <div class="col" >
                                     Описание работы (результата)
                                </div>
                            </div>
                          </div>
                          <div class="container">
                            <div class="row">
                                 <textarea class="form-control" id="requestDescription" rows="8"></textarea>
                            </div>
                          </div>
                          <div class="container">
                            <div class="row">

                                <div class="col-5">
                                    <button type="button" class="btn btn-secondary" onclick="sendStatus(2, 0); result=false;">В работу</button>
                                    <button type="button" class="btn btn-info" onclick="sendStatus(3, 0); result=false;">На паузу</button>
                                    <button type="button" class="btn btn-warning" onclick="sendStatus(4, 0); result=false;">На проверку</button>
                                </div>
                                <div class="col-3">

                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#editModal" >Сменить исполнителя</button>
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-success" onclick="sendStatus(5, 0); result=false;">Закрыть</button>
                                </div>

                                <div class="col-1">
                                    <button type="button" class="btn btn-outline-secondary" onclick="location.href='<?=$returnURL?>'">Вернуться</button>
                                </div>
                            </div>
                          </div>

                      </div>
                      <div class="col-4">
                          <div class="container">
                            <div class="row bg-primary bg-gradient text-white  p-1 rounded" >
                                <div class="col">
                                     Заявка поступила от
                                </div>
                            </div>
                          </div>
                          <div class="container">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="py-2"> Имя ПК</div>
                                        <div class="py-2"> Сотрудник</div>
                                        <div > Учетная запись</div>
                                    </div>
                                    <div class="col-7">
                                        <div>
                                            <div class="btn btn-outline-primary" id="popoverButton" data-bs-toggle="popover" title="Состав ПК" data-bs-placement="bottom" data-bs-trigger="hover"
                                                    data-bs-content="<?=$request[0]['lst_typemodelname']?>"><?=$request[0]['pcname']?>
                                            </div>

                                        </div>
                                        <div>
                                            <div class="btn btn-outline-primary" id="popoverButton" data-bs-toggle="popover" title="<?=$request[0]['employeename']?>" data-bs-placement="bottom" data-bs-trigger="hover"
                                                    data-bs-content="<?='Отдел: '.$request[0]['subdivision_name'].'$$$Должность: '.$request[0]['job_name']?>"><?=$request[0]['employeename']?>
                                            </div>

                                        </div>
                                        <div id="accountInfo" data-id="<?=$request[0]['id']?>"> <?=$request[0]['account']?></div>
                                        <div id="guidemployee" data-id="<?=$request[0]['guidemployee']?>"></div>
                                    </div>
                                </div>
                          </div>

                          <div class="container">
                            <div class="row bg-primary bg-gradient text-white p-1 rounded" >
                                <div class="col-3">
                                     Исполнитель
                                </div>
                            </div>
                          </div>
                          <div class="container p-1">
                              <div class="row">
                                  <div class="col-6">
                                      <div class="p-2"> <?=$request[0]['name_users']?></div>
                                  </div>
                                  <div class="col-6">
                                      <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#dataModal" onclick="loadHistoryData()">
                                           <small>История</small>
                                      </button>
                                  </div>
                              </div>
                          </div>
                          <div class="container">
                            <div class="row bg-primary bg-gradient text-white p-1 rounded" >
                                <div class="col">
                                     Чат с заявителем
                                </div>
                            </div>
                          </div>
                          <div class="container p-1">
                              <div class="row">
                                  <div class="col" id="chatlastmsgid" data-id=0></div>>
                                        <div class="container">
                                            <div class="chat-container">

                                                <div class="chat-messages d-flex flex-column" id="chatMessages">
                                                </div>

                                                <div class="chat-input">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" placeholder="Введите сообщение..." id="messageInput">
                                                        <button class="btn btn-primary" type="button" id="sendButton">Отправить</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                  </div>
                              </div>
                          </div>

                      </div>
                  </div>
            </div>
        </div>
    </div>


</div>
    <!-- Модальное показ скриншота -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Просмотр изображения</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid" alt="Изображение">
                    <div id="loadingSpinner" class="spinner-border text-primary mt-3" role="status" style="display: none;">
                        <span class="visually-hidden">Загрузка...</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <a id="downloadLink" href="#" class="btn btn-success" download="image.png">Скачать</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Модальное окно редактирования -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Сотрудники ИТ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editId">
                        <div class="mb-3">
                            <select class="form-select form-select-sm status-select"  id="editItUser">
                                <?php foreach ($allUsers as $one): ?>
                                <option value="<?= $one['id'] ?>"   <?php if($request[0]['iduser_proc']===$one['id']):?> selected <?php endif;?>   >
                                   <?= $one['name'] ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" onclick="sendStatus(<?=$request[0]['status_proc']?>, document.getElementById('editItUser').value)">Сохранить изменения</button>
                </div>
            </div>
        </div>
    </div>


<!-- Модальное окно -->
<div class="modal fade" id="dataModal" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dataModalLabel">История статусов обращения</h5>
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

    </div>
    </form>

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


        document.addEventListener('DOMContentLoaded', function() {
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl, {
                    html: true,
                    content: function() {
                        // Заменяем символы новой строки на <br>
                        return popoverTriggerEl.getAttribute('data-bs-content').replaceAll('$$$', '<br>');
                    }
                });
            });
        });

        async function sendStatus(stat, iduser){
            $id = document.getElementById('requestId').getAttribute('data-id');
            $description = htmlspecialchars(document.getElementById('requestDescription').value);

            try {
                 const response = await fetch('?page=request&api&action=proc', {
                                     method: 'POST',
                                     body: JSON.stringify(
                                        {id: $id,
                                        description: $description,
                                        request_userid:iduser,
                                        status: stat
                                     })
                                 });
                console.log(response);
                //const response = await fetch('models/dbcategories.php', { method: 'POST', body: formData});
                const result = await response.json();
                console.log(result);

                if (result.success===true) {
                    document.location.href = '?page=allrequests&pg=1&curpg=1';
                } else {
                    alert('Ошибка: ' + result.message);
                }
            } catch (error) {
                console.error('Ошибка:', error);
                alert('Произошла ошибка при сохранении данных');
            }


        }




        // Функция для загрузки данных
        async function loadHistoryData() {
            const tableBody = document.getElementById('dataTableBody');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const tableContainer = document.getElementById('tableContainer');

            // Показываем индикатор загрузки
            loadingSpinner.style.display = 'block';
            tableContainer.style.display = 'none';

            try {
                // Загрузка данных с сервера
                const response = await fetch('?page=request&api&action=history&id='+document.getElementById('requestId').getAttribute('data-id')); // Замените на ваш URL
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

        // Автоматическая загрузка данных при открытии модального окна
        document.getElementById('dataModal').addEventListener('shown.bs.modal', loadHistoryData);



        // Простая логика отправки сообщения
        document.getElementById('sendButton').addEventListener('click', sendMessage);
        document.getElementById('messageInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                event.preventDefault();
                sendMessage();
            }
        });

        async function loadChatMessages() {
                lastmsgid = document.getElementById('chatlastmsgid').getAttribute('data-id');
                clientguid = document.getElementById('guidemployee').getAttribute('data-id');
                $requestId = document.getElementById('requestId').getAttribute('data-id');

                const messagesContainer = document.getElementById('chatMessages');
                // newMessage = document.createElement('div');
                //newMessage.className = 'message sent';
                try {
                      response = await fetch('?page=request&api&action=getchatmsg&request='+$requestId+'&lastid='+lastmsgid, {
                                         method: 'GET'
                                     });
                    //console.log(response);
                    //const response = await fetch('models/dbcategories.php', { method: 'POST', body: formData});
                     result = await response.json();
                    //console.log(result);

                    if (result.success===true) {
                        if (result.messages.length > 0) {
                            result.messages.forEach(onemsg => {
                                newMessage = document.createElement('div');
                                if (onemsg.guid===clientguid) {
                                    newMessage.className = 'message received';
                                }
                                else{
                                    newMessage.className = 'message sent';
                                }
                                newMessage.textContent = onemsg.msg;
                                messagesContainer.appendChild(newMessage);
                                ///addMessageToChat(message);
                                lastmsgid = Math.max(lastmsgid, onemsg.id);
                            });

                            // Прокрутка к последнему сообщению
                            //const messagesContainer = $('#messages');
                            messagesContainer.scrollTop = messagesContainer.scrollHeight;
                            document.getElementById('chatlastmsgid').setAttribute('data-id', lastmsgid);
                        }

                    } else {
                        newMessage.textContent = 'Ошибка: ' + result.message;
                    }
                } catch (error) {
                    newMessage.textContent = 'Ошибка:'+ error;
                }

                setTimeout(loadChatMessages, 2000);


        }

        async function sendMessage() {
            const input = document.getElementById('messageInput');
            const message = input.value.trim();

            if (message) {
                $id = document.getElementById('requestId').getAttribute('data-id');
                try {
                     const response = await fetch('?page=request&api&action=chatnewmsg', {
                                         method: 'POST',
                                         body: JSON.stringify(
                                            {id: $id,
                                            msg: message
                                         })
                                     });
                    const result = await response.json();

                    if (result.success===true) {
                       // newMessage.textContent = message;
                    } else {
                        messagesContainer = document.getElementById('chatMessages');
                        newMessage = document.createElement('div');
                        newMessage.className = 'message sent';
                        newMessage.textContent = 'Ошибка: ' + result.message;
                        messagesContainer.appendChild(newMessage);
                    }
                } catch (error) {
                    messagesContainer = document.getElementById('chatMessages');
                    newMessage = document.createElement('div');
                    newMessage.className = 'message sent';
                    newMessage.textContent = 'Ошибка: '+ error;
                    messagesContainer.appendChild(newMessage);

                }
                input.value = '';
            }
        }

    function loadImage(imageId) {
        const modalImage = document.getElementById('modalImage');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const downloadLink = document.getElementById('downloadLink');
        $id = document.getElementById('requestId').getAttribute('data-id');
        // Показываем спиннер загрузки
        modalImage.style.display = 'none';
        loadingSpinner.style.display = 'block';

        // Устанавливаем src с timestamp для избежания кэширования
        const imageUrl = `?page=request&action=getscreenshot&request=`+$id;

        // Предзагрузка изображения
        const img = new Image();
        img.onload = function() {
            modalImage.src = imageUrl;
            modalImage.style.display = 'block';
            loadingSpinner.style.display = 'none';

            // Обновляем ссылку для скачивания
            downloadLink.href = imageUrl;
        };

        img.onerror = function() {
            modalImage.src = 'path/to/error/image.png';
            modalImage.style.display = 'block';
            loadingSpinner.style.display = 'none';
            downloadLink.style.display = 'none';
        };

        img.src = imageUrl;
    }

    // Очистка изображения при закрытии модального окна
    document.getElementById('imageModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('modalImage').src = '';
    });
        // Загружаем сообщения при загрузке страницы
        $(document).ready(function() {
            loadChatMessages();
        });
    </script>



</body>
</html>
