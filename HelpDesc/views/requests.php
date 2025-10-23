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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body style="padding-left: 5%;  padding-right: 5%;">
<?php include 'menu.php';?>
<div>
    <img src="/title2.png" class="img-fluid" >
</div>

<div class="mb-3" style="padding-top: 20px;padding-left: 1%;  padding-right: 1%;">
<?php if(isAuthorized()):?>
    <div class="row g-7">
        <div class="col-sm-12">
                    <div class="d-flex justify-content-between mb-3">
                         <h3>Заявки</h3>
                         <div class="col-sm-1">
                              <button class="btn btn-primary" value="Создать" data-bs-toggle="modal" data-bs-target="#editModal" onclick="location.href='?page=requestedit&new'">Создать </button>
                         </div>
                    </div>


                    <div class="card sm-1" style="height: 4.5rem;">
                        <div class="card-body">
                             <form class="form-group row" action="?page=allrequests"  method="post" name="Search" id="Search">
                                <div class="col-sm-2">
                                    <div class="btn-group">

                                       <div class="input-group mb-3">
                                           <span class="input-group-text" id="basic-addon1">Статус</span>
                                           <select class="form-select" aria-label="Статус" id="StatusID" onchange="doSearch(); return false;" >
                                               <option value="0" <?php if(isset($status)and $status===0):?> selected <?php endif;?> >Все</option>
                                               <option value="1" <?php if(isset($status)and $status===1):?> selected <?php endif;?>>Новая</option>
                                               <option value="2" <?php if(isset($status)and $status===2):?> selected <?php endif;?>>В работе</option>
                                               <option value="3" <?php if(isset($status)and $status===3):?> selected <?php endif;?>>На паузе</option>
                                               <option value="4" <?php if(isset($status)and $status===4):?> selected <?php endif;?>>На проверке</option>
                                               <option value="5" <?php if(isset($status)and $status===5):?> selected <?php endif;?>>Закрыта</option>
                                               <option value="100" <?php if(isset($status)and $status===100):?> selected <?php endif;?>>Акутальные</option>
                                           </select>
                                       </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="btn-group">
                                       <div class="input-group mb-3">
                                           <span class="input-group-text" id="basic-addon1">Приоритет</span>
                                           <select class="form-select" aria-label="Приоритет" id="Priority" onchange="doSearch(); return false;" >
                                               <option value="0" <?php if(isset($priority)and $priority===0):?> selected <?php endif;?>>Все</option>
                                               <option value="1" <?php if(isset($priority)and $priority===1):?> selected <?php endif;?>>Пожелание</option>
                                               <option value="2" <?php if(isset($priority)and $priority===2):?> selected <?php endif;?>>Мало важная</option>
                                               <option value="3" <?php if(isset($priority)and $priority===3):?> selected <?php endif;?>>Важная</option>
                                               <option value="4" <?php if(isset($priority)and $priority===4):?> selected <?php endif;?>>Очень важная</option>
                                           </select>
                                       </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="btn-group">
                                       <div class="input-group mb-3">
                                           <span class="input-group-text" id="basic-addon1">Категория</span>
                                           <select class="form-select" aria-label="Категория" id="Category" onchange="doSearch(); return false;" >
                                               <option value="0" <?php if(isset($category)and $category===0):?> selected <?php endif;?>>Все</option>
                                               <?php foreach ($AllCategories as $OneCat): ?>
                                                    <?php if(isset($category)):?>
                                                        <option value="<?=$OneCat['id']?>"
                                                            <?php if(isset($category)and ($category==$OneCat['id'])) :?>
                                                                selected
                                                            <?php endif;?>

                                                        ><?=$OneCat['name']?></option>
                                                    <?php else:?>
                                                        <option value="<?=$OneCat['id']?>"><?=$OneCat['name']?></option>
                                                    <?php endif;?>

                                               <?php endforeach; ?>
                                           </select>
                                       </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="SearchText" placeholder="Введите строку поиска" aria-describedby="button-addon2"
                                        <?php if(isset($searchStr)):?>
                                            value="<?=$searchStr?>"
                                        <?php endif;?>
                                        >
                                        <input type="submit" name="ok" class="btn btn-outline-secondary" id="button-addon2" value="Найти" onclick="doSearch(); return false;">
                                    </div>
                                </div>
                                <div class="col-sm-2 my-2">
                                    <div class="form-check form-switch">
                                      <input class="form-check-input" type="checkbox" role="switch" id="showDeadline" onclick="doSearch(); return false;"
                                            <?php if(isset($deadline) and $deadline==true):?>
                                                checked
                                            <?php endif;?>
                                      >
                                      <label class="form-check-label" for="showDeadline">Просроченные</label>
                                    </div>
                                </div>
                             </form>
                        </div>
                    </div>


        </div>
    </div>
    <hr class="border border-primary border-3 opacity-75">
    <table  class="table table-hover" >
        <thead ><tr>
            <th scope="col">№</th>
            <th scope="col">Статус</th>
            <th scope="col">Приоритет</th>
            <th scope="col">Категория</th>
            <th scope="col">Тема</th>
            <th scope="col">Сотрудник (учетка)</th>
            <th scope="col">Ответственный</th>
            <th scope="col">Имя ПК</th>
            <th scope="col">Создана</th>
            <th scope="col">Срок</th>
            <th scope="col"></th>
        </tr></thead>
        <tbody >
        <?php foreach ($requests as $request): ?>
            <?php if((new DateTime($request['deadline']))<(new DateTime(date('Y-m-d H:i:s'))) and $request['status_proc']!=4):?>
                <tr class="table-danger"  id="item_<?=$request['id']?>" >
            <?php else: ?>
                <tr id="item_<?=$request['id']?>" >
            <?php endif;?>
                <th scope="row">
                    <?=$request['id']?>
                </th>
                <?php if($request['status_proc']==1):?>
                    <td><span class="badge bg-primary bg-gradient text-white"> <?=$request['status_proc_name']?> </span></td>
                <?php elseif($request['status_proc']==2): ?>
                    <td><span class="badge bg-secondary bg-gradient text-white"> <?=$request['status_proc_name']?> </span></td>
                <?php elseif($request['status_proc']==3): ?>
                    <td ><span class="badge bg-info bg-gradient text-white"> <?=$request['status_proc_name']?> </span></td>
                <?php elseif($request['status_proc']==4): ?>
                    <td><span class="badge bg-warning bg-gradient text-white"> <?=$request['status_proc_name']?> </span></td>
                <?php elseif($request['status_proc']==5): ?>
                    <td><span class="badge bg-success bg-gradient text-white"> <?=$request['status_proc_name']?></span> </td>
                <?php endif;?>
                <?php if($request['urgency']==1 and $request['status_proc']!=5): ?>
                    <td><span class="badge bg-success bg-gradient text-white "> <?=$request['urgency_name']?></span> </td>
                <?php elseif($request['urgency']==2 and $request['status_proc']!=5): ?>
                    <td><span class="badge bg-primary bg-gradient text-white "> <?=$request['urgency_name']?></span> </td>
                <?php elseif($request['urgency']==3 and $request['status_proc']!=5): ?>
                    <td><span class="badge bg-warning bg-gradient text-white "> <?=$request['urgency_name']?></span> </td>
                <?php elseif($request['urgency']==4 and $request['status_proc']!=5): ?>
                    <td><span class="badge bg-danger bg-gradient text-white"> <?=$request['urgency_name']?> </span></td>
                <?php elseif($request['urgency']==1 and ($request['status_proc']==5)): ?>
                    <td><span class="badge btn btn-outline-success text-success "> <?=$request['urgency_name']?></span> </td>
                <?php elseif($request['urgency']==2 and ($request['status_proc']==5)): ?>
                    <td><span class="badge btn btn-outline-primary text-primary"> <?=$request['urgency_name']?></span> </td>
                <?php elseif($request['urgency']==3 and ($request['status_proc']==5)): ?>
                    <td><span class="badge btn btn-outline-warning text-warning"> <?=$request['urgency_name']?></span> </td>
                <?php elseif($request['urgency']==4 and ($request['status_proc']==5)): ?>
                    <td><span class="badge btn btn-outline-danger text-danger"> <?=$request['urgency_name']?> </span></td>
                <?php else:?>
                    <td > <?=$request['urgency_name']?> </td>
                <?php endif;?>
                <td>
                    <?=$request['idcategory_name']?>
                </td>
                <td>
                    <?=$request['topic']?>
                </td>
                <td>
                    <?=$request['employeename']?> <br> <?='('.$request['account'].')'?>
                </td>
                <td>
                    <?=$request['name_users']?> <br> <?='('.$request['itrole_proc_name'].')'?>
                </td>
                <td>
                    <?=$request['pcname']?>
                </td>
                <td>
                    <?= (new DateTime($request['dt']))->format('Y.m.d')?>
                </td>
                <td>
                    <?=(new DateTime($request['deadline']))->format('Y.m.d')?>
                </td>
                <td>
                    <a href="?page=request&id=<?=$request['id']?>&rp=a" class="btn btn-outline-primary">...</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <nav aria-label="...">
        <ul class="pagination">
        <?php if($newpg<=2):?>
            <li class="page-item disabled"> <span class="page-link">Предыдущая</span> </li>
        <?php else: ?>
            <li class="page-item"> <a class="page-link" href="?page=allrequests&pg=prior&curpg=<?=$newpg?><?=$AddSearchStrURL?>">Предыдущая</a> </li>
        <?php endif;?>


        <?php if($newpg>1):?>
            <li class="page-item"><a class="page-link" href="?page=allrequests&pg=<?=$newpg-1?>&curpg=<?=$newpg?><?=$AddSearchStrURL?>"><?=$newpg-1?></a></li>
        <?php endif;?>


        <li class="page-item active"> 
            <span class="page-link">
                <?=$newpg?>        
            </span>
        </li>

        <?php if($newpg<=$pgcount-1):?>
            <li class="page-item"><a class="page-link"  href="?page=allrequests&pg=<?=$newpg+1?>&curpg=<?=$newpg?><?=$AddSearchStrURL?>"><?=$newpg+1?></a></li>
        <?php endif;?>

        <?php if($newpg>=$pgcount-2):?>
            <li class="page-item disabled"> <span class="page-link">Следующая</span> </li>
        <?php else: ?>
            <li class="page-item"> <a class="page-link" href="?page=allrequests&pg=next&curpg=<?=$newpg?><?=$AddSearchStrURL?>">Следующая</a> </li>
        <?php endif;?>
        </ul>
    </nav>
    </div>
