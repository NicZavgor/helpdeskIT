<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Иконки Bootstrap -->


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
                                      <?php if($request[0]['id']===0):?>
                                            Новая заявка
                                      <?php else: ?>
                                            Данные по заявке №<?=$request[0]['id']?>
                                      <?php endif;?>
                                  </div>
                              </div>
                          </div>

                          <div class="container">
                              <div class="row">
                                  <div class="col-12">
                                      <div class="mb-3">
                                        <label for="editTopic" class="form-label">Тема</label>
                                        <input type="text" class="form-control" id="editTopic" value="<?=$request[0]['topic']?>" required>
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-3">
                                          <div class="mb-3">
                                              <label for="editTopic" class="form-label" >Исполнитель</label>
                                              <select class="form-select form-select-sm status-select"  id="editItUser">
                                                  <?php foreach ($allUsers as $one): ?>
                                                       <option value="<?= $one['id'] ?>"   <?php if($request[0]['iduser_proc']===$one['id']):?> selected <?php endif;?>   >
                                                          <?= $one['name'] ?>
                                                       </option>
                                                  <?php endforeach; ?>
                                              </select>
                                          </div>

                                      </div>
                                      <div class="col-3">
                                          <div class="mb-3">
                                              <label for="editCategory" class="form-label">Категория</label>
                                              <select class="form-select form-select-sm status-select"  id="editCategory">
                                                <?php foreach ($AllCategories as $one): ?>
                                                    <option value="<?= $one['id'] ?>"   <?php if($request[0]['idcategory']===$one['id']):?> selected <?php endif;?>   >
                                                   <?= $one['name'] ?>
                                                </option>
                                                <?php endforeach; ?>
                                              </select>
                                          </div>
                                      </div>
                                      <div class="col-3">
                                          <div class="mb-3">
                                              <label for="editPriority" class="form-label">Приоритет</label>
                                              <select class="form-select form-select-sm status-select"  id="editPriority">
                                                <?php foreach ($AllPriorities as $one): ?>
                                                    <option value="<?= $one['id'] ?>"   <?php if($request[0]['urgency']===$one['id']):?> selected <?php endif;?>   >
                                                       <?= $one['name'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                              </select>
                                          </div>
                                      </div>
                                      <div class="col-3">
                                          <label for="editName" class="form-label">Срок</label>
                                          <!---<?=(new DateTime($request[0]['deadline']))->format('yyyy.mm.dd')?>-->
                                          <input type="date" class="form-control" id="editdeadline" required value=<?=$request[0]['deadline']?>>
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
                                                   autocomplete="off" value="<?=$request[0]['employeename']?>" required>
                                            <div id="employeeAutocompleteList" class="autocomplete-items d-none"></div>
                                        </div>
                                        <input type="hidden" id="employeeId" name="employee_id">
                                     </div>
                                  </div>
                                  <div class="col-4">
                                     <div class="mb-3">
                                        <label for="account" class="form-label">Аккакунт</label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control" id="account"
                                                   placeholder="Введите аккаунт"
                                                   autocomplete="off" value="<?=$request[0]['account']?>" required>
                                            <div id="accountAutocompleteList" class="autocomplete-items d-none"></div>
                                        </div>
                                        <input type="hidden" id="accountId" name="account_id">
                                     </div>
                                  </div>
                                  <div class="col-4">
                                     <div class="mb-3">

                                        <label for="PCName" class="form-label">Имя ПК</label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control" id="PCName"
                                                   placeholder="Введите имя ПК"
                                                   autocomplete="off" value="<?=$request[0]['pcname']?>"required>
                                            <div id="PCNameAutocompleteList" class="autocomplete-items d-none"></div>
                                        </div>
                                        <input type="hidden" id="PCNameId" name="PCName_id">
                                     </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <label for="content" class="form-label">Описание проблемы</label>
                                  <textarea class="form-control" id="editMessage" rows="8"><?=$request[0]['message']?></textarea>
                              </div>
                          </div>
                            <div class="container">
                              <div class="row">
                                 <div class="col-10">
                                     <div class="mb-3">
                                        <button type="button" class="btn btn-primary" onclick="saveChanges()">Сохранить</button>
                                        <button type="button" class="btn btn-outline-primary"
                                        onclick="location.href= '<?= $ReturnURL ?>'" id="cancelBtn" >Отмена</button>

                                    </div>
                                 </div>
                                 <div class="col-2">
                                    <button type="button" class="btn btn-danger float-end" onclick="deletePage()" id="deleteBtn" >Удалить </button>
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



    async function saveChanges() {
        id = document.getElementById('page_id').value;
        idcategory = (document.getElementById('editCategory').value);

        topic = document.getElementById('editTopic').value;
        message = (document.getElementById('editMessage').value);
        urgency = (document.getElementById('editPriority').value);
        ituser = (document.getElementById('editItUser').value);
        deadline = (document.getElementById('editdeadline').value);
        employee_guid = (document.getElementById('employeeId').value);
        employee_name = (document.getElementById('employee').value);
        account = (document.getElementById('account').value);
        PCName = (document.getElementById('PCName').value);



        //$text = (document.getElementById('hiddenContent').value);

        try {
             const response = await fetch('?page=requestedit&api&action=pst', {
                                 method: 'POST',
                                 body: JSON.stringify({
                                    id: id,
                                    topic: topic,
                                    message: message,
                                    idcategory: idcategory,
                                    urgency: urgency,
                                    ituser: ituser,
                                    deadline: deadline,
                                    employee_guid: employee_guid,
                                    employee_name: employee_name,
                                    account: account,
                                    PCName: PCName
                                 })
                             });
            console.log(response);
            //const response = await fetch('models/dbcategories.php', { method: 'POST', body: formData});
            const result = await response.json();
            console.log(result);

            if (result.success===true) {
                alert('Данные успешно сохранены!');
                document.location.href = '?page=request&id='+result.returnID+'&rp=a';

            } else {
                alert('Ошибка: ' + result.message);
            }
        } catch (error) {
            console.error('Ошибка:', error);
            alert('Произошла ошибка при сохранении данных');
        }

    }



document.addEventListener('DOMContentLoaded', function() {
    const employeeInput = document.getElementById('employee');
    const employeeautocompleteList = document.getElementById('employeeAutocompleteList');
    const employeeIdInput = document.getElementById('employeeId');
    const accountInput = document.getElementById('account');

    const PCNameInput = document.getElementById('PCName');
    const PCNameautocompleteList = document.getElementById('PCNameAutocompleteList');
    const PCNameIdInput = document.getElementById('PCNameId');



    let currentFocus = -1;
//////////////employee
    // Обработчик ввода текста
    employeeInput.addEventListener('input', function(e) {
        const query = e.target.value.trim();
        if (query.length < 2) {
            hideAutocompleteEmployee();
            return;
        }
        searchEmployees(query);
    });

    // Обработчик нажатия клавиш
    employeeInput.addEventListener('keydown', function(e) {
        const items = employeeautocompleteList.getElementsByTagName('div');
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
            hideAutocompleteEmployee();
        }
    });

    // Закрытие автодополнения при клике вне области
    document.addEventListener('click', function(e) {
        if (!employeeInput.contains(e.target) && !employeeautocompleteList.contains(e.target)) {
            hideAutocompleteEmployee();
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
            showAutocompleteEmployee(data);
        })
        .catch(error => {
            console.error('Ошибка:', error);
            hideAutocompleteEmployee();
        });
    }

    // Показать автодополнение
    function showAutocompleteEmployee(employees) {
        if (employees.length === 0) {
            hideAutocompleteEmployee();
            return;
        }

        employeeautocompleteList.innerHTML = '';
        employees.forEach(employee => {
            const item = document.createElement('div');
            item.innerHTML = `<strong>${employee.name}</strong>`;
            item.dataset.id = employee.guid;

            item.addEventListener('click', function() {
                selectEmployee(employee);
            });

            employeeautocompleteList.appendChild(item);
        });

        employeeautocompleteList.classList.remove('d-none');
        currentFocus = -1;
    }

    // Скрыть автодополнение
    function hideAutocompleteEmployee() {
        employeeautocompleteList.classList.add('d-none');
        employeeautocompleteList.innerHTML = '';
        currentFocus = -1;
    }

    // Выбор сотрудника
    function selectEmployee(employee) {
        employeeInput.value = `${employee.name} `;
        accountInput.value = `${employee.account} `;
        employeeIdInput.value = employee.guid;
        hideAutocompleteEmployee();
    }

//=========================================================PCName=======================================================
    // Обработчик ввода текста
    PCNameInput.addEventListener('input', function(e) {
        const query = e.target.value.trim();
        if (query.length < 2) {
            hideAutocompletePCName();
            return;
        }
        searchPCNames(query);
    });

    // Обработчик нажатия клавиш
    PCNameInput.addEventListener('keydown', function(e) {
        const items = PCNameautocompleteList.getElementsByTagName('div');
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
            hideAutocompletePCName();
        }
    });

    // Закрытие автодополнения при клике вне области
    document.addEventListener('click', function(e) {
        if (!PCNameInput.contains(e.target) && !PCNameautocompleteList.contains(e.target)) {
            hideAutocompletePCName();
        }
    });

    // Поиск сотрудников
    function searchPCNames(query) {
        const formData = new FormData();
        formData.append('action', 'search_employees');
        formData.append('query', query);

        fetch('?page=allpc&action=comboboxsearch&str=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
            showAutocompletePCName(data);
        })
        .catch(error => {
            console.error('Ошибка:', error);
            hideAutocompletePCName();
        });
    }

    // Показать автодополнение
    function showAutocompletePCName(PCNames) {
        if (PCNames.length === 0) {
            hideAutocompletePCName();
            return;
        }

        PCNameautocompleteList.innerHTML = '';
        PCNames.forEach(PCName => {
            const item = document.createElement('div');
            item.innerHTML = `<strong>${PCName.name}</strong>`;
            item.dataset.id = PCName.id;

            item.addEventListener('click', function() {
                selectPCName(PCName);
            });

            PCNameautocompleteList.appendChild(item);
        });

        PCNameautocompleteList.classList.remove('d-none');
        currentFocus = -1;
    }

    // Скрыть автодополнение
    function hideAutocompletePCName() {
        PCNameautocompleteList.classList.add('d-none');
        PCNameautocompleteList.innerHTML = '';
        currentFocus = -1;
    }

    // Выбор сотрудника
    function selectPCName(PCName) {
        PCNameInput.value = `${PCName.name} `;
        PCNameIdInput.value = PCName.id;
        hideAutocompletePCName();
    }





///////////////////
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


        async function deletePage() {
            if (confirm('Вы уверены, что хотите удалить эту заявку?')) {
                 $id = document.getElementById('page_id').value;
                 console.log($id);
                const response = await fetch('?page=requestedit&api&action=delete', {
                                        method: 'POST',
                                        body: JSON.stringify({id: $id
                                    })
                            });
                console.log(response);
                const result = await response.json();
                console.log(result);

                if (result.success===true) {
                    alert('Данные успешно удалены!');
                    document.location.href = '?page=allrequests&pg=1&curpg=1';

                } else {
                    alert('Ошибка: ' + result.message);
                }

            }
        }

    </script>
</body>
</html>
