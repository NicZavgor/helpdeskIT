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
        .clickable-card {
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .clickable-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        .card-clickable {
            cursor: pointer !important;
        }

        .card-clickable:hover {
            cursor: pointer !important;
        }
            #video-background {
                position: fixed;
                left: 0px;
                top: 70px;
                min-width: 100%;
                min-height: 100%;
                width: auto;
                height: auto;
                z-index: -1;
                object-fit: cover;
                padding-left: 5%;
                padding-right: 5%;
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
    <?php if(isAuthorized()):?>
    <div class="mb-3" style="padding-top: 20px;padding-left: 1%;  padding-right: 1%;">
        <main >
                <div class="row mb-10">


                     <div class="col-md-6">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card clickable-card text-white bg-primary bg-gradient" style="width: 9rem;" onclick="window.location.href='?page=allrequests&pg=1&curpg=1&status=1'" >
                                    <div class="card-body">
                                        <h6 class="card-title">Открыты</h6>
                                        <h2 class="card-text"><?=$CountRequest[0]?></h2>
                                        <p class="card-text"><small></small></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card clickable-card text-white bg-success bg-gradient" style="width: 9rem;" onclick="window.location.href='?page=allrequests&pg=1&curpg=1&status=5'" >
                                    <div class="card-body">
                                        <h6 class="card-title">Решены</h6>
                                        <h2 class="card-text"><?=$CountRequest[1]?></h2>
                                        <p class="card-text"><small></small></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card clickable-card text-white bg-secondary bg-gradient" style="width: 9rem;" onclick="window.location.href='?page=allrequests&pg=1&curpg=1&status=2'" >
                                    <div class="card-body">
                                        <h6 class="card-title">В работе</h6>
                                        <h2 class="card-text"><?=$CountRequest[2]?></h2>
                                        <p class="card-text"><small></small></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card clickable-card text-white bg-warning bg-gradient" style="width: 9rem;" onclick="window.location.href='?page=allrequests&pg=1&curpg=1&status=4'" >
                                    <div class="card-body">
                                        <h6 class="card-title">На проверке</h6>
                                        <h2 class="card-text"><?=$CountRequest[3]?></h2>
                                        <p class="card-text"><small></small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mb-10">
                            <div class="col-md-3">
                                <div class="card clickable-card text-white bg-primary bg-gradient" style="width: 9rem;" onclick="window.location.href='?page=allrequests&pg=1&curpg=1&status=100&priority=2'" >
                                    <div class="card-body">
                                        <h6 class="card-title">Текущие</h6>
                                        <h2 class="card-text"><?=$CountRequest[4]?></h2>
                                        <p class="card-text"><small></small></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card clickable-card text-white bg-warning bg-gradient" style="width: 9rem;" onclick="window.location.href='?page=allrequests&pg=1&curpg=1&status=100&priority=3'" >
                                    <div class="card-body">
                                        <h6 class="card-title">Оперативные</h6>
                                        <h2 class="card-text"><?=$CountRequest[5]?></h2>
                                        <p class="card-text"><small></small></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card clickable-card text-white bg-danger bg-gradient" style="width: 9rem;" onclick="window.location.href='?page=allrequests&pg=1&curpg=1&status=100&priority=4'" >
                                    <div class="card-body">
                                        <h6 class="card-title">Срочные</h6>
                                        <h2 class="card-text"><?=$CountRequest[6]?></h2>
                                        <p class="card-text"><small></small></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card clickable-card text-white bg-gradient" style="background-color: #ff707b; border-color: #ff69b4; width: 9rem;" onclick="window.location.href='?page=allrequests&pg=1&curpg=1&status=100&deadline=true'" >
                                    <div class="card-body">
                                        <h6 class="card-title">Просрочены</h6>
                                        <h2 class="card-text"><?=$CountRequest[7]?></h2>
                                        <p class="card-text"><small></small></p>
                                    </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="mb-4">
                    <div >
                        <div >
                            <h3>Аналитика</h3>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            Статистика заявок за месяц
                                        </div>
                                        <div class="card-body">
                                            <canvas id="ticketsChart" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            Распределение по категориям
                                        </div>
                                        <div class="card-body">
                                            <canvas id="categoriesChart" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            Активность
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group ">
                                                <?php foreach ($RequestWithLastChanges as $request): ?>
                                                    <li class="list-group-item activity-item" onclick="location.href='?page=request&id=<?=$request['idrequest']?>&rp=m'" >
                                                        <div class="activity-header" >

                                                            <span class="text-bold">#<?=$request['idrequest']?></span>
                                                            <span>
                                                                    <?php if($request['status']==1):?>
                                                                        <td><span class="badge bg-primary bg-gradient"> <?=$request['status_name']?> </span></td>
                                                                    <?php elseif($request['status']==2): ?>
                                                                        <td><span class="badge bg-secondary bg-gradient text-white"> <?=$request['status_name']?> </span></td>
                                                                    <?php elseif($request['status']==3): ?>
                                                                        <td ><span class="badge bg-info bg-gradient text-white"> <?=$request['status_name']?> </span></td>
                                                                    <?php elseif($request['status']==4): ?>
                                                                        <td><span class="badge bg-success bg-gradient text-white"> <?=$request['status_name']?> </span></td>
                                                                    <?php elseif($request['status']==5): ?>
                                                                        <td><span class="badge bg-warning bg-gradient text-white"> <?=$request['status_name']?></span> </td>
                                                                    <?php endif;?>
                                                            </span>
                                                            <span class="text-muted small"><?= (new DateTime($request['dt']))->format('Y.m.d H:i:s')?></span>
                                                        </div>

                                                        <div ><small class="text-muted"><?=$request['topic']?></small></div>
                                                    </li>
                                                <?php endforeach; ?>
<!---
<div  class="d-flex justify-content-between align-items-center mb-2 ">
                                                        <span>Заявка #<?=$request['idrequest']?> </span><br>
                                                        взять с карточки wiki
                                                        № заявки, новый статус,  время назад
                                                        имя заявки


                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>Новая заявка от Иванова И.</spa d-flex justify-content-between align-items-centern>
                                                    <small class="text-muted">15 мин назад</small>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>Заявка #2453 решена</span>
                                                    <small class="text-muted">1 час назад</small>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>Петров П. добавил комментарий</span>
                                                    <small class="text-muted">3 часа назад</small>
                                                </li>
                                            -->
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </main>
    </div>
<?php else: ?>
    <div>

    <video autoplay muted loop id="video-background">
        <!-- Замените ссылку на ваше видео -->
        <source src="/Seamless.mp4" type="video/mp4">
        Ваш браузер не поддерживает видео тег.
    </video>
    </div>


<?php endif;?>


    <!-- Chart.js для графиков -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Инициализация графиков
        document.addEventListener('DOMContentLoaded', function() {
            // График статистики тикетов
            const ticketsCtx = document.getElementById('ticketsChart').getContext('2d');

            const ticketsChart = new Chart(ticketsCtx, {
                type: 'bar',
                data: {
                    labels: [ <?=$StatusProportion[0]?>
                    ],
                    datasets: [
                        {
                            label: 'Новых',
                            data: [ <?=$StatusProportion[2]?>
                            ],
                            backgroundColor: 'rgba(13, 110, 253, 0.7)',
                        },
                        {
                            label: 'Закрыто',
                            data: [ <?=$StatusProportion[1]?>
                            ],
                            backgroundColor: 'rgba(25, 135, 84, 0.7)',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });





            // График распределения по категориям
            const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
            const categoriesChart = new Chart(categoriesCtx, {
                type: 'doughnut',
                data: {
                    labels: [<?=$CategoriesProportion[0]?>



                    ],
                    datasets: [{
                        data: [
                            <?=$CategoriesProportion[1]?>
                        ],
                        backgroundColor: [
                            <?=$CategoriesProportion[2]?>
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true
                }
            });
        });
    </script>
</body>
</html>
