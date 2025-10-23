<?php

function getRequests(int $userid, int $numpg,int $order, string $searchStr, int $status, int $priority, int $category, bool $deadline): bool|array
{
    if ($numpg<=0) {$numpg = 1;}
    $numpg -= 1;
    $searchArray = null;
    $str = "";
    if ($order<1 or $order>9)
        {$order=9;}
    $strOrder = (string)$order;
    $select = "select id,status_proc_name,urgency_name, idcategory_name, topic,employeename, name_users, pcname,dt,deadline,account,urgency,message,finalstatus ,iduser_proc,itrole_proc,itrole_proc_name, idcategory, dt_proc,description_proc,status_proc,role_users,employeeguid_users,blocking_users, guidemployee FROM full_request_info ";
//    $conn = getDb();

    $strItRole = htmlspecialchars(getItRoleById($userid));
    if ($searchStr!=''){
        $str = '%'.htmlspecialchars($searchStr).'%';
    }

    if ($strItRole==="superadmin"){
        $strItRole = "";
    }

    $params = [];
    if ($strItRole!="" or $str!="" or $status!=0 or $priority!=0 or $category!=0 or $deadline===true) {
        $selectWh = "";
        if ($str!="") {
            $selectWh = $selectWh . " (id like :str1 or status_proc_name like :str2 or  urgency_name like :str3 or  topic like :str4 or  employeename like :str5 or  name_users like :str6 or  pcname like :str7 )";
            $params["str1"] = $str;
            $params["str2"] = $str;
            $params["str3"] = $str;
            $params["str4"] = $str;
            $params["str5"] = $str;
            $params["str6"] = $str;
            $params["str7"] = $str;
        }
        if ($strItRole!=""){
            if ($selectWh!="") {$selectWh=$selectWh." and ";}
            $selectWh = $selectWh . " (itrole=:itrole or iduser_proc=:userid)";
            $params["itrole"] = $strItRole;
            $params["userid"] = $userid;
        }
        if ($status!=0){
            if ($selectWh!="") {$selectWh=$selectWh." and ";}
            if ($status!=100){
                $selectWh = $selectWh . " (status_proc=:status)";
                $params["status"] = $status;

            }else {
                $selectWh = $selectWh . " (status_proc<>4 and status_proc<>5)";
            }
        }
        if ($priority!=0){
            if ($selectWh!="") {$selectWh=$selectWh." and ";}
            $selectWh = $selectWh . " (urgency=:urgency)";
            $params["urgency"] = $priority;
        }
        if ($category!=0){
            if ($selectWh!="") {$selectWh=$selectWh." and ";}
            $selectWh = $selectWh . " (idcategory=:idcategory)";
            $params["idcategory"] = $category;
        }
        if ($deadline ===true){
            if ($selectWh!="") {$selectWh=$selectWh." and ";}
            $selectWh = $selectWh . " (deadline <CURRENT_TIMESTAMP and status_proc<>4 and status_proc<>5) ";
            //array_push($queryarray, $category);
        }
        $select = $select . " where ".$selectWh;
        //die(print_r($select));

        //$stmt = sqlsrv_prepare($conn, $select, $queryarray);
    }

    $select = $select . " order by ".$strOrder." desc OFFSET 10 * :pg ROWS FETCH NEXT 10 ROWS ONLY"; //
    //die(print_r($numpg));
    $params["pg"] = $numpg;
    $stmt = getDb()->prepare($select);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //die(print_r($resultArray));
    //die(print_r($deadline));
    //die(print_r($queryarray));
    //die(print_r($select));
    //die(print_r($numpg));

}


