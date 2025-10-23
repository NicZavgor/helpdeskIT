<?php

function updateWikiTopic(int $id, int $iduser, string $tags, string $theme, string $text, int $idcategory,string $description,int $is_published,int $countviews): string
{
    try
    {
        $update = "update wiki set iduser=:iduser, tags=:tags, theme=:theme, text=:text, idcategory=:idcategory, description=:description,upd_dt=CURRENT_TIMESTAMP, is_published=:is_published,countviews=:countviews   where id=:id";
        $stmt = getDb()->prepare($update);
        $stmt->execute([":iduser"=> $iduser, ":tags"=> $tags, ":theme"=> $theme, ":text"=> $text, ":idcategory"=> $idcategory, ":description"=>$description, ":is_published"=>$is_published, ":countviews"=>$countviews, ":id" => $id]);
    } catch (PDOException $e) {
            return 'Ошибка базы данных: ' . $e->getMessage();
    }
    return '';
}

function insertWikiTopic(int $iduser, string $tags, string $theme, string $text, int $idcategory,string $description,int $is_published,int $countviews): string
{
    try
    {
        $insert = "insert INTO wiki (iduser, dt, tags, theme, text, idcategory,description,upd_dt, is_published,countviews) OUTPUT INSERTED.ID as NewID values (:iduser, CURRENT_TIMESTAMP, :tags, :theme, :text, :idcategory, :description, CURRENT_TIMESTAMP, :is_published,:countviews)";
        $stmt = getDb()->prepare($insert);
        $stmt->execute([":iduser"=> $iduser, ":tags"=> $tags, ":theme"=> $theme, ":text"=> $text, ":idcategory"=> $idcategory, ":description"=>$description, ":is_published"=>$is_published, ":countviews"=>$countviews]);
        return (string)$stmt->fetch()['NewID'];
    } catch (PDOException $e) {
            return 'Ошибка базы данных: ' . $e->getMessage();
    }
    return '';
}
function deleteWikiTopic(int $id): string
{
    try
    {
        $delete = "delete from wiki  where id=:id";
        $stmt = getDb()->prepare($delete);
        $stmt->execute([":id"=> $id]);
    } catch (PDOException $e) {
            return 'Ошибка базы данных: ' . $e->getMessage();
    }
    return '';
}


function getWikiTopics(int $numpg, int $order, string $strSearch): ?array
{
    if ($numpg<=0) {$numpg = 1;}
    $numpg -= 1;
    //die(print_r($numpg));
    if ($order<1 or $order>4)
        {$order=1;} //если сортировка за пределом списка полей, то сортируем по имени
    $strOrder = (string)$order;
    if ($strSearch==="") {
        $select = "select theme, description, idcategory_name, dt, upd_dt, is_published, countviews, id  from wiki_info order by ".$strOrder." OFFSET 9 * :pg ROWS FETCH NEXT 9 ROWS ONLY";
        $stmt = getDb()->prepare($select);
        //die(print_r($select));
        $stmt->execute([":pg" => $numpg]);
    }else{
        $select = "select theme, description, idcategory_name, dt, upd_dt, is_published, countviews, id  from wiki_info  where (theme like :searchStr1 or description like :searchStr2 or idcategory_name like :searchStr3 or tags like :searchStr4) order by ".$strOrder." OFFSET 9 * :pg ROWS FETCH NEXT 9 ROWS ONLY";
        $str = htmlspecialchars($strSearch);
        $str = '%'.$str.'%';
        $stmt = getDb()->prepare($select);
        $stmt->execute([":searchStr1"=> $str, ":searchStr2"=> $str, ":searchStr3"=> $str, ":searchStr4"=>$str, ":pg" => $numpg]);
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function  getWikiTopicsCount(string $strSearch): int
{

     $params = [];
     $select = "select COUNT(*) cnt FROM wiki_info";

     if (!empty($strSearch)) {
         $searchTerm = htmlspecialchars($strSearch);
         $select .= " WHERE (theme like :searchStr1 or description like :searchStr2 or tags like :searchStr3  or idcategory_name like :searchStr4)"; //
         $stmt = getDb()->prepare($select);
         $stmt->execute([":searchStr1"=> "%" . $searchTerm . "%", ":searchStr2"=> "%" . $searchTerm . "%", ":searchStr3"=> "%" . $searchTerm . "%", ":searchStr4"=> "%" . $searchTerm . "%"]);
     }else{
        $stmt = getDb()->prepare($select);
        $stmt->execute();
     }
     return $stmt->fetch()['cnt'];
}

function getWikiTopic(int $id): ?array
{

    $select = "select iduser, theme, description, text, idcategory, idcategory_name, dt, upd_dt, is_published, countviews, id, tags  from wiki_info where id=:id";
    $stmt = getDb()->prepare($select);
    //die(print_r($select));
    $stmt->execute([":id" => $id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
