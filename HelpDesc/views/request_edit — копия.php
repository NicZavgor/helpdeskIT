<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Иконки Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .employee-search-results {
            position: absolute;
            z-index: 1000;
            max-height: 200px;
            overflow-y: auto;
        }
        .selected-employee {
            background-color: #e9ecef;
            padding: 8px;
            border-radius: 4px;
            margin-top: 5px;
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






        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-top: none;
            z-index: 99;
            top: 100%;
            left: 0;
            right: 0;
            max-height: 200px;
            overflow-y: auto;
        }
        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #d4d4d4;
        }
        .autocomplete-items div:hover {
            background-color: #e9e9e9;
        }
        .autocomplete-active {
            background-color: #007bff !important;
            color: #ffffff;
        }
.card {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border: none;
}

.card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.form-label {
    font-weight: 500;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

     .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            top: 100%;
            left: 0;
            right: 0;
            max-height: 200px;
            overflow-y: auto;
        }
        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #d4d4d4;
        }
        .autocomplete-items div:hover {
            background-color: #e9e9e9;
        }
        .autocomplete-active {
            background-color: DodgerBlue !important;
            color: #ffffff;
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
    <div class="card-body">
        <form id="pageForm" <?php if($request[0]['id']===0):?> action="?page=requestedit&new" <?php else: ?> action="?page=requestedit&editid=$request[0]['id']"<?php endif;?> method="post">
            <input type="hidden" name="page_id" id="page_id" value="<?=$request[0]['id']?>">

            <p style="color: red" id="message"></p>
            <?php if(!empty($message)):?>
                <h5><?=$message?></h5>
            <?php else: ?>
                <h5></h5>
            <?php endif;?>

                  <div class="row">
                      <div class="col-12">
                          <div class="container">
                              <div class="row bg-primary bg-gradient text-white  p-1 rounded" >
                                  <div class="col">
                                      Данные по заявке №<?=$request[0]['id']?>
                                  </div>
                              </div>
                          </div>
                          <div class="container">
                              <div class="row">
                                  <div class="col-12">
                                      <div class="mb-3">
                                        <label for="editTopic" class="form-label">Тема</label>
                                        <input type="text" class="form-control" id="editTopic" required>
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-3">
                                           <label for="editTopic" class="form-label" >Исполнитель</label>
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
                                      <div class="col-3">
                                          <div class="mb-3">
                                              <label for="editCategory" class="form-label">Категория</label>
                                              <select class="form-select form-select-sm status-select"  id="editCategory">
                                                <?php foreach ($AllCategories as $one): ?>
                                                <option value="<?= $one['id'] ?>">
                                                   <?= $one['name'] ?>
                                                </option>
                                                <?php endforeach; ?>
                                              </select>
                                          </div>
                                      </div>
                                      <div class="col-3">
                                          <div class="mb-3">
                                              <label for="editName" class="form-label">Приоритет</label>
                                              <select class="form-select form-select-sm status-select"  id="editItRole">
                                                <?php foreach ($AllPriorities as $one): ?>
                                                <option value="<?= $one['id'] ?>">
                                                   <?= $one['name'] ?>
                                                </option>
                                                <?php endforeach; ?>
                                              </select>
                                          </div>
                                      </div>
                                      <div class="col-3">
                                          <label for="editName" class="form-label">Срок</label>
                                          <input type="date" class="form-control" id="editdeadline" required value="<?=(new DateTime($request[0]['deadline']))->format('Y.m.d')?>">
                                      </div>
                                  </div>

                              </div>
                              <div class="row">
                                  <div class="col-4">
                                    <div class="mb-3">

                                <label for="employee" class="form-label">Сотрудник</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" id="employee"
                                           placeholder="Введите фамилию сотрудника"
                                           autocomplete="off" required>
                                    <div id="employeeAutocompleteList" class="autocomplete-items d-none"></div>
                                </div>
                                <input type="hidden" id="employeeId" name="employee_id">


                                    </div>
                                  </div>
                                  <div class="col-4">
                                    <div class="mb-3">
                                        <label class="form-label">Учетка</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="accountSearch"
                                                   placeholder="Начните вводить имя учетки..."
                                                   autocomplete="off">
                                            <button class="btn btn-outline-secondary" type="button" id="accountClearSearch">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                        <div id="searchaccountResults" class="account-search-results w-100 d-none"></div>
                                        <input type="hidden" id="selectedaccountId" name="accountId" required>
                                        <div id="selectedaccountInfo" class="selected-account d-none mt-2"></div>
                                    </div>
                                  </div>
                                  <div class="col-4">
                                    <div class="mb-3">
                                        <label class="form-label">ПК</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="PCSearch"
                                                   placeholder="Начните вводить имя ПК..."
                                                   autocomplete="off">
                                            <button class="btn btn-outline-secondary" type="button" id="PCClearSearch">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                        <div id="searchPCResults" class="PC-search-results w-100 d-none"></div>
                                        <input type="hidden" id="selectedPCId" name="PCId" required>
                                        <div id="selectedPCInfo" class="selected-PC d-none mt-2"></div>
                                    </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <label for="content" class="form-label">Описание проблемы</label>
                                   <textarea class="form-control" id="requestDescription" rows="8"></textarea>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>

        </form>
    </div>


<?php else: ?>
    <div class="alert alert-danger" role="alert">
        Вы не имеете доступа к данной странице - авторизируйтесь
    </div>
<?php endif;?>
</div>

    <!-- Инициализируем popover -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function htmlspecialchars(str) {
            str = str.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#039;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            return str;
        };

  /*      document.addEventListener('DOMContentLoaded', function() {
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

        // Простая логика отправки сообщения
        document.getElementById('sendButton').addEventListener('click', sendMessage);
        document.getElementById('messageInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                event.preventDefault();
                sendMessage();
            }
        });fetch('?page=employees&action=comboboxsearch&str=' + encodeURIComponent(searchTerm))
*/


document.addEventListener('DOMContentLoaded', function() {
    const employeeInput = document.getElementById('employee');
    const autocompleteList = document.getElementById('employeeAutocompleteList');
    const employeeIdInput = document.getElementById('employeeId');
    let currentFocus = -1;

    // Обработчик ввода текста
    employeeInput.addEventListener('input', function(e) {
        const query = e.target.value.trim();
        if (query.length < 2) {
            hideAutocomplete();
            return;
        }
        searchEmployees(query);
    });

    // Обработчик нажатия клавиш
    employeeInput.addEventListener('keydown', function(e) {
        const items = autocompleteList.getElementsByTagName('div');
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            currentFocus = Math.min(currentFocus + 1, items.length - 1);
            addActive(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            currentFocus = Math.max(currentFocus - 1, -1);
            addActive(items);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (currentFocus > -1 && items.length > 0) {
                items[currentFocus].click();
            }
        } else if (e.key === 'Escape') {
            hideAutocomplete();
        }
    });

    // Закрытие автодополнения при клике вне области
    document.addEventListener('click', function(e) {
        if (!employeeInput.contains(e.target) && !autocompleteList.contains(e.target)) {
            hideAutocomplete();
        }
    });

    // Поиск сотрудников
    function searchEmployees(query) {
        const formData = new FormData();
        formData.append('action', 'search_employees');
        formData.append('query', query);

        fetch('?page=employees&action=comboboxsearch&str=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
            showAutocomplete(data);
        })
        .catch(error => {
            console.error('Ошибка:', error);
            hideAutocomplete();
        });
    }

    // Показать автодополнение
    function showAutocomplete(employees) {
        if (employees.length === 0) {
            hideAutocomplete();
            return;
        }

        autocompleteList.innerHTML = '';
        employees.forEach(employee => {
            const item = document.createElement('div');
            item.innerHTML = `<strong>${employee.name}</strong>`;
            item.dataset.id = employee.guid;

            item.addEventListener('click', function() {
                selectEmployee(employee);
            });

            autocompleteList.appendChild(item);
        });

        autocompleteList.classList.remove('d-none');
        currentFocus = -1;
    }

    // Скрыть автодополнение
    function hideAutocomplete() {
        autocompleteList.classList.add('d-none');
        autocompleteList.innerHTML = '';
        currentFocus = -1;
    }

    // Выбор сотрудника
    function selectEmployee(employee) {
        employeeInput.value = `${employee.name} `;
        employeeIdInput.value = employee.guid;
        hideAutocomplete();
    }

    // Добавить активный класс
    function addActive(items) {
        removeActive(items);

        if (currentFocus >= items.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = items.length - 1;

        if (items[currentFocus]) {
            items[currentFocus].classList.add('autocomplete-active');
            items[currentFocus].scrollIntoView({ block: 'nearest' });
        }
    }

    // Убрать активный класс
    function removeActive(items) {
        for (let i = 0; i < items.length; i++) {
            items[i].classList.remove('autocomplete-active');
        }
    }
});
    </script>
</body>
</html>