function getRequestsCount(int $userid, string $searchStr, int $status, int $priority, int $category, bool $deadline): int
{
    $searchArray = null;
    $str = "";
    $select = "select count(*) as cnt FROM full_request_info  ";
    //$conn = getDb();

    $strItRole = htmlspecialchars(getItRoleById($userid));
    if ($searchStr!=''){
        $str = '%'.htmlspecialchars($searchStr).'%';
    }
    if ($strItRole==="superadmin"){
        $strItRole = "";
    }

    $params = [];
    if ($strItRole!="" or $str!="" or $status!=0 or $priority!=0 or $category!=0 or $deadline===true) {
        $selectWh = "";
        if ($str!="") {
            $selectWh = $selectWh . " (id like :str1 or status_proc_name like :str2 or  urgency_name like :str3 or  topic like :str4 or  employeename like :str5 or  name_users like :str6 or  pcname like :str7 )";
            $params["str1"] = $str;
            $params["str2"] = $str;
            $params["str3"] = $str;
            $params["str4"] = $str;
            $params["str5"] = $str;
            $params["str6"] = $str;
            $params["str7"] = $str;
        }
        if ($strItRole!=""){
            if ($selectWh!="") {$selectWh=$selectWh." and ";}
            $selectWh = $selectWh . " (itrole=:itrole or iduser_proc=:userid)";
            $params["itrole"] = $strItRole;
            $params["userid"] = $userid;
        }
        if ($status!=0){
            if ($selectWh!="") {$selectWh=$selectWh." and ";}
            if ($status!=100){
                $selectWh = $selectWh . " (status_proc=:status)";
                $params["status"] = $status;

            }else {
                $selectWh = $selectWh . " (status_proc<>4 and status_proc<>5)";
            }
        }
        if ($priority!=0){
            if ($selectWh!="") {$selectWh=$selectWh." and ";}
            $selectWh = $selectWh . " (urgency=:urgency)";
            $params["urgency"] = $priority;
        }
        if ($category!=0){
            if ($selectWh!="") {$selectWh=$selectWh." and ";}
            $selectWh = $selectWh . " (idcategory=:idcategory)";
            $params["idcategory"] = $category;
        }
        if ($deadline ===true){
            if ($selectWh!="") {$selectWh=$selectWh." and ";}
            $selectWh = $selectWh . " (deadline <CURRENT_TIMESTAMP and status_proc<>4 and status_proc<>5) ";
            //array_push($queryarray, $category);
        }
        $select = $select . " where ".$selectWh;
        //die(print_r($select));
//        $stmt = sqlsrv_prepare($conn, $select, $queryarray);
    }
    $stmt = getDb()->prepare($select);
    $stmt->execute($params);
    return $stmt->fetch()['cnt'];
}

