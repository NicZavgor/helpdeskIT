<?php

function getRequestStatusProportionToStr(int $userid): array
{
    $str = "";$str4="";$strN="";
    $arr = getRequestStatusProportion($userid);

    if(isset($arr)) {
        foreach ($arr as $OneCat) {
            //$dd = new DateTime($OneCat['dt1']);
            //die(print_r($dd->format('d M')));
            $str = $str . "'".(new DateTime($OneCat['dt1']))->format('d')."-".(new DateTime($OneCat['dt2']))->format('d M')."',";
            $str4 = $str4 . $OneCat['stat4'].",";
            $strN = $strN . $OneCat['statnew'].",";

        }
    }
    return array(&$str,&$str4,&$strN);
}
function getRequestStatusProportion(int $userid): bool|array
{
    $select = "select sss.dt1 as dt1,sss.dt2 as dt2, sum(sss.stat4) as stat4, sum(sss.statnew) as statnew from (
               SELECT DATEADD(day, -28, CURRENT_TIMESTAMP) as dt1, DATEADD(day, -22, CURRENT_TIMESTAMP) as dt2, count(*) stat4,null as statnew from full_request_info where status_proc=4 and dt_proc>=DATEADD(day, -28, CURRENT_TIMESTAMP) and dt_proc<=DATEADD(day, -22, CURRENT_TIMESTAMP) $$$
               union all SELECT DATEADD(day, -28, CURRENT_TIMESTAMP) as dt1, DATEADD(day, -22, CURRENT_TIMESTAMP) as dt2, null as stat4, count(*) statnew from full_request_info where dt>=DATEADD(day, -28, CURRENT_TIMESTAMP) and dt<=DATEADD(day, -22, CURRENT_TIMESTAMP) $$$
               union all SELECT DATEADD(day, -21, CURRENT_TIMESTAMP) as dt1, DATEADD(day, -15, CURRENT_TIMESTAMP) as dt2, count(*) stat4,null as statnew from full_request_info where status_proc=4 and dt_proc>=DATEADD(day, -21, CURRENT_TIMESTAMP) and dt_proc<=DATEADD(day, -15, CURRENT_TIMESTAMP) $$$
               union all SELECT DATEADD(day, -21, CURRENT_TIMESTAMP) as dt1, DATEADD(day, -15, CURRENT_TIMESTAMP) as dt2, null as stat4, count(*) statnew from full_request_info where dt>=DATEADD(day, -21, CURRENT_TIMESTAMP) and dt<=DATEADD(day, -15, CURRENT_TIMESTAMP) $$$
               union all SELECT DATEADD(day, -14, CURRENT_TIMESTAMP) as dt1, DATEADD(day, -8, CURRENT_TIMESTAMP) as dt2, count(*) stat4,null as statnew from full_request_info where status_proc=4 and dt_proc>=DATEADD(day, -14, CURRENT_TIMESTAMP) and dt_proc<=DATEADD(day, -8, CURRENT_TIMESTAMP) $$$
               union all SELECT DATEADD(day, -14, CURRENT_TIMESTAMP) as dt1, DATEADD(day, -8, CURRENT_TIMESTAMP) as dt2, null as stat4, count(*) statnew from full_request_info where dt>=DATEADD(day, -14, CURRENT_TIMESTAMP) and dt<=DATEADD(day, -8, CURRENT_TIMESTAMP) $$$
               union all SELECT DATEADD(day, -7, CURRENT_TIMESTAMP) as dt1, DATEADD(day, 0, CURRENT_TIMESTAMP) as d2, count(*) stat4,null as statnew from full_request_info where status_proc=4 and dt_proc>=DATEADD(day, -7, CURRENT_TIMESTAMP) and dt_proc<=DATEADD(day, 0, CURRENT_TIMESTAMP) $$$
               union all SELECT DATEADD(day, -7, CURRENT_TIMESTAMP) as dt1, DATEADD(day, 0, CURRENT_TIMESTAMP) as d2, null as stat4, count(*) statnew from full_request_info where dt>=DATEADD(day, -7, CURRENT_TIMESTAMP) and dt<=DATEADD(day, 0, CURRENT_TIMESTAMP) $$$) as sss
               group by dt1, dt2";
    //$conn = getDb();
    $params = [];
    $selectWh = "";
    $strItRole = htmlspecialchars(getItRoleById($userid));
    if ($strItRole==="superadmin"){
        $strItRole = "";
    }
    if ($strItRole!="") {
        $select = str_replace("$$$", "(itrole=:itrole or iduser_proc=:iduser)", $select);
        $params["itrole"] = $strItRole;
        $params["userid"] = $userid;
        //$stmt = sqlsrv_prepare($conn, $select, array(&$strItRole, &$userid, &$strItRole, &$userid,&$strItRole, &$userid,&$strItRole, &$userid));
    }else{
        $select = str_replace("$$$", "", $select);
    }
    $stmt = getDb()->prepare($select);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getRequestCategoryProportionToStr(int $userid): array
{
    $str = "";$strP="";$strRGBA="";
    $arr = getRequestCategoryProportion($userid);
    //die(print_r($arr));
    if(isset($arr)) {
        foreach ($arr as $OneCat) {
            $str = $str . "'".$OneCat['name']."',";
            $strP = $strP . $OneCat['proportion'].",";
            $strRGBA = $strRGBA . "' rgba( ".random_int(1, 250)." , ".random_int(1, 250).", ".random_int(1, 250).",0.7 )',";
        }
    }
    return array(&$str,&$strP,&$strRGBA);
}
function getCountRequestStatusForDushBoard(int $userid, int $status, bool $inMonth): int
{
    $params = [];
    $select = "select count(*) as cnt from full_request_info ";
    $select = $select . " where status_proc=:status ";
    $params["status"] = $status;

    if ($inMonth===true){
        $select = $select . " and dt>=DATEADD(day, -28, CURRENT_TIMESTAMP) ";
    }

    $strItRole = htmlspecialchars(getItRoleById($userid));
    if ($strItRole==="superadmin"){
        $strItRole = "";
    }
    if ($strItRole!="") {
        $select = $select . " and (itrole=:itrole or iduser_proc=:iduser)";
        $params["itrole"] = $strItRole;
        $params["userid"] = $userid;
    }
    //die(print_r($select));
    $stmt = getDb()->prepare($select);
    $stmt->execute($params);
    return $stmt->fetch()['cnt'];
}

