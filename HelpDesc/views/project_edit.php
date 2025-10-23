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
        <form id="pageForm" <?php if($project[0]['id']===0):?> action="?page=projectedit&new" <?php else: ?> action="?page=projectedit&editid=$project[0]['id']"<?php endif;?> method="post">
            <input type="hidden" name="page_id" id="page_id" value="<?=$project[0]['id']?>">

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
                                      <?php if($project[0]['id']===0):?>
                                            Новый проект
                                      <?php else: ?>
                                            Данные по проекту №<?=$project[0]['id']?>
                                      <?php endif;?>
                                  </div>
                              </div>
                          </div>

                          <div class="container">
                              <div class="row">
                                  <div class="col-12">
                                      <div class="mb-3">
                                        <label for="editTopic" class="form-label">Наименование</label>
                                        <input type="text" class="form-control" id="editTitle" value="<?=$project[0]['title']?>" required>
                                      </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-3">
                                      <div class="mb-3">
                                          <label for="editPriority" class="form-label">Приоритет</label>
                                          <select class="form-select form-select-sm status-select"  id="editPriority">
                                            <?php foreach ($AllPriorities as $one): ?>
                                                <option value="<?= $one['id'] ?>"   <?php if($project[0]['urgency']===$one['id']):?> selected <?php endif;?>   >
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
                                                <option value="<?= $one['id'] ?>"   <?php if($project[0]['idcategory']===$one['id']):?> selected <?php endif;?>   >
                                               <?= $one['name'] ?>
                                            </option>
                                            <?php endforeach; ?>
                                          </select>
                                      </div>
                                  </div>
                                  <div class="col-3">
                                      <label for="editstartdate" class="form-label">Начало</label>
                                      <!---<?=(new DateTime($project[0]['deadline']))->format('yyyy.mm.dd')?>-->
                                      <input type="date" class="form-control" id="editstartdate" required value=<?=$project[0]['start_date']?>>
                                  </div>
                                  <div class="col-3">
                                      <label for="editenddate" class="form-label">Оконачние</label>
                                      <!---<?=(new DateTime($project[0]['deadline']))->format('yyyy.mm.dd')?>-->
                                      <input type="date" class="form-control" id="editenddate" required value=<?=$project[0]['end_date']?>>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-12">
                                      <div class="mb-3">
                                          <label for="content" class="form-label">Описание проекта</label>
                                         <textarea class="form-control" id="editDescription" rows="8"><?=$project[0]['description']?></textarea>
                                      </div>
                                  </div>
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
        title = htmlspecialchars(document.getElementById('editTitle').value);
        description = htmlspecialchars(document.getElementById('editDescription').value);
        urgency = (document.getElementById('editPriority').value);
        idcategory = (document.getElementById('editCategory').value);
        start_date = (document.getElementById('editstartdate').value);
        end_date = (document.getElementById('editenddate').value);


        //$text = (document.getElementById('hiddenContent').value);

        try {
             const response = await fetch('?page=projectedit&action=pst', {
                                 method: 'POST',
                                 body: JSON.stringify({
                                    id: id,
                                    title: title,
                                    description: description,
                                    idcategory: idcategory,
                                    urgency: urgency,
                                    start_date: start_date,
                                    end_date: end_date
                                 })
                             });
            console.log(response);
            //const response = await fetch('models/dbcategories.php', { method: 'POST', body: formData});
            const result = await response.json();
            console.log(result);

            if (result.success===true) {
                alert('Данные успешно сохранены!');
                document.location.href = '?page=project&id='+result.returnID+'&rp=a';

            } else {
                alert('Ошибка: ' + result.message);
            }
        } catch (error) {
            console.error('Ошибка:', error);
            alert('Произошла ошибка при сохранении данных');
        }

    }
        async function deletePage() {
            if (confirm('Вы уверены, что хотите удалить эту заявку?')) {
                 $id = document.getElementById('page_id').value;
                 console.log($id);
                const response = await fetch('?page=projectedit&action=delete', {
                                        method: 'POST',
                                        body: JSON.stringify({id: $id
                                    })
                            });
                console.log(response);
                const result = await response.json();
                console.log(result);

                if (result.success===true) {
                    alert('Данные успешно удалены!');
                    document.location.href = '?page=projects&pg=1&curpg=1';

                } else {
                    alert('Ошибка: ' + result.message);
                }

            }
        }

    </script>
</body>
</html>