function getRequest(int $id): ?array
{
    $select = "select id,pcname,idpc, lst_typename, lst_modelname, lst_typemodelname,idpreviosrequest, account,employeename,idcategory, urgency,urgency_name, message,finalstatus, job_name,subdivision_name,dt,topic,deadline,iduser_proc,itrole_proc,itrole_proc_name, dt_proc,description_proc,status_proc,status_proc_name,name_users,role_users,employeeguid_users,blocking_users, guidemployee FROM full_request_info  where id = :id";
    $stmt = getDb()->prepare($select);
    $stmt->execute([":id" => $id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getRequestScreenshot(int $id): ?array
{
    $select = "select id,screenshot FROM full_request_info  where id = :id";
    $stmt = getDb()->prepare($select);
    $stmt->execute([":id" => $id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



function getRequestProc(int $id): ?array
{
    $select = "select * from request_proc_info where idrequest =:id order by dt desc";
    $stmt = getDb()->prepare($select);
    $stmt->execute([":id" => $id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function setRequestStatus(int $id, int $userid, int $status, string $description)
{

        $insert = "insert INTO proc_request (idrequest, iduser, dt, description, status) values (:idrequest, :iduser, CURRENT_TIMESTAMP, :description, :status)";
        $stmt = getDb()->prepare($insert);
        $stmt->execute([":idrequest"=> $id, ":iduser"=>$userid, ":description"=>$description, ":status"=>$status]);

}
function deleteRequest(int $id): string
{
    try
    {
        $delete = "delete from proc_request  where idrequest=:id";
        $stmt = getDb()->prepare($delete);
        $stmt->execute([":id"=> $id]);
        $delete = "delete from request  where id=:id";
        $stmt = getDb()->prepare($delete);
        $stmt->execute([":id"=> $id]);
    } catch (PDOException $e) {
            return 'Ошибка базы данных: ' . $e->getMessage();
    }
    return '';
}

function updateRequestProc(int $id, string $description, int $status, int $request_userid):string
{
    try
    {
        $insert = "insert into proc_request  (idrequest, iduser, dt, description, status, itrole) values (:id, :iduser, CURRENT_TIMESTAMP, :description, :status, :itrole)";
        $stmt = getDb()->prepare($insert);
        $stmt->execute([":iduser"=> $request_userid, ":id"=> $id, ":description"=> $description, ":status"=> $status, ":itrole"=>"spec"]);
    } catch (PDOException $e) {
            return 'Ошибка базы данных: ' . $e->getMessage();
    }
    return '';
}


function getRequestChatAllMsg(int $idrequest, int $fromId): ?array
{
    $select = "select chat.id, guid, users.id as userid, dt, msg, isNew from chat left join users on (users.employeeguid=guid) where idrequest=:idrequest and chat.id>:fromId order by chat.id";
    $stmt = getDb()->prepare($select);
    $stmt->execute([":idrequest"=> $idrequest, ":fromId"=>$fromId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function postRequestChatMsg(int $id, int $userid, string $msg)
{
    try
    {
        $insert = "insert INTO chat (idrequest, guid, dt, msg, isNew) values (:idrequest, (select top 1 employeeguid from users where id=:userid), CURRENT_TIMESTAMP, :message, 1)";
        $stmt = getDb()->prepare($insert);
        $stmt->execute([":idrequest"=> $id, ":userid"=>$userid, ":message"=>$msg]);
    } catch (PDOException $e) {
            return 'Ошибка базы данных: ' . $e->getMessage();
    }
    return '';
}

function updateRequest(int $id, int $iduser, string $topic, string $message, int $idcategory, int $urgency, DateTime $deadline, string $employee, string $account, string $PCName):string
{
    //die(print_f($deadline));
    try
    {
        $update = "update request set topic=:topic, message=:message, idcategory=:idcategory, urgency=:urgency, deadline=:deadline, employeename=:employee, account=:account, pcname=:pcname  where id=:id";
        $stmt = getDb()->prepare($update);
        // iduser=:iduser, topic=:topic, message=:message, idcategory=:idcategory, urgency=:urgency, deadline=:deadline, employee=:employee, account=:account, PC_id=:PC_id
        $stmt->execute([":topic"=> $topic, ":message"=> $message, ":idcategory"=> $idcategory, ":urgency"=>$urgency, ":deadline"=>$deadline->format('Y.d.m'), ":employee"=>$employee, ":account"=>$account, ":pcname"=>$PCName,":id" => $id]);
        $str = updateRequestProc($id, "", 1, $iduser);
        if ($str==="") {$str = $id; }
        return '';
    } catch (PDOException $e) {
            return 'Ошибка базы данных: ' . $e->getMessage();
    }
    return '';
}

function insertRequest(int $iduser, string $topic, string $message, int $idcategory, int $urgency, DateTime $deadline, string $employee, string $account, string $PCName):string
{
    try
    {
        $insert = "insert INTO request (dt, topic, message, idcategory, urgency, employeename, account, deadline, pcname) OUTPUT INSERTED.ID as NewID values ".
            "(CURRENT_TIMESTAMP, :topic, :message, :idcategory, :urgency, :employee, :account, :deadline, :pcname)";
        $stmt = getDb()->prepare($insert);
        $stmt->execute([":topic"=> $topic, ":message"=> $message, ":idcategory"=> $idcategory, ":urgency"=>$urgency,  ":employee"=>$employee, ":account"=>$account, ":deadline"=>$deadline->format('Y.m.d'), ":pcname"=>$PCName]);
        $NewID = (string)$stmt->fetch()['NewID'];//,
        $str = updateRequestProc($NewID, "", 1, $iduser);
        if ($str==="") {$str = $NewID; }
        return $str;
    } catch (PDOException $e) {
            return 'Ошибка базы данных: ' . $e->getMessage();
    }
    return '';
}

function getRequestWithLastChanges(int $iduser){
    $select = "select top 5 xx.dt, xx.idrequest, status,status_name, request.topic from full_request_last_changes_info xx left join request on (idrequest=request.id)
               where idrequest in (select distinct idrequest from proc_request where iduser=:iduser) order by dt desc";
    $stmt = getDb()->prepare($select);
    $stmt->execute([":iduser"=> $iduser]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getRequestWithLastChatMessages(int $iduser){
    $select = "select top 5 xx.dt, xx.idrequest as idrequest, msg, request.topic from full_chat_last_msg_info xx left join request on (idrequest=request.id)
               where idrequest in (select distinct idrequest from proc_request where iduser=:iduser)   order by dt desc";
    $stmt = getDb()->prepare($select);
    $stmt->execute([":iduser"=> $iduser]);//, ":fromdate"=>(new DateTime('-3 days'))->format('Y.m.d')    and xx.dt >=:fromdate
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
