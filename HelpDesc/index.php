<?php

require_once __DIR__ . '/app/db.php';
require_once __DIR__ . '/app/db_init.php';
require_once __DIR__ . '/app/auth.php';
require_once __DIR__ . '/models/dbrequests.php';
require_once __DIR__ . '/models/dbfunctions.php';
require_once __DIR__ . '/models/dbcategories.php';
require_once __DIR__ . '/models/dbwiki.php';
require_once __DIR__ . '/models/dbusers.php';
require_once __DIR__ . '/models/dbdushboard.php';
require_once __DIR__ . '/models/dbemployee.php';
require_once __DIR__ . '/models/dbpc.php';
require_once __DIR__ . '/models/dbprojects.php';
require_once __DIR__ . '/models/dbkanban.php';

//require_once __DIR__ . '/models/categories.php';
//require_once __DIR__ . '/models/users.php';


$page = $_GET['page'] ?? 'index';
/*
print_r('$_POST');
var_dump($_POST);
print_r($_POST);
print_r('$_GET');
var_dump($_GET);
print_r($_GET);
print_r('$_SESSION');
var_dump($_SESSION);
print_r($_SESSION);
*/
/*
$out[] = json_decode(file_get_contents('php://input'));
$answer = json_encode($out);
print_r($out[0]->{'id'});
print_r($out[0]->{'mad'});*/
//echo $out['id'];


