<?php

function updateCategory(int $id, string $name, string $itrole): bool
{
    /*
    $conn = getDb();
    $update = "update categories set name=? , itrole=?   where id=?";
    $stmt = sqlsrv_prepare($conn, $update, array(&$name, &$itrole, &$id));
    sqlsrv_execute($stmt);
    sqlsrv_free_stmt( $stmt);
    return true;
    */
    $update = "update categories set name=:name , itrole=:itrole   where id=:id";
    $stmt = getDb()->prepare($update);
    $stmt->execute([":name"=> $name, ":itrole"=> $itrole, ":id" => $id]);
    return true;

}

function insertCategory(string $name, string $itrole): string
{
    try
    {

        $insert = "insert INTO categories (name, itrole) values (:name , :itrole)";
        $stmt = getDb()->prepare($insert);
        $stmt->execute([":name"=> $name, ":itrole"=> $itrole]);


    /*
        $conn = getDb();
        $sql = "insert INTO categories (name, itrole) values (?, ?)";
        $stmt = sqlsrv_prepare($conn, $sql, array(&$name, &$itrole));
        sqlsrv_execute($stmt);
        sqlsrv_free_stmt( $stmt);*/
        /*if ($stmt === false) {
            sqlsrv_free_stmt( $stmt);
            return 'Ошибка сохранения данных';
        }
        if (sqlsrv_fetch($stmt)) {
            $insertedId = sqlsrv_get_field($stmt, 0);
            sqlsrv_free_stmt( $stmt);
            return 1;
        } else {
            sqlsrv_free_stmt( $stmt);
            return 0;
        }*/
    } catch (PDOException $e) {
            return 'Ошибка базы данных: ' . $e->getMessage();
    }
    return '';
}
function deleteCategory(int $id): string
{
    try
    {

        $delete = "delete from categories  where id=:id";
        $stmt = getDb()->prepare($delete);
        $stmt->execute([":id"=> $id]);
    /*
    $conn = getDb();
    $update = "delete from categories  where id=:id";
    $stmt = sqlsrv_prepare($conn, $update, array(&$id));
    sqlsrv_execute($stmt);
    sqlsrv_free_stmt( $stmt);
    */
    } catch (PDOException $e) {
            return 'Ошибка базы данных: ' . $e->getMessage();
    }
    return '';
}



function getAllCategories(): ?array
{
    $select = "select name, itrole_name, itrole, id  from categories_info order by name";
    $stmt = getDb()->prepare($select);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getCategories(int $numpg, int $order, string $strSearch): ?array
{
    if ($numpg<=0) {$numpg = 1;}
    $numpg -= 1;
    //die(print_r($numpg));
    if ($order<1 or $order>4)
        {$order=1;} //если сортировка за пределом списка полей, то сортируем по имени
    $strOrder = (string)$order;
    if ($strSearch==="") {
        $select = "select name, itrole_name, itrole, id  from categories_info order by ".$strOrder." OFFSET 10 * :pg ROWS FETCH NEXT 10 ROWS ONLY";
        $stmt = getDb()->prepare($select);
        $stmt->execute([":pg" => $numpg]);
    }else{
        $select = "select name, itrole_name, itrole, id  from categories_info  where (name like :searchStr1 or itrole_name like :searchStr2) order by ".$strOrder." OFFSET 10 * :pg ROWS FETCH NEXT 10 ROWS ONLY";
        $str = htmlspecialchars($strSearch);
        $str = '%'.$str.'%';
        $stmt = getDb()->prepare($select);
        $stmt->execute([":searchStr1"=> $str, ":searchStr2"=> $str, ":pg" => $numpg]);
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function  getCategoriesCount(string $strSearch): int
{

     $params = [];
     $select = "SELECT COUNT(*) cnt FROM categories_info";

     if (!empty($strSearch)) {
         $searchTerm = htmlspecialchars($strSearch);
         $select .= " WHERE (name LIKE :searchStr1 OR itrole_name LIKE :searchStr2)"; //
         $stmt = getDb()->prepare($select);
         $stmt->execute([":searchStr1"=> "%" . $searchTerm . "%", ":searchStr2"=> "%" . $searchTerm . "%"]);
     }else{
        $stmt = getDb()->prepare($select);
        $stmt->execute();
     }
     return $stmt->fetch()['cnt'];
}
