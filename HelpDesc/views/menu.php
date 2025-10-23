<?php if(isAuthorized()):?>
<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
    <div>
    <img src="/logo.png" class="img-fluid" >
    </div>
    <b>Helpdesk</b>
    <div class="container-fluid">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="/">Главная</a></li>
            <li class="nav-item">
                <a class="nav-link" href="/?page=kanban">Канбан</a></li>
            <?php if(isAdmin()):?>
                <li class="nav-item">
                    <a class="nav-link" href="/?page=allrequests&status=100&pg=1&curpg=1">Все заявки</a></li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="/?page=moder&pg=1&curpg=1">Заявки</a></li>
            <?php endif;?>
            <li class="nav-item">
                <a class="nav-link" href="/?page=projects&pg=1&curpg=1">Проекты</a></li>
            <li class="nav-item">
                <a class="nav-link" href="/?page=allpc&pg=1&curpg=1">ПК</a></li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Организация</a>
                <ul class="dropdown-menu">
                   <a class="nav-link" href="/?page=subdivisions">Структура организации</a></li>
                   <a class="nav-link" href="/?page=employees&pg=1&curpg=1">Сотрудники</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/?page=wiki&pg=1&curpg=1">База знаний</a></li>
            <?php if(isAdmin()):?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Настройки</a>
                <ul class="dropdown-menu">
                   <a class="nav-link" href="/?page=users&pg=1&curpg=1">Пользователи</a></li>
                   <a class="nav-link" href="/?page=categories&pg=1&curpg=1">Категории</a></li>
                </ul>
            </li>
            <?php else: ?>
            <?php endif;?>
            <li class="nav-item">
                <a class="nav-link" href="/?page=about">О нас</a></li>
        </ul>
    </div>
    <div class="dropdown me-2">
        <button class="btn btn-outline-secondary dropdown-toggle " type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-bell"></i>
            <span class="badge bg-danger"><?=$CountRequestWithLastChatMessages?></span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end " aria-labelledby="dropdownMenuButton" data-popper-placement="bottom-end" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 40px);">
            <?php foreach ($RequestWithLastChatMessages as $ChMsg): ?>
                <li><a class="dropdown-item" href="?page=request&id=<?=$ChMsg['idrequest']?>&rp=m">#<?=$ChMsg['idrequest']." ". substr($ChMsg['msg'],0,15) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>


    <form action="/?action=logout" method="post">        
        <input type="submit" class="btn btn-outline-secondary" value="Выйти <?=getUserName()?> " />         
    </form>
</nav>

<?php else: ?>
<form action="/?action=login" method="post">
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
        <div>
            <img src="/media/img/logo2.png" class="img-fluid" >
        </div>
        <div class="container-fluid">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/">Главная</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="/?page=about">О нас</a></li>
            </ul>
        </div>
        <div class="input-group mb-3">  
            <input type="text" class="form-control" placeholder="Пользователь"  name="login"> 
            <input type="password" class="form-control" placeholder="Пароль"  name="password">            
                     
            <input type="submit" class="btn btn-outline-secondary" name="ok" value="Войти">
            <button type="button" class="btn btn-outline-secondary" onclick="location.href='?page=newuser'">Регистрация</button>                    
        </div>
        <?php if(isset($message)):?>        
            <p style="color: red" id="message"><?=$message?></p>
        <?php endif;?>
    </nav>
</form>
<?php endif;?>