function getCountRequestPriorityForDushBoard(int $userid, int $priority, bool $inMonth): int
{
    $params = [];
    $select = "select count(*) as cnt from full_request_info ";
    $select = $select . " where urgency=:urgency and status_proc<>4 and  status_proc<>5 ";
    $params["urgency"] = $priority;

    if ($inMonth===true){
        $select = $select . " and dt>=DATEADD(day, -28, CURRENT_TIMESTAMP) ";
    }

    $strItRole = htmlspecialchars(getItRoleById($userid));
    if ($strItRole==="superadmin"){
        $strItRole = "";
    }
    if ($strItRole!="") {
        $select = $select . " and (itrole=:itrole or iduser_proc=:iduser)";
        $params["itrole"] = $strItRole;
        $params["userid"] = $userid;
    }
    $stmt = getDb()->prepare($select);
    $stmt->execute($params);
    return $stmt->fetch()['cnt'];
}

function getCountRequestDeadlineForDushBoard(int $userid): int
{
    $params = [];
    $select = "select count(*) as cnt from full_request_info ";
    $select = $select . " where  (deadline<CURRENT_TIMESTAMP  and status_proc<>4 and status_proc<>5)";

    $strItRole = htmlspecialchars(getItRoleById($userid));
    if ($strItRole==="superadmin"){
        $strItRole = "";
    }
    if ($strItRole!="") {
        $select = $select . " and (itrole=:itrole or iduser_proc=:iduser)";
        $params["itrole"] = $strItRole;
        $params["userid"] = $userid;
    }
    $stmt = getDb()->prepare($select);
    $stmt->execute($params);
    return $stmt->fetch()['cnt'];
}

function getDushBoardCountRequest(int $userid): bool|array
{
    $stat_1=getCountRequestStatusForDushBoard($userid, 1, true);
    $stat_5=getCountRequestStatusForDushBoard($userid, 5, true);
    $stat_3=getCountRequestStatusForDushBoard($userid, 2, false);
    $stat_2=getCountRequestStatusForDushBoard($userid, 4, false);

    $priority_2=getCountRequestPriorityForDushBoard($userid, 2, false);
    $priority_3=getCountRequestPriorityForDushBoard($userid, 3, false);
    $priority_4=getCountRequestPriorityForDushBoard($userid, 4, false);
    $deadline = getCountRequestDeadlineForDushBoard($userid);
//            $selectWh = $selectWh . " (deadline<CURRENT_TIMESTAMP  and status_proc<>4 and status_proc<>5)";

    $resultArray = array(&$stat_1, &$stat_5, &$stat_3, &$stat_2, &$priority_2, &$priority_3, &$priority_4, &$deadline);

    return $resultArray;


}
function getRequestCategoryProportion(int $userid): bool|array
{
    $select = "select idcategory, idcategory_name as name, count(*) as proportion from full_request_info ";
    $params = [];
    $selectWh = "";
    $strItRole = htmlspecialchars(getItRoleById($userid));
    if ($strItRole==="superadmin"){
        $strItRole = "";
    }
    if ($strItRole!="") {
        $select = $select . " where (itrole=:itrole or iduser_proc=:iduser) and dt>DATEADD(day, -28, CURRENT_TIMESTAMP)";
        $select = $select . " group by idcategory,  idcategory_name";
        $params["itrole"] = $strItRole;
        $params["userid"] = $userid;
    }else{
        $select = $select . " where dt>=DATEADD(day, -28, CURRENT_TIMESTAMP) group by idcategory,  idcategory_name";
    }
    $stmt = getDb()->prepare($select);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);


}