<?php else: ?>

    <div class="alert alert-danger" role="alert">
        Вы не имеете доступа к данной странице - авторизируйтесь
    </div>    


<?php endif;?>

<script>
    ///////////////ПОИСК//////////////////
    //аналог ф-ции в php
    function htmlspecialchars(str) {
        str = str.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#039;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return str;
    }
    //поиск
    function doSearch(){
        if (htmlspecialchars(document.getElementById('SearchText').value) != "" || document.getElementById('StatusID').value!='0'|| document.getElementById('Priority').value!='0' || document.getElementById('Category').value!='0'|| document.getElementById('showDeadline').checked==true) {
            var s = document.getElementById('Search').action;
            s = s+"&pg=1&curpg=1";
            if (htmlspecialchars(document.getElementById('SearchText').value)!=""){
                s = s+"&action=search&str="+htmlspecialchars(document.getElementById('SearchText').value);
            }
            if (document.getElementById('StatusID').value!=0){
                s = s+"&status="+htmlspecialchars(document.getElementById('StatusID').value);
            }
            if (document.getElementById('Priority').value!=0){
                s = s+"&priority="+htmlspecialchars(document.getElementById('Priority').value);
            }
            if (document.getElementById('Category').value!=0){
                s = s+"&category="+htmlspecialchars(document.getElementById('Category').value);
            }
            if (document.getElementById('showDeadline').checked==true){
                s = s+"&deadline=true";
            }
            document.getElementById('Search').action=s;
        }else{
            var s = document.getElementById('Search').action;
            s = s+"&pg=1&curpg=1";
            document.getElementById('Search').action=s;
        }
        document.getElementById("Search").submit();
    }





    //удалить звук
    function deleteSound(sound_id) {        
        //var res = confirm('Вы действительно собираетесь безвозвратно удалить этот звук? Для подтвержения действия нажмите "Ok"');
        //if (res) {
            (async ()=>{
                const response = await fetch('?page=moder&api&action=delSound',
                    {method: 'POST',
                    headers: {'Content-Type': 'application/json;charset=utf-8'},
                    body: JSON.stringify({id: sound_id})
                    });
                const answer = await response.text();
                if (answer=="ok"){
                        document.getElementById("item_" + sound_id).remove();
                        location.reload();
                    }else{
                    }
            })();
        //}
    }
</script>
</body>
</html>
