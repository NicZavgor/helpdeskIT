<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body style="padding-left: 5%;  padding-right: 5%;">
<?php include 'menu.php';?>
<div>
    <img src="/title2.png" class="img-fluid" >
</div>

<div class="mb-3" style="padding-top: 20px;padding-left: 10%;  padding-right: 10%;">
<?php if(isAuthorized()):?>

                            <div class="row g-7">

                                <div class="col-sm-9">
                                    <h2><?=$wikiTopic[0]['theme']?></h2>
                                </div>
                                <div class="col-sm-2">
                                    <i>Обновлено <?= (new DateTime($wikiTopic[0]['upd_dt']))->format('Y.m.d')?></i>
                                    <i><?= $wikiTopic[0]['idcategory_name']?></i>
                                </div>
                                <div class="col-sm-1">
                                    <a href="?page=article&editid=<?=$wikiTopic[0]['id']?>" class="btn btn-outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                        </svg>
                                    </a>
                                </div>

                            </div>
                            <h5><?=$wikiTopic[0]['description']?></h5>



                    <div class="col-lg-12">
                        <!-- Контент статьи -->
                        <article>
                            <?=$wikiTopic[0]['text']?>

                        </article>

                        <!-- Навигация
                        <div class="d-flex justify-content-between mt-5 pt-4 border-top">
                            <a href="#" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left"></i> Предыдущая статья
                            </a>
                            <a href="#" class="btn btn-outline-primary">
                                Следующая статья <i class="bi bi-arrow-right"></i>
                            </a>
                        </div> -->

                        <!--
                        <div class="card mt-4">
                            <div class="card-body text-center">
                                <h5 class="card-title">Была ли эта статья полезной?</h5>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-success">
                                        <i class="bi bi-hand-thumbs-up"></i> Да
                                    </button>
                                    <button type="button" class="btn btn-outline-danger">
                                        <i class="bi bi-hand-thumbs-down"></i> Нет
                                    </button>
                                </div>
                            </div>
                        </div> -->
                    </div>


<?php else: ?>
    <div class="alert alert-danger" role="alert">
        Вы не имеете доступа к данной странице - авторизируйтесь
    </div>
<?php endif;?>


</body>
</html>
