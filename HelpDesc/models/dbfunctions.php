<?php

function getMyRequests(int $userid, int $numpg): bool|array
{
    $params = [];
    $select = "select id,pcname,account,employeename,urgency,message,finalstatus ,dt,topic,deadline,iduser_proc,itrole_proc,dt_proc,description_proc,status_proc,name_users,role_users,employee_users,blocking_users FROM ".
        " full_request_info where where iduser_proc = :userid or (itrole_proc=(select top 1 role from users where users.id=:userid) and iduser_proc is null)  order by dt OFFSET 10 * :pg ROWS FETCH NEXT 10 ROWS ONLY";
    $params["userid"] = $userid;
    $params["pg"] = $numpg;

    $stmt = getDb()->prepare($select);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMyRequestCount(int $userid): int
{
    $select = "select count(*) as cnt FROM  full_request_info where where iduser_proc = :userid1 or (itrole_proc=(select top 1 role from users where users.id=:userid2) and iduser_proc is null) ";
    $stmt = getDb()->prepare($select);
    $stmt->execute($params);
    $stmt->execute([":userid1" => $userid, ":userid2" => $userid]);
    return $stmt->fetch()['cnt'];
}


function getAllPriorities(): ?array
{
    $select = "select id, name from urgencies";
    $stmt = getDb()->prepare($select);
    $stmt->execute([]);
    return $stmt->fetchAll();
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function getSounds(string $strSearch, int $userid, int $numpg): bool|array
{
    $str = "";
    $select = "SELECT sounds.*, users.name  as username, categories.name  as categoryname, likes.LikeDisLike as LikeDisLike, lll.LikeDisLike as LikeVoices, -1*mmm.LikeDisLike as DisLikeVoices FROM sounds
        left join categories on (sounds.category=categories.id)
        left join users on (sounds.userid=users.id)
        left join (select soundid, sum(LikeDisLike) as LikeDisLike from likes where LikeDisLike>0 group by soundid) as lll on (sounds.id=lll.soundid)
        left join (select soundid, sum(LikeDisLike) as LikeDisLike from likes where LikeDisLike<0 group by soundid) as mmm on (sounds.id=mmm.soundid)
        left join likes on (sounds.id=likes.soundid and likes.userid=:userid)";
    if ($strSearch=="") {
        $select = $select . " where approve=1 order by categoryname limit 5 OFFSET 5 * :pg";
        $stmt = getDb()->prepare($select);
        $stmt->execute([":userid" => $userid, ":pg" => $numpg-1]);
    }else{
        $str = htmlspecialchars($strSearch);
        $select = $select . " where approve=1 and (text like :str or title like :str or categoryname like :str) order by categoryname limit 5 OFFSET 5 * :pg";
        $stmt = getDb()->prepare($select);
        $stmt->execute([":userid" => $userid, ":str" => "%".$str."%", ":pg" => $numpg-1]);
    }

    return $stmt->fetchAll();
}



function getForApproveSounds(int $numpg): bool|array
{
    $select = "SELECT sounds.*, categories.name as categoryname, users.name as username FROM sounds left join categories on (sounds.category=categories.id) left join users on (sounds.userid=users.id) order by categoryname limit 5 OFFSET 5 * :pg";
    $stmt = getDb()->prepare($select);
    $stmt->execute([":pg" => $numpg-1]);
    return $stmt->fetchAll();
}

function getForApproveSoundsCount(): int
{
    $select = "SELECT count(*) as cnt FROM sounds";
    $stmt = getDb()->prepare($select);
    $stmt->execute();
    return $stmt->fetch()['cnt'];
}

/*
function setAproveSound(int $id): void
{
    //if (!isset($_SESSION['userid'])) {return;}
    $update = "update sounds set approve=1 where id=:id";
    $stmt = getDb()->prepare($update);
    $stmt->execute([
        ":id" => $id
    ]);
}
*/
function InsertOrUpdateSound(int $id, string $title, string $text, int $category, string $path, int $userid): int
{
    $retID = 0;
    if (!empty($id)){
        $retID = $id;
        $update = "UPDATE sounds set title=:title, text=:text, category=:category, path=:path where id=:id";
        $stmt = getDb()->prepare($update);
        $stmt->execute([
            ":title" => $title,
            ":text" => $text,
            ":category" => $category,
            ":path" => $path,
            ":id" => $id
        ]);
    }else{
        $insert = "INSERT INTO sounds (title, text, category, path, userid) VALUES (:title, :text, :category, :path, :userid)";
        $stmt = getDb()->prepare($insert);
        $stmt->execute([
            ":title" => $title,
            ":text" => $text,
            ":category" => $category,
            ":path" => $path,
            ":userid"=>$userid
        ]);
        $select = "SELECT id from sounds where title=:title and text=:text and category=:category and path=:path order by id desc";
        $stmt = getDb()->prepare($select);
        $stmt->execute([
            ":title" => $title,
            ":text" => $text,
            ":category" => $category,
            ":path" => $path]);
        $retID = $stmt->fetch()['id'];
    }
    return $retID;
}

function deleteSound(int $id): void
{
    $delete = "DELETE FROM sounds WHERE id = :id";
    $stmt = getDb()->prepare($delete);
    $stmt->execute([":id"=> $id]);
}

function validateSound(string $title, string $text, int $category): string
{
    $message = '';
    if (empty($title)) {
        $message = "Заполните название";
    }
    if (empty($text)) {
        $message .= "Заполните описание ";
    }
    if (empty($category)) {
        $message = "Заполните категорию";
    }

    return $message;
}

function addSoundLike(int $soundid,  int $likedislike, int $userid):string
{
    $select = "SELECT count() as cnt FROM likes Where soundid = :soundid and userid=:userid";
    $stmt = getDb()->prepare($select);
    $stmt->execute([':soundid' => $soundid, ':userid' => $userid ]);
    $cnt = $stmt->fetch()['cnt'];

    if ($cnt === 0)
        {$query = "INSERT INTO likes (SoundId, UserID, LikeDisLike) VALUES (:soundid, :userid, :likedislike)"; }
    else
        {$query = "update likes set LikeDisLike =:likedislike where  SoundId=:soundid and UserID=:userid"; }

    $stmt = getDb()->prepare($query);
    $stmt->execute([':soundid' => $soundid, ":userid"=>$userid, ":likedislike"=>$likedislike]);

    $select = "SELECT count() as cnt FROM likes Where soundid = :soundid and LikeDisLike = 1";
    $stmt = getDb()->prepare($select);
    $stmt->execute([':soundid' => $soundid]);
    $cntL= $stmt->fetch()['cnt'];
    $select = "SELECT count() as cnt FROM likes Where soundid = :soundid and LikeDisLike = -1";
    $stmt = getDb()->prepare($select);
    $stmt->execute([':soundid' => $soundid]);
    $cntDL= $stmt->fetch()['cnt'];
    return json_encode([$cntL, $cntDL] );
}

function approveDeniedSound(int $soundid,  int $approve_denied):int
{
    $query = "update sounds set approve =:approve where  Id=:soundid";
    $stmt = getDb()->prepare($query);
    $stmt->execute([':soundid' => $soundid, ":approve"=>$approve_denied]);
    return $approve_denied;
}

function addMadSound(int $soundid,  string $mad, int $userid): void
{
    $mad = htmlspecialchars($mad);
    $query = "INSERT INTO mads (SoundId, FromUserID, dt, mad) VALUES (:soundid, :userid, :dt, :mad)";
    $stmt = getDb()->prepare($query);
    $stmt->execute([':soundid' => $soundid, ":userid"=>$userid, ":dt"=> date('d.m.Y H:i:s'), ":mad"=>$mad]);
}

function addAnswerMadSound(int $madId,  string $answer): void
{
    $answer = htmlspecialchars($answer);
    $query = "update mads set answer=:answer where id=:madId";
    $stmt = getDb()->prepare($query);
    $stmt->execute([":answer"=>$answer, ':madId' => $madId]);
}

function getSoundMads(int $soundid, int $userid): bool|array
{
    $select = "SELECT mads.*, users.name  as username FROM mads  left join users on (mads.fromuserid=users.id) where soundid=:soundid";
    if (!isAdmin()) {
        $select = $select ." and fromUserId=:userid";
        $stmt = getDb()->prepare($select);
        $stmt->execute([':soundid' => $soundid, ":userid"=>$userid]);
    } else {
        $stmt = getDb()->prepare($select);
        $stmt->execute([':soundid' => $soundid]);
    }
    return $stmt->fetchAll();
}

function getAllSoundMads(int $numpg): bool|array
{
    $select = "SELECT mads.*, sounds.title as title, users.name as username FROM mads
        left join users on (mads.fromuserid=users.id)
        left join sounds on (mads.soundid=sounds.id)  limit 5 OFFSET 5 * :pg";
    $stmt = getDb()->prepare($select);
    $stmt->execute([":pg" => $numpg-1]);
    return $stmt->fetchAll();
}

function getAllSoundMadsCount(): int
{
    $select = "SELECT count(*) as cnt FROM mads";
    $stmt = getDb()->prepare($select);
    $stmt->execute([]);
    return $stmt->fetch()['cnt'];
}