//exit();
if (isset($_SESSION['userid'])){
    $RequestWithLastChatMessages =  getRequestWithLastChatMessages((int)$_SESSION['userid']);
    $CountRequestWithLastChatMessages = count($RequestWithLastChatMessages);
}
switch ($page) {
    case 'kanban':
        if (isset($_SESSION['userid'])){
            $action = $_GET['action'] ?? '';

            switch($action) {
                case 'getTasks':
                    echo json_encode(getKanbanTasks((int)$_SESSION['userid']));
                    exit();
                case 'getTasksInfo':
                    echo json_encode(getKanbanOneTask((string)$_GET['task'] ));
                    exit();
                case 'updateTaskStatus':
                    /*$out[] = json_decode(file_get_contents('php://input'));
                    $id=$out[0]->{'taskId'};
                    $status =$out[0]->{'status'};
                    $itrole =htmlspecialchars($out[0]->{'desc'});
                    die(print_r($out[0]));*/
                    $taskId = htmlspecialchars($_POST['taskId']);
                    $newStatus = (int)$_POST['status'];
                    $desc = htmlspecialchars($_POST['desc']);
                    $id = intval(substr($_POST['taskId'], 1, strlen($_POST['taskId'])), 10);
                    //die(print_r($id));
                    if (substr($_POST['taskId'], 0, 1)=='r'){
                        $result = updateRequestProc($id, $desc, $newStatus, (int)$_SESSION['userid']);
                        if ($result=='') {$result=true;} else{$result=false;}
                    }else{
                        $result = updateProjectTaskStatus($id, $desc, $newStatus, (int)$_SESSION['userid']);
                        if ($result=='') {$result=true;} else{$result=false;}
                    }
                    //$result = updateKanbanTask($taskId, ['status' => $newStatus]);
                    echo json_encode(['success' => $result]);
                    exit();
            }
            include __DIR__ . '/views/kanban.php';
        }
        break;
    case 'projectedit':
        if (isset($_SESSION['userid'])){
            $userid = (int)$_SESSION['userid'];
            $action = $_GET['action'] ?? '';
            switch($action) {
                case 'new':
                    $editid =0;
                    $project=array(['id'=>0,'title'=>'','description'=>'','idcategory'=>0, 'urgency'=>0, 'start_date'=>date('Y-m-d'), 'end_date'=>date('Y-m-d', strtotime('+1 day'))]);
                    $AllCategories = getAllCategories();
                    $AllPriorities = getAllPriorities();
                    $ReturnURL = "?page=projects&pg=1&curpg=1";
                    include './views/project_edit.php';
                    break;
                case 'edit':
                    if (isset($_GET['id']) ) {
                        $editid = (int)$_GET['id'];
                        $project=getOneProject($editid);
                        //$select = "select id,pcname,idpc, lst_typename, lst_modelname, lst_typemodelname,idpreviosrequest, account,employeename,urgency,urgency_name, message,finalstatus, job_name,subdivision_name,dt,topic,deadline,iduser_proc,itrole_proc,itrole_proc_name, dt_proc,description_proc,status_proc,status_proc_name,name_users,role_users,employeeguid_users,blocking_users, guidemployee FROM full_request_info  where id = :id";
                        //array(['id'=>0,'topic'=>'','message'=>'','idcategory'=>0, 'urgency'=>0, 'iduser_proc'=>0,'deadline'=>date('Y-m-d', strtotime('+1 day'))]);

                        $AllCategories = getAllCategories();
                        $AllPriorities = getAllPriorities();
                        $ReturnURL = "?page=project&id=".$editid;
                        include './views/project_edit.php';
                    }
                    break;
                case 'pst':
                    $out[] = json_decode(file_get_contents('php://input'));
                    $id=(int)$out[0]->{'id'};
                    $taskData = [
                            'title' => htmlspecialchars($out[0]->{'title'}),
                            'description' => htmlspecialchars($out[0]->{'description'}),
                            'idcategory' => (int)($out[0]->{'idcategory'}),
                            'urgency' => (int)($out[0]->{'urgency'}),
                            'start_date' => $out[0]->{'start_date'},
                            'end_date' => $out[0]->{'end_date'}];
                    /*
                    $title = htmlspecialchars($out[0]->{'title'});
                    $description = htmlspecialchars($out[0]->{'description'});
                    $idcategory = (int)($out[0]->{'idcategory'});
                    $urgency = (int)($out[0]->{'urgency'});
                    $start_date = new DateTime($out[0]->{'start_date'});
                    $end_date = new DateTime($out[0]->{'end_date'});*/
                    //$ddd=$id." ".$topic." ".$message." ".$idcategory." ".$urgency." ".$ituser." ".$employee_name." ".$account." ".$PCName;

                    if ($id!==0){
                        $msg = updateProject($id, $taskData);
                        if ($msg === '') {
                            echo json_encode(['success' => true, 'message' => 'Данные сохранены', 'returnID' => (string)$id]);
                        }
                        else{
                            echo json_encode(['success' => false, 'message' => 'Ошибка сохранения данных'.$msg]);
                        }
                    }else{
                        $msg = insertProject($taskData);
                        if (is_numeric($msg)===true) {
                            echo json_encode(['success' => true, 'message' => 'Данные сохранены', 'returnID' => $msg]);
                        }
                        else{
                            echo json_encode(['success' => false, 'message' => 'Ошибка сохранения данных'.$msg]);
                        }
                    }
                    break;
                case 'delete':
                    $out[] = json_decode(file_get_contents('php://input'));
                    $id=(int)$out[0]->{'id'};
                    $msg = deleteProject($id);
                    if ($msg==='') {
                        echo json_encode(['success' => true, 'message' => 'Данные удалены']);
                    }
                    else{
                        echo json_encode(['success' => false, 'message' => 'Ошибка удаления данных'.$msg]);
                    }
                break;
            }

        }
        break;
    case 'project':
        if (isset($_SESSION['userid']) ){
            $allUsers = getListOfUsers();
            $AllCategories = getAllCategories();
            $AllPriorities = getAllPriorities();
            $action = $_GET['action'] ?? '';
            switch($action) {
                case 'getTasks':
                    if (isset($_GET['id']) ){
                        $id = (int)($_GET['id']);
                        $result=getProjectTasksTree($id);
                        echo $result;//json_encode(['success' => true]);
                        exit(); }
                    break;
                case 'getTasksInfo':
                    echo json_encode(getProjectOneTask((int)$_GET['id'] ));
                    exit();
                break;
                case 'getAllTaskTree':
                    echo json_encode(getProjectTasksTreeItems((int)$_GET['id'] ));
                    exit();
                break;
                case 'getAllTaskTreeByProject':
                    echo json_encode(getProjectTasksTreeByProject((int)$_GET['id'] ));
                    exit();
                break;
                case 'updateTaskStatus':
                    /*$out[] = json_decode(file_get_contents('php://input'));
                    $id=$out[0]->{'taskId'};
                    $status =$out[0]->{'status'};
                    $itrole =htmlspecialchars($out[0]->{'desc'});
                    die(print_r($out[0]));*/
                    $taskId = (int)$_POST['taskId'];
                    $newStatus = (int)$_POST['status'];
                    $desc = htmlspecialchars($_POST['desc']);

                    $result = updateProjectTaskStatus($taskId, $desc, $newStatus, (int)$_SESSION['userid']);
                    if ($result=='') {$result=true;} else{$result=false;}

                    echo json_encode(['success' => $result]);
                    exit();

                case 'updateTask':
                    $parentId = NULL;
                    if ((int)$_POST['parentId']!=0) {$parentId = (int)$_POST['parentId'];}
                    $taskData = [
                            'idparent' => $parentId,
                            'idproject' => (int)$_POST['projectId'],
                            'title' => htmlspecialchars($_POST['taskName']),
                            'start_date' => $_POST['taskStart'],
                            'end_date' => $_POST['taskEnd'],
                            'description' => htmlspecialchars($_POST['taskDesc']),
                            'urgency' => (int)$_POST['taskPriority']];
                    $taskId = (int)$_POST['taskId'];
                    $taskIdUser = (int)$_POST['taskIdUser'];
                    $taskChangeStatus = (bool)$_POST['taskChangeStatus'];
                    $taskStatus = (int)$_POST['taskStatus'];
                    $taskStatusDesc = "";
                    if ($taskChangeStatus) {$taskStatusDesc = ($taskId!=0 ? "Смена исполнителя" : "");}
                    $errMsg = "";
                    if ($taskId!=0){
                        $result = updateProjectTask($taskId, $taskData);
                        if ($result!=true) {
                            $errMsg = $result;
                            $result=false;
                        }
                    }else{
                        $result = insertProjectTask($taskData);
                        $taskId = $result;
                        if ($result==false) {$errMsg ="запись не добавлена";}
                    }
                    if ($result==false) {
                        echo json_encode(['success' => $result, "error"=>$errMsg]);
                        exit();
                    }

                    if ($taskChangeStatus){
                        $result = updateProjectTaskStatus($taskId, $taskStatusDesc, (int)$taskStatus, (int)$taskIdUser);
                    }
                    if ($result=='') {$result=true;} else{$result=false;}

                    echo json_encode(['success' => $result]);
                    exit();
                case 'history':
                     $id=(int)$_GET['id'];
                     $arr = getTaskProc($id);
                     echo json_encode(['data' => $arr]);
                     exit();
                case 'deleteTask':
                    //echo json_encode(['id' => (int)$_POST['taskId']]);
                    //exit();
                    $msg = canDeleteProjectTask((int)$_POST['taskId']);
                    if ($msg==""){
                        $msg = DeleteProjectTask((int)$_POST['taskId']);
                        if ($msg==""){
                            echo json_encode(['success' => true]);
                            exit();
                        }else{
                            echo json_encode(['success' => false, "message"=>$msg]);
                            exit();
                        }
                    }else{
                        echo json_encode(['success' => false, "message"=>$msg]);
                        exit();
                    }
                    break;
                    exit();
                default:
                    if(isset($_GET['id']) ){
                        $id = (int)($_GET['id']);
                        $project = getOneProject((int)$id);
                        //echo $result;//json_encode(['success' => true]);
                    }
            }


        include __DIR__ . '/views/project.php';
        }
    break;
    case 'projects':
        if (isset($_SESSION['userid'])){
            $action = $_GET['action'] ?? '';
            $searchStr = '';
            if (isset($_GET['action']) && $_GET['action'] === 'search') {
                $searchStr = htmlspecialchars($_GET['str']);
            }
            $sortField=0;
            if (isset($_GET['sort']) ) {
                $sortField = (int)$_GET['sort'];
            }
            //die(print_r($searchStr));
            //$allroles = getRoles();
            //die(print_r($allroles));
            $newpg = 1;
            $curpg = 1;
            $pgcount = 1;

            if (isset($_GET['pg']) && isset($_GET['curpg'])) {
                $newpg = 1;
                if ($_GET['pg']==='prev') {
                    $curpg = (int)$_GET['curpg'];
                    $newpg = $curpg - 2;
                }elseif ($_GET['pg']==='next') {
                    $curpg = (int)$_GET['curpg'];
                    $newpg = $curpg + 2;
                }else{
                    $curpg = 0;
                    $newpg = (int)$_GET['pg'];
                }
                $All = getProjectsCount($searchStr);
                $pgcount = (int)$All/10;
                if ($All%10 != 0){  $pgcount++; }

                if ($newpg <=0) {$newpg = 1;}  elseif ($newpg > $pgcount) {$newpg = $pgcount;}
            }

            $Projects = getProjects($newpg, $sortField, $searchStr);//, $newpg
            include __DIR__ . '/views/projects.php';
        }
        break;
    case 'index':
        if (isset($_SESSION['userid'])){
            $CategoriesProportion = getRequestCategoryProportionToStr((int)$_SESSION['userid']);
            $StatusProportion = getRequestStatusProportionToStr((int)$_SESSION['userid']);
            $CountRequest =  getDushBoardCountRequest((int)$_SESSION['userid']);
            $RequestWithLastChanges =  getRequestWithLastChanges((int)$_SESSION['userid']);

            //die(print_r($CountRequest));
        }
        include __DIR__ . '/views/index.php';
        break;
    case 'myrequests':
        if (isset($_SESSION['userid'])){
            $newpg = 1;
            $curpg = 1;
            $pgcount = 1;
            if (isset($_GET['pg']) && isset($_GET['curpg'])) {
                $newpg = 1;
                if ($_GET['pg']==='prev') {
                    $curpg = (int)$_GET['curpg'];
                    $newpg = $curpg - 2;
                }elseif ($_GET['pg']==='next') {
                    $curpg = (int)$_GET['curpg'];
                    $newpg = $curpg + 2;
                }else{
                    $curpg = 0;
                    $newpg = (int)$_GET['pg'];
                }
                $requestCount = getMyRequestsCount((int)$_SESSION['userid']);
                $pgcount = (int)$requestCount/5;
                if ($requestCount%5 != 0){  $pgcount++; }

                if ($newpg <=0) {$newpg = 1;}  elseif ($newpg > $pgcount) {$newpg = $pgcount;}
            }

            $requests = getMyRequests((int)$_SESSION['userid'], $newpg);

            include './views/myrequests.php';
            break;
        }
    case 'allrequests':

        if (isset($_SESSION['userid'])){
            $searchStr = '';$sortField=0;  $status =0;$priority=0;$category=0;$deadline=false;
            $AddSearchStrURL = "";
            if (isset($_GET['action']) && $_GET['action'] === 'search') {
                $searchStr = htmlspecialchars($_GET['str']);
            }

            if (isset($_GET['sort']) ) {
                $sortField = (int)$_GET['sort'];
            }
            if (isset($_GET['status']) ) {
                $status = (int)$_GET['status'];
            }
            if (isset($_GET['priority']) ) {
                $priority = (int)$_GET['priority'];
            }
            if (isset($_GET['category']) ) {
                $category = (int)$_GET['category'];
            }

            if (isset($_GET['deadline']) ) {
                $deadline = true;

            }
            $AllCategories = getAllCategories();

            $newpg = 1;
            $curpg = 1;
            $pgcount = 1;

            if (isset($_GET['pg']) && isset($_GET['curpg'])) {
                $newpg = 1;
                if ($_GET['pg']==='prev') {
                    $curpg = (int)$_GET['curpg'];
                    $newpg = $curpg - 2;
                }elseif ($_GET['pg']==='next') {
                    $curpg = (int)$_GET['curpg'];
                    $newpg = $curpg + 2;
                }else{
                    $curpg = 0;
                    $newpg = (int)$_GET['pg'];
                }

                $All = getRequestsCount((int)$_SESSION['userid'],$searchStr,$status,$priority,$category, $deadline);

                $pgcount = (int)($All/10);
                if ($All%10 != 0){  $pgcount++; }

                if ($newpg <=0) {$newpg = 1;}  elseif ($newpg > $pgcount) {$newpg = $pgcount;}

                //die(print_r($pgcount."    ".$All));
                //die(print_r("---------------"));

            }

            $requests = getRequests((int)$_SESSION['userid'],$newpg,$sortField,  $searchStr,$status,$priority,$category, $deadline);//, $newpg

            include './views/requests.php';
            break;
        }
    case 'requestedit':
        if (isset($_SESSION['userid'])){
            $userid = (int)$_SESSION['userid'];
            if (isset($_GET['new']) ) {
                $editid =0;
                $request=array(['id'=>0,'topic'=>'','message'=>'','idcategory'=>0, 'urgency'=>0, 'employeename'=>'', 'account'=>'','pcname'=>'', 'iduser_proc'=>0,'deadline'=>date('Y-m-d', strtotime('+1 day'))]);
                $allUsers = getListOfUsers();
                $AllCategories = getAllCategories();
                $AllPriorities = getAllPriorities();
                $ReturnURL = "?page=allrequests&pg=1&curpg=1";
                include './views/request_edit.php';
                break;
            }
            if (isset($_GET['editid']) ) {
                $editid = (int)$_GET['editid'];
                $request=getRequest($editid);
                $allUsers = getListOfUsers();
                $AllCategories = getAllCategories();
                $AllPriorities = getAllPriorities();
                $ReturnURL = "?page=request&rp=a&id=".$editid;
                include './views/request_edit.php';
                break;
            }


            if (isset($_GET['action'])  && $_GET['action'] === 'pst') {
                $out[] = json_decode(file_get_contents('php://input'));
                $id=(int)$out[0]->{'id'};
                $topic = htmlspecialchars($out[0]->{'topic'});
                $message = htmlspecialchars($out[0]->{'message'});
                $idcategory = (int)($out[0]->{'idcategory'});
                $urgency = (int)($out[0]->{'urgency'});
                $ituser = (int)($out[0]->{'ituser'});
                $deadline = new DateTime($out[0]->{'deadline'});
                $employee_name = htmlspecialchars($out[0]->{'employee_name'});
                $account = htmlspecialchars($out[0]->{'account'});
                $PCName = htmlspecialchars($out[0]->{'PCName'});
                $ddd=$id." ".$topic." ".$message." ".$idcategory." ".$urgency." ".$ituser." ".$employee_name." ".$account." ".$PCName;

                if ($id!==0){
                    $msg = updateRequest($id, $ituser, $topic, $message, $idcategory,  $urgency, $deadline, $employee_name, $account, $PCName);
                    if ($msg === '') {
                        echo json_encode(['success' => true, 'message' => 'Данные сохранены', 'returnID' => (string)$id]);
                    }
                    else{
                        echo json_encode(['success' => false, 'message' => 'Ошибка сохранения данных'.$msg]);
                    }

                }else{

                    $msg = insertRequest($ituser, $topic, $message, $idcategory,  $urgency, $deadline, $employee_name, $account, $PCName);
                    //function insertWikiTopic($_SESSION['userid'], string $tags, string $theme, string $text, int $idcategory,string $description,bool $is_published,int $countviews): string
                    if (is_numeric($msg)===true) {
                        echo json_encode(['success' => true, 'message' => 'Данные сохранены', 'returnID' => $msg]);
                    }
                    else{
                        echo json_encode(['success' => false, 'message' => 'Ошибка сохранения данных'.$msg]);
                    }
                }
                exit();
            }
                if (isset($_GET['api']) && $_GET['action'] === 'delete') {
                    $out[] = json_decode(file_get_contents('php://input'));
                    $id=(int)$out[0]->{'id'};
                    $msg = deleteRequest($id);
                    if ($msg==='') {
                        echo json_encode(['success' => true, 'message' => 'Данные удалены']);
                    }
                    else{
                        echo json_encode(['success' => false, 'message' => 'Ошибка удаления данных'.$msg]);
                    }
                    exit();

                }


        }

    case 'request':
        if (isset($_SESSION['userid'])){
            $userid = (int)$_SESSION['userid'];
            $allUsers = getListOfUsers();
            if (isset($_GET['action']) && $_GET['action'] === 'history') {
                $id=(int)$_GET['id'];
                $arr = getRequestProc($id);
                echo json_encode(['data' => $arr]);
                exit();
            }
            if (isset($_GET['action']) && $_GET['action'] === 'getchatmsg') {
                $lastid=(int)$_GET['lastid'];
                $requestId=(int)$_GET['request'];
                $arr = getRequestChatAllMsg($requestId, $lastid);
                //die(print_r($arr));
                echo json_encode(['success' => true, 'messages' => $arr]);
                exit();
            }

            if (isset($_GET['action']) && $_GET['action'] === 'chatnewmsg') {
                $out[] = json_decode(file_get_contents('php://input'));
                $id=(int)$out[0]->{'id'};
                $msg =htmlspecialchars($out[0]->{'msg'});
//                $id=(int)$_GET['id'];
//                $msg=(int)$_GET['msg'];
                $respMsg = postRequestChatMsg($id,  $userid, $msg);
                if ($respMsg === '') {
                    echo json_encode(['success' => true, 'message' => 'Данные сохранены']);
                }
                else{
                    echo json_encode(['success' => false, 'message' => 'Ошибка сохранения данных '.$respMsg]);
                }
                exit();
            }

            if (isset($_GET['action']) && $_GET['action'] === 'proc') {
                $out[] = json_decode(file_get_contents('php://input'));

                //die(print_r($out));

                $id=(int)$out[0]->{'id'};
                $description =htmlspecialchars($out[0]->{'description'});
                $status =(int)($out[0]->{'status'});
                $request_userid =(int)($out[0]->{'request_userid'});
                if ($request_userid===0) {$request_userid = $userid;}

                if ($id!==0) {
                    $msg = updateRequestProc($id, $description, $status, $request_userid);
                    if ($msg === '') {
                        echo json_encode(['success' => true, 'message' => 'Данные сохранены']);
                    }
                    else{
                        echo json_encode(['success' => false, 'message' => 'Ошибка сохранения данных']);
                    }
                }else{
                    $msg = insertRequestAndInsertProc($description, $status, $request_userid);
                    if ($msg==='') {
                        echo json_encode(['success' => true, 'message' => 'Данные сохранены']);
                    }
                    else{
                        echo json_encode(['success' => false, 'message' => 'Ошибка сохранения данных'.$msg]);
                    }
                }
                exit();
            }

            if (isset($_GET['action'])  && $_GET['action'] === 'getscreenshot') {
                $requestId=(int)$_GET['request'];
                $scrShot = getRequestScreenshot($requestId);
                echo $scrShot[0]['screenshot'];//json_encode(['success' => true, 'messages' => $arr]);
                exit();
            }




            $id = (int)$_GET['id'];
            $request = getRequest($id);//заявка
            $requestProc = getRequestProc($id);//история заявки
            //die(print_r($request[0]['account']));
            $employee = getEmployee($request[0]['account']);//о пользователе - ФИО, должность, отдел
            $PC = getPC($request[0]['pcname']);//имя ПК
            $PC_comp = getPC_composition($request[0]['pcname']);//состав ПК
            //$chatmsg = getRequestChatAllMsg($id);
            $returnURL = "";
            switch ($_GET['rp']){
                case 'm':
                    $returnURL = "/";
                    break;
                case 'a':
                    $returnURL="/?page=allrequests&pg=1&curpg=1";
                    break;
                case 'a':
                    $returnURL="/?page=kanban";
                    break;
                case 'y':
                    //$returnURL="/?page=mysounds&pg=1&curpg=1";
                    break;
                case 'd':
                    //$returnURL="/?page=allmads";
                    break;
                default:
                    $returnURL="javascript:history.back()";
            }

            if (!$request) {
                header("Location: /views/404.php");
                exit();
            }
            include './views/request.php';
        }
        break;
    case 'allpc':

        if (isset($_SESSION['userid'])){
            //die(print_r('dddddddddd'));
            $searchStr = '';
            if (isset($_GET['action']) && $_GET['action'] === 'comboboxsearch') {
                $searchStr = htmlspecialchars($_GET['str']);
                $arPCName = getPCNameCombobox($searchStr);
                //echo json_encode(['success' => true, 'data' => $arEmployee]);
                echo json_encode($arPCName);
                break;
            }
            $searchStr = '';
            if (isset($_GET['action']) && $_GET['action'] === 'search') {
                $searchStr = htmlspecialchars($_GET['str']);
            }
            $sortField=0;
            if (isset($_GET['sort']) ) {
                $sortField = (int)$_GET['sort'];
            }

            $newpg = 1;
            $curpg = 1;
            $pgcount = 1;

            if (isset($_GET['pg']) && isset($_GET['curpg'])) {
                $newpg = 1;
                if ($_GET['pg']==='prev') {
                    $curpg = (int)$_GET['curpg'];
                    $newpg = $curpg - 2;
                }elseif ($_GET['pg']==='next') {
                    $curpg = (int)$_GET['curpg'];
                    $newpg = $curpg + 2;
                }else{
                    $curpg = 0;
                    $newpg = (int)$_GET['pg'];
                }
                $All = getAllPCCount($searchStr);
                $pgcount = (int)$All/10;
                if ($All%10 != 0){  $pgcount++; }

                if ($newpg <=0) {$newpg = 1;}  elseif ($newpg > $pgcount) {$newpg = $pgcount;}
            }

            $AllPCs = getAllPC($newpg, $sortField, $searchStr);//, $newpg

            include './views/pclist.php';
            break;
        }break;

    case 'employees':
        if (isset($_SESSION['userid'])){
            $searchStr = '';
            if (isset($_GET['action']) && $_GET['action'] === 'comboboxsearch') {
                $searchStr = htmlspecialchars($_GET['str']);
                $arEmployee = getEmployeesCombobox($searchStr);
                //echo json_encode(['success' => true, 'data' => $arEmployee]);
                echo json_encode($arEmployee);
                break;
            }
            if (isset($_GET['action']) && $_GET['action'] === 'search') {
                $searchStr = htmlspecialchars($_GET['str']);
            }
            $sortField=0;
            if (isset($_GET['sort']) ) {
                $sortField = (int)$_GET['sort'];
            }
            $newpg = 1;
            $curpg = 1;
            $pgcount = 1;

            if (isset($_GET['pg']) && isset($_GET['curpg'])) {
                $newpg = 1;
                if ($_GET['pg']==='prev') {
                    $curpg = (int)$_GET['curpg'];
                    $newpg = $curpg - 2;
                }elseif ($_GET['pg']==='next') {
                    $curpg = (int)$_GET['curpg'];
                    $newpg = $curpg + 2;
                }else{
                    $curpg = 0;
                    $newpg = (int)$_GET['pg'];
                }
                $All = getAllEmployeesCount($searchStr);
                $pgcount = (int)$All/10;
                if ($All%10 != 0){  $pgcount++; }

                if ($newpg <=0) {$newpg = 1;}  elseif ($newpg > $pgcount) {$newpg = $pgcount;}
            }

            $AllEmployees = getAllEmployees($newpg, $sortField, $searchStr);//, $newpg

            include './views/employees.php';
            break;
        }break;

    case 'subdivisions':
        if (isset($_SESSION['userid'])){
            $SubDiv  = getSubdivision();

            include './views/subdivisions.php';
            break;
        }break;
    case 'categories':
        if (isset($_SESSION['userid'])){
            //api редактировать, вставить
            if (isset($_GET['api']) && $_GET['action'] === 'edit') {
                $out[] = json_decode(file_get_contents('php://input'));

                //die(print_r($out));

                $id=(int)$out[0]->{'id'};
                $name =htmlspecialchars($out[0]->{'name'});
                $itrole =htmlspecialchars($out[0]->{'itrole'});
                if ($id!==0) {
                    if (updateCategory($id, $name, $itrole) === true) {
                        echo json_encode(['success' => true, 'message' => 'Данные сохранены', 'isNew'=>false, 'id'=>$id, 'name'=>$name, 'itrole'=>$itrole]);
                    }
                    else{
                        echo json_encode(['success' => false, 'message' => 'Ошибка сохранения данных']);
                    }
                }else{
                    $msg = insertCategory($name, $itrole);
                    if ($msg==='') {
                        echo json_encode(['success' => true, 'message' => 'Данные сохранены', 'isNew'=>true, 'id'=>$id, 'name'=>$name, 'itrole'=>$itrole]);
                    }
                    else{
                        echo json_encode(['success' => false, 'message' => 'Ошибка сохранения данных'.$msg]);
                    }
                }
                exit();
            }
            //api удалить
            if (isset($_GET['api']) && $_GET['action'] === 'delete') {
                $out[] = json_decode(file_get_contents('php://input'));
                $id=(int)$out[0]->{'id'};
                $msg = deleteCategory($id);
                if ($msg === '') {
                    echo json_encode(['success' => true, 'message' => 'Данные удалены', 'id'=>$id]);
                }
                else{
                    echo json_encode(['success' => false, 'message' => 'Ошибка удаления: '.$msg]);
                }
                exit();
            }

            $searchStr = '';
            if (isset($_GET['action']) && $_GET['action'] === 'search') {
                $searchStr = htmlspecialchars($_GET['str']);
            }
            $sortField=0;
            if (isset($_GET['sort']) ) {
                $sortField = (int)$_GET['sort'];
            }
            //die(print_r($searchStr));
            $allroles = getRoles();
            //die(print_r($allroles));
            $newpg = 1;
            $curpg = 1;
            $pgcount = 1;

            if (isset($_GET['pg']) && isset($_GET['curpg'])) {
                $newpg = 1;
                if ($_GET['pg']==='prev') {
                    $curpg = (int)$_GET['curpg'];
                    $newpg = $curpg - 2;
                }elseif ($_GET['pg']==='next') {
                    $curpg = (int)$_GET['curpg'];
                    $newpg = $curpg + 2;
                }else{
                    $curpg = 0;
                    $newpg = (int)$_GET['pg'];
                }
                $All = getCategoriesCount($searchStr);
                $pgcount = (int)$All/10;
                if ($All%10 != 0){  $pgcount++; }

                if ($newpg <=0) {$newpg = 1;}  elseif ($newpg > $pgcount) {$newpg = $pgcount;}
            }

            $Categories = getCategories($newpg, $sortField, $searchStr);//, $newpg

            include './views/categories.php';
            break;
        }break;
    case 'users':
        if (isset($_SESSION['userid'])){
            //api редактировать, вставить
            if (isset($_GET['api']) && $_GET['action'] === 'edit') {
                $out[] = json_decode(file_get_contents('php://input'));

                //die(print_r($_SERVER['REQUEST_METHOD']));

                $id=(int)$out[0]->{'id'};
                $name =htmlspecialchars($out[0]->{'name'});
                $itrole =htmlspecialchars($out[0]->{'itrole'});
                $changePassword=(bool)$out[0]->{'changePassword'};
                $pass=htmlspecialchars($out[0]->{'pass'});
                if ($id!==0) {
                    if (updateUser($id, $name, $itrole, $changePassword, htmlspecialchars($pass)) === true) {
                        echo json_encode(['success' => true, 'message' => 'Данные сохранены', 'isNew'=>false, 'id'=>$id, 'name'=>$name, 'itrole'=>$itrole]);
                    }
                    else{
                        echo json_encode(['success' => false, 'message' => 'Ошибка сохранения данных']);
                    }
                }else{
                    $msg = insertUser($name, $itrole, $changePassword, htmlspecialchars($pass));
                    if ($msg==='') {
                        echo json_encode(['success' => true, 'message' => 'Данные сохранены', 'isNew'=>true, 'id'=>$id, 'name'=>$name, 'itrole'=>$itrole]);
                    }
                    else{
                        echo json_encode(['success' => false, 'message' => 'Ошибка сохранения данных'.$msg]);
                    }
                }
                exit();
            }
            //api удалить
            if (isset($_GET['api']) && $_GET['action'] === 'delete') {
                $out[] = json_decode(file_get_contents('php://input'));
                $id=(int)$out[0]->{'id'};
                $msg = deleteUser($id);
                if ($msg === '') {
                    echo json_encode(['success' => true, 'message' => 'Данные удалены', 'id'=>$id]);
                }
                else{
                    echo json_encode(['success' => false, 'message' => 'Ошибка удаления: '.$msg]);
                }
                exit();
            }
            $allroles = getRoles();
            $searchStr = '';
            if (isset($_GET['action']) && $_GET['action'] === 'search') {
                $searchStr = htmlspecialchars($_GET['str']);
            }
            $sortField=0;
            if (isset($_GET['sort']) ) {
                $sortField = (int)$_GET['sort'];
            }
            //die(print_r($searchStr));

            $newpg = 1;
            $curpg = 1;
            $pgcount = 1;

            if (isset($_GET['pg']) && isset($_GET['curpg'])) {
                $newpg = 1;
                if ($_GET['pg']==='prev') {
                    $curpg = (int)$_GET['curpg'];
                    $newpg = $curpg - 2;
                }elseif ($_GET['pg']==='next') {
                    $curpg = (int)$_GET['curpg'];
                    $newpg = $curpg + 2;
                }else{
                    $curpg = 0;
                    $newpg = (int)$_GET['pg'];
                }
                $All = getUsersCount($searchStr);
                $pgcount = (int)$All/10;
                if ($All%10 != 0){  $pgcount++; }

                if ($newpg <=0) {$newpg = 1;}  elseif ($newpg > $pgcount) {$newpg = $pgcount;}
            }

            $users = getUsers($newpg, $sortField, $searchStr);//, $newpg

            include './views/users.php';
            break;
        }break;
    case 'wiki':
        if (isset($_SESSION['userid'])){
            $AddSearchStrURL = "";
            $newpg = 1;
            $curpg = 1;
            $pgcount = 1;
            $searchStr = '';
            if (isset($_GET['action']) && $_GET['action'] === 'search') {
                $searchStr = htmlspecialchars($_GET['str']);
                $AddSearchStrURL ="&action=search&str=".$searchStr;
            }
            $sortField=0;
            if (isset($_GET['sort']) ) {
                $sortField = (int)$_GET['sort'];
            }

            if (isset($_GET['pg']) && isset($_GET['curpg'])) {
                $newpg = 1;
                if ($_GET['pg']==='prev') {
                    $curpg = (int)$_GET['curpg'];
                    $newpg = $curpg - 2;
                }elseif ($_GET['pg']==='next') {
                    $curpg = (int)$_GET['curpg'];
                    $newpg = $curpg + 2;
                }else{
                    $curpg = 0;
                    $newpg = (int)$_GET['pg'];
                }
                $All = getWikiTopicsCount($searchStr);
                $pgcount = (int)$All/9;
                if ($All%9 != 0){  $pgcount++; }
                if ($newpg <=0) {$newpg = 1;}  elseif ($newpg > $pgcount) {$newpg = $pgcount;}
            }

            $wikiTopics = getWikiTopics($newpg, $sortField, $searchStr);//, $newpg
            $topicCount= count($wikiTopics);
            $rowCount = (int)($topicCount/3);
            if ($rowCount%3 != 0){  $rowCount++; }
            //die(print_r($rowCount));

            include './views/wiki.php';
            break;
        }break;
    case 'article':

        if (isset($_SESSION['userid'])){
            $AllCategories = getAllCategories();
            if (isset($_GET['id']) ) {
                $id = (int)$_GET['id'];
                $wikiTopic = getWikiTopic($id);//, $newpg
                include './views/article.php';
            }
            //die(print_r($_GET));
            if (isset($_GET['api']) && $_GET['action'] === 'delete') {
                $out[] = json_decode(file_get_contents('php://input'));


                $id=(int)$out[0]->{'id'};

                $msg = deleteWikiTopic($id);
                if ($msg==='') {
                    echo json_encode(['success' => true, 'message' => 'Данные удалены']);
                }
                else{
                    echo json_encode(['success' => false, 'message' => 'Ошибка сохранения данных'.$msg]);
                }


            }

            if (isset($_GET['api']) && $_GET['action'] === 'edit') {
                $out[] = json_decode(file_get_contents('php://input'));

                //die(print_r($_POST['REQUEST_METHOD']));

                $id=(int)$out[0]->{'id'};
                $theme =htmlspecialchars($out[0]->{'title'});
                $tags =htmlspecialchars($out[0]->{'tags'});
                $descripion =htmlspecialchars($out[0]->{'description'});
                $idcategory=(int)$out[0]->{'idcategory'};
                $text=$out[0]->{'text'};
                $userid = (int)$_SESSION['userid'];
                //die(print_r($text));
                if ($id!==0) {
                    //$msg = updateWikiTopic($id, $theme, $tags, $descripion, $idcategory, $text);
                    $msg = updateWikiTopic($id, $userid, $tags, $theme, $text,  $idcategory,$descripion, 1,0);
                    if ($msg === '') {
                        echo json_encode(['success' => true, 'message' => 'Данные сохранены', 'returnID' => (string)$id]);
                    }
                    else{
                        echo json_encode(['success' => false, 'message' => 'Ошибка сохранения данных'.$msg]);
                    }

                }else{

                    $msg = insertWikiTopic($userid, $tags, $theme, $text,  $idcategory,$descripion, 1,0);

                    //function insertWikiTopic($_SESSION['userid'], string $tags, string $theme, string $text, int $idcategory,string $description,bool $is_published,int $countviews): string
                    if (is_numeric($msg)===true) {
                        echo json_encode(['success' => true, 'message' => 'Данные сохранены', 'returnID' => $msg]);
                    }
                    else{
                        echo json_encode(['success' => false, 'message' => 'Ошибка сохранения данных'.$msg]);
                    }

                }
                exit();
            }

            if (isset($_GET['new']) ) {
                    //$editid = (int)$_GET['editid'];
                    $wikiTopic=array(['id'=>0,'theme'=>'','text'=>'','idcategory'=>0, 'description'=>'','tags'=>'']);
                    //die(print_r($wikiTopic));
                    //$wikiTopic = getWikiTopic($editid);//, $newpg
                    $ReturnURL = "'?page=wiki&pg=1&curpg=1";
                    include './views/article_edit.php';
            }
            if (isset($_GET['editid']) ) {
                    $editid = (int)$_GET['editid'];
                    $wikiTopic = getWikiTopic($editid);//, $newpg
                    $ReturnURL = "?page=article&id=".$wikiTopic[0]['id'];
                    include './views/article_edit.php';
            }
        }break;
    case 'chat':
        //die(print_r("ddddd"));
          include './views/chat.php';
    break;
/*
    case 'users':
            //api проверка
            if (isset($_GET['api']) && isset($_GET['action']) && $_GET['action'] === 'check') {
                if (!isAuthorized()) {
                    $message = validateUser(0, htmlspecialchars($_GET['name']), '?', 'user', htmlspecialchars($_GET['email']), '?');
                    if (!empty($message)) {
                        echo $message;
                    }
                    echo "";
                    exit();
                }
                if (isAdmin()) {
                    $message = validateUser(htmlspecialchars($_GET['id']), htmlspecialchars($_GET['name']), '?', htmlspecialchars($_GET['role']), htmlspecialchars($_GET['email']), '?');
                    if (!empty($message)) {
                        echo $message;
                    }
                    echo "";
                    exit();
                }
            }
            //api поставить лайк
            if (isset($_GET['action']) && $_GET['action'] === 'addUser') {
                if (!isAuthorized()) {
                    //Инициализируем переменные для работы
                    $message = "";
                    //Если нажата кнопка отправки формы
                    if($_SESSION['captcha'] != $_POST['captcha']){
                        $message = 'Код с картинки не совпадает!';
                        $user = array('id'=>$_POST['idUser'],
                                    'name'=>$_POST['name'],
                                    'email'=>$_POST['email'],
                                    'tel'=>$_POST['tel']);
                        include './views/useredit.php';
                    }
                    $message = validateUser(0, htmlspecialchars($_POST['name']),  htmlspecialchars($_POST['pass']),
                        'user', htmlspecialchars($_POST['email']), htmlspecialchars($_POST['tel']));
                    if (!empty($message)) {
                        echo $message;
                        exit();
                    }
                    insertUser(htmlspecialchars($_POST['name']), htmlspecialchars($_POST['pass']), 'user', htmlspecialchars($_POST['email']), htmlspecialchars($_POST['tel']));
                    header('Location: /?page=userOk');
                    exit();

                }
                if (!isAdmin()) {
                    $message = "Нет прав на добавление пользователей";
                    header('Location: /?page=users&message=' . strip_tags($message));
                    exit();
                }
                //сюда попадает только админ

                //валидация данных
                $message = validateUser(htmlspecialchars($_POST['idUser']), htmlspecialchars($_POST['name']),  htmlspecialchars($_POST['pass']),
                    htmlspecialchars($_POST['role']), htmlspecialchars($_POST['email']), htmlspecialchars($_POST['tel']));
                if (!empty($message)) {
                    echo $message;
                    exit();
                }
                //санитация данных
                if ($_POST['idUser'] === 0 ) {
                    insertUser(htmlspecialchars($_POST['name']), htmlspecialchars($_POST['pass']), htmlspecialchars($_POST['role']), htmlspecialchars($_POST['email']), htmlspecialchars($_POST['tel']));
                }else {
                    updateUser($_POST['idUser'], htmlspecialchars($_POST['name']), htmlspecialchars($_POST['pass']), htmlspecialchars($_POST['role']), htmlspecialchars($_POST['email']), htmlspecialchars($_POST['tel']));
                }
                $message = "Пользователь изменён";
                header('Location: /?page=users');
                exit();
            }

            if (isset($_GET['api']) && isset($_GET['action']) && $_GET['action'] === 'delUser') {
                //Проверка прав на действие
                if (!isAdmin()) {
                    $message = "Нет прав на удаление";
                    header('Location: /?page=users&message=' . strip_tags($message));
                    exit();
                }
                $id = (int)$_GET['id'];
                $res = deleteUser($id);
                //ответ через api
                if (isset($_GET['api'])) {
                    if ($res=="ok") {
                        //ответ api
                        echo "ok";
                        exit();
                    }else{
                        echo "error";
                        exit();
                    }

                }
                exit();
            }

            if (isset($_GET['api']) && isset($_GET['action']) && ($_GET['action'] === 'UnBlockUser' || $_GET['action'] === 'BlockUser')) {
                //Проверка прав на действие
                if (!isAdmin()) {
                    $message = "Нет прав на разблокировку";
                    header('Location: /?page=users&message=' . strip_tags($message));
                    exit();
                }
                $id = (int)$_GET['id'];
                if ($_GET['action'] === 'UnBlockUser') {
                    $res = setBlockUblockUser($id, 0);
                }else{
                    $res = setBlockUblockUser($id, 1);
                }
                //ответ через api
                if (isset($_GET['api'])) {
                    if ($res==="ok") {
                        //ответ api
                        echo "ok";
                        exit();
                    }else{
                        echo "error";
                        exit();
                    }

                }
                exit();
            }


            $users = getUsers();
            $message = $_GET['message'] ?? '';
            include './views/users.php';
            break;
    case 'userOk':
        include './views/userOk.php';
        break;
    case 'newuser':

        $user = array('id' => 0,
                    'name' => '',
                    'role' => '',
                    'email'=> '',
                    'tel' => ''
                );
        $roles = getRoles();
        $returnURL = '/?page=users';
        $message='';

        include './views/useredit.php';
        break;
    case 'useredit':
        $id = (int)$_GET['id'];
        $user = getUser($id);

        $roles = getRoles();
        if (!$user) {
            header("Location: /views/404.php");
            exit();
        }
        $returnURL = '/?page=users';
        $message='';
        include './views/useredit.php';
        break;

    case 'categories':
        //api поставить лайк
        if (isset($_GET['action']) && $_GET['action'] === 'addcategory') {
            if (!isAdmin()) {
                $message = "Нет прав на добавление категорий";
                header('Location: /?page=sounds&message=' . strip_tags($message));
                exit();
            }
            //валидация данных
            $message = validateCategory($_POST['name']);
            if (!empty($message)) {
                header('Location: /?page=categories&message=' . strip_tags($message));
                exit();
            }
            //санитация данных
            $text = htmlspecialchars($_POST['name']);
            insertCategory($text);
            $message = "Категория добавлена";
            header('Location: /?page=categories&message=' . strip_tags($message)); //или массив сообщений
            exit();
        }

        if (isset($_GET['action']) && $_GET['action'] === 'delete') {
            //Проверка прав на действие
            if (!isAdmin()) {
                $message = "Нет прав на удаление";
                header('Location: /?page=sounds&message=' . strip_tags($message));
                exit();
            }
            $id = (int)$_GET['id'];
            $res = deleteCategory($id);
            //ответ через api
            if (isset($_GET['api'])) {
                if ($res==="ok") {
                    //ответ api
                    echo "ok";
                    exit();
                }else{
                    echo "error";
                    exit();
                }

            }
            exit();
        }


        $categories = getCategories();
        $message = $_GET['message'] ?? '';
        include './views/categories.php';
        break;

    case 'sounds':
        if (isset($_SESSION['userid'])){$userid=(int)$_SESSION['userid'];}
        else{$userid=0;}
        //api поставить лайк
        if ($userid!==0 && isset($_GET['api']) && $_GET['action'] === 'addlike') {
            $out[] = json_decode(file_get_contents('php://input'));
            $id=(int)$out[0]->{'id'};
            $likes = addSoundLike($id, 1, $userid);

            //$id = (int)$_GET['id'];
            //ответ api

            echo $likes;
            exit();
        }
        if ($userid!==0 && isset($_GET['api']) && $_GET['action'] === 'adddislike') {
            //$id = (int)$_GET['id'];
            $out[] = json_decode(file_get_contents('php://input'));
            $id = (int)$out[0]->{'id'};
            $likes = addSoundLike($id, -1, $userid);

            echo $likes;
            exit();
        }

        if ($userid!==0 && isset($_GET['api']) && $_GET['action'] === 'addMadSound') {
            $out[] = json_decode(file_get_contents('php://input'));
            $id = (int)$out[0]->{'id'};
            $mad =  htmlspecialchars($out[0]->{'mad'});
            addMadSound($id, $mad, $userid);
            exit();
        }
        if ($userid!==0 && isset($_GET['api']) && $_GET['action'] === 'addAnswerMad') {
            $out[] = json_decode(file_get_contents('php://input'));
            $id = (int)$out[0]->{'id'};
            $answer =  htmlspecialchars($out[0]->{'ans'});
            echo $out[0]->{'id'};
            echo $out[0]->{'ans'};
            addAnswerMadSound($id, $answer);
            exit();
        }

        //Сохранить звук
        if ($userid!==0 && isset($_GET['action']) && $_GET['action'] === 'addsound') {
            //валидация данных

            $id = htmlspecialchars($_POST['idControl']);
            $title = htmlspecialchars($_POST['titleControl']);
            $userid = (int)$_SESSION['userid'];
            $text = htmlspecialchars($_POST['nameControl']);
            $category = htmlspecialchars($_POST['nameCategory']);
            $soundPath = htmlspecialchars($_POST['soundPath']);
            $message = validateSound($title, $text, $category);

            if (!empty($message)) {
                $error = $message;
                $returnURL = 'href="/?page=sounds&pg=1&curpg=1"';
                $returnCaption = "к списку звуков";
                include './views/showError.php';
                exit();
            }

            if (!empty($_FILES['attachment']) and !empty($_FILES['attachment']['name'])) {

                if (($_FILES['attachment']['size']) > 5 * 1024 * 1024){
                    $error = 'указанный файл превышает максимальный размер загружаемых файлов 5Мб';
                    $returnURL = 'href="/?page=sounds&pg=1&curpg=1"';
                    $returnCaption = "к списку звуков";
                    include './views/showError.php';
                    exit();
                }


                if ($_FILES['attachment']['type']==='audio/mp4' or $_FILES['attachment']['type']==='audio/ogg' or $_FILES['attachment']['type']==='audio/webm' or
                    $_FILES['attachment']['type']==='audio/mpeg' or $_FILES['attachment']['type']==='audio/wav') {
                    // собираем путь до нового файла - папка uploads в текущей директории
                    // в качестве имени оставляем исходное файла имя во время загрузки в браузере
                    //$srcFileName = $file['name'];
                    $dir = '/media/uploads_'.(string)$userid;
                    if(!file_exists(__DIR__ . $dir)){
                        // Попытка создать каталог
                        if(!mkdir(__DIR__ . $dir)){
                            $error = 'Ошибка создания папок на сервере';
                            $returnURL = 'href="/?page=sounds&pg=1&curpg=1"';
                            $returnCaption = "к списку звуков";
                            include './views/showError.php';
                            exit();
                        }
                    }
                    if(file_exists(__DIR__ . $dir)){
                        $ext = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
                        $newFilePath = $dir.'/'. Uniqid().'.'.$ext;
                        if (!move_uploaded_file($_FILES['attachment']['tmp_name'], __DIR__ . $newFilePath)) {
                            $error = 'Ошибка при загрузке файла';
                            $returnURL = 'href="/?page=sounds&pg=1&curpg=1"';
                            $returnCaption = "к списку звуков";
                            include './views/showError.php';
                            exit();
                        } else {
                            //файл загружен => создаем запись
                            $message='Данные сохранены';
                            if ($id===0) {$returnURL = '/?page=mysounds&pg=1&curpg=1';}else{$returnURL = '/?page=moder&pg=1&curpg=1';}

                            $newID = InsertOrUpdateSound($id, $title,  $text, $category, $newFilePath, $userid);

                            $sound = getSound($newID, $userid);
                            $categories = getCategories();
                            $mads=array();
                            if (!$sound) {
                                header("Location: /views/404.php");
                                exit();
                            }
                            include './views/sound.php';
                            exit();
                        }
                    }

                }else{
                    $error = 'Некорректный тип загружаемого файла';
                    $returnURL = 'href="/?page=sounds&pg=1&curpg=1"';
                    $returnCaption = "к списку звуков";
                    include './views/showError.php';
                    exit();
                }
            }else{
                if (!empty($soundPath)) {
                    $newID = InsertOrUpdateSound($id, $title,  $text, $category, $soundPath, $userid);
                    $returnURL='/?page=moder&pg=1&curpg=1';
                    $message='Данные сохранены';

                    $sound = getSound($newID, $userid);
                    $categories = getCategories();
                    //$mads=array();
                    $mads = getSoundMads($id, $userid);
                    if (!$sound) {
                        header("Location: /views/404.php");
                        exit();
                    }
                    include './views/sound.php';
                    exit();
                }else{
                    $error = 'Звуковой файл не указан для загрузки';
                    $returnURL = '/?page=sounds&pg=1&curpg=1';
                    $returnCaption = "к списку звуков";
                    include './views/showError.php';
                    exit();
                }
            }

            exit();
        }
        $searchStr = "";
        if (isset($_GET['action']) && $_GET['action'] === 'search') {
            $searchStr = $_GET['str'];
        }
        $newpg = 1;
        $curpg = 1;
        $pgcount = 1;
        if (isset($_GET['pg']) && isset($_GET['curpg'])) {
            $newpg = 1;
            if ($_GET['pg']==='prev') {
                $curpg = (int)$_GET['curpg'];
                $newpg = $curpg - 2;
            }elseif ($_GET['pg']==='next') {
                $curpg = (int)$_GET['curpg'];
                $newpg = $curpg + 2;
            }else{
                $curpg = 0;
                $newpg = (int)$_GET['pg'];
            }
            $soundCount = getSoundsCount($searchStr);
            $pgcount = (int)$soundCount/5;
            if ($soundCount%5 != 0){  $pgcount++; }

            if ($newpg <=0) {$newpg = 1;}  elseif ($newpg > $pgcount) {$newpg = $pgcount;}
        }

        $sounds = getSounds($searchStr , $userid, $newpg);
        $categories = getCategories();
        $message = $_GET['message'] ?? '';

        include './views/sounds.php';
        break;

    case 'allmads':
        if (isset($_SESSION['userid']) && isAdmin() ) {
            $newpg = 1;
            $curpg = 1;
            $pgcount = 1;
            if (isset($_GET['pg']) && isset($_GET['curpg'])) {
                $newpg = 1;
                if ($_GET['pg']==='prev') {
                    $curpg = (int)$_GET['curpg'];
                    $newpg = $curpg - 2;
                }elseif ($_GET['pg']==='next') {
                    $curpg = (int)$_GET['curpg'];
                    $newpg = $curpg + 2;
                }else{
                    $curpg = 0;
                    $newpg = (int)$_GET['pg'];
                }
                $soundCount = getAllSoundMadsCount();
                $pgcount = (int)$soundCount/5;
                if ($soundCount%5 != 0){  $pgcount++; }

                if ($newpg <=0) {$newpg = 1;}  elseif ($newpg > $pgcount) {$newpg = $pgcount;}
            }



            $mads = getAllSoundMads($newpg);

            include './views/allmads.php';
        }
        break;
    case 'moder':
        //установить флаг одобрено
        if (isset($_SESSION['userid']) && isAdmin() && isset($_GET['api']) && $_GET['action'] === 'setApprove') {
            $out[] = json_decode(file_get_contents('php://input'));
            $id=(int)$out[0]->{'id'};
            $likes = approveDeniedSound($id, 1);
            echo $likes;
            exit();
        }
        //установить флаг отказано
        if (isset($_SESSION['userid']) && isAdmin() &&  isset($_GET['api']) && $_GET['action'] === 'setDenied') {
            $out[] = json_decode(file_get_contents('php://input'));
            $id=(int)$out[0]->{'id'};
            $likes = approveDeniedSound($id, -1);
            echo $likes;
            exit();
        }

        //удалить звук
        if (isset($_SESSION['userid']) && isset($_GET['api']) && $_GET['action'] === 'delSound') {
            $out[] = json_decode(file_get_contents('php://input'));
            $id=(int)$out[0]->{'id'};
            deleteSound($id);
            //echo $id;
            echo "ok";
            exit();
        }
        if (isset($_GET['pg']) && isset($_GET['curpg'])) {
            $newpg = 1;
            if ($_GET['pg']==='prev') {
                $curpg = (int)$_GET['curpg'];
                $newpg = $curpg - 2;
            }elseif ($_GET['pg']==='next') {
                $curpg = (int)$_GET['curpg'];
                $newpg = $curpg + 2;
            }else{
                $curpg = 0;
                $newpg = (int)$_GET['pg'];
            }
            $soundCount = getForApproveSoundsCount();
            $pgcount = (int)$soundCount/5;
            if ($soundCount%5 != 0){  $pgcount++; }

            if ($newpg <=0) {$newpg = 1;}  elseif ($newpg > $pgcount) {$newpg = $pgcount;}
            $sounds = getForApproveSounds($newpg);

            $message = $_GET['message'] ?? '';

            include './views/moder.php';
            exit();
        }



    case 'sound':
        if (isset($_SESSION['userid'])){
            $id = (int)$_GET['id'];
            $userid = (int)$_SESSION['userid'];
            $sound = getSound($id, $userid);
            $categories = getCategories();
            $mads = getSoundMads($id, $userid);
            switch ($_GET['rp']){
                case 'm':
                    $returnURL = "/?page=moder&pg=1&curpg=1";
                    break;
                case 'a':
                    $returnURL="/?page=sounds&pg=1&curpg=1";
                    break;
                case 'y':
                    $returnURL="/?page=mysounds&pg=1&curpg=1";
                    break;
                case 'd':
                    $returnURL="/?page=allmads";
                    break;
                default:
                    $returnURL="javascript:history.back()";
            }

            if (!$sound) {
                header("Location: /views/404.php");
                exit();
            }
            include './views/sound.php';
        }
        break;
    case 'newsound':

        switch ($_GET['rp']){
            case 'm':
                $returnURL = "/?page=moder&pg=1&curpg=1";
                break;
            case 'a':
                $returnURL="/?page=sounds&pg=1&curpg=1";
                break;
            case 'y':
                $returnURL="/?page=mysounds&pg=1&curpg=1";
                break;
            default:
                $returnURL="javascript:history.back()";
        }
        $sound = array('id' => 0,
            'title' => '',
            'text' => '',
            'category'=> 0,
            'likes' => 0,
            'path' => '',
            'userid' => 0,
            'approve' => 0,
            'categoryname' => '',
            'LikeVoices' => 0,
            'DisLikeVoices' => 0


        );
        $mads=array();
        $categories = getCategories();

        if (!$sound) {
            header("Location: /views/404.php");
            exit();
        }
        include './views/soundedit.php';
        break;
    case 'soundedit':
        if (isset($_SESSION['userid'])){
            $id = (int)$_GET['id'];
            $userid = (int)$_SESSION['userid'];
            switch ($_GET['rp']){
                case 'm':
                    $returnURL = "/?page=moder&pg=1&curpg=1";
                    break;
                case 'a':
                    $returnURL="/?page=sounds&pg=1&curpg=1";
                    break;
                case 'y':
                    $returnURL="/?page=mysounds&pg=1&curpg=1";
                    break;
                default:
                    $returnURL="javascript:history.back()";
            }
            $sound = getSound($id, $userid);
            $categories = getCategories();
            $mads = getSoundMads($id, $userid);
            if (!$sound) {
                header("Location: /views/404.php");
                exit();
            }
            include './views/soundedit.php';
        }
        break;
        */
    case 'about':
        $phone = '+7 3423 324 4344';
        include './views/about.php';
        break;
}



