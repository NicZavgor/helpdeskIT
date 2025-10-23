<?php

function getRoles(): array
{
    return  array(
        array ('role' => 'admin', 'name' => 'Сисадмин'),
        array ('role' => 'progr', 'name' => 'Программист'),
        array ('role' => 'boss', 'name' => 'Руководитель'),
        array ('role' => 'superadmin', 'name' => 'Администратор системы')
        );
}

function updateUser(int $id, string $name, string $itrole, bool $changepass, string $pass): bool
{
    if ($changepass ===true) {
        $update = "update users set name=:name , itrole=:itrole, pass=:pass   where id=:id";
        $stmt = getDb()->prepare($update);
        $stmt->execute([":name" => $name, ":itrole"=>$itrole, ":pass"=>password_hash($pass, PASSWORD_DEFAULT), ":id"=>$id]);
        return true;
    }else{
        $update = "update users set name=:name , itrole=:itrole where id=:id";
        $stmt = getDb()->prepare($update);
        $stmt->execute([":name" => $name, ":itrole"=>$itrole, ":id"=>$id]);
        return true;
    }
}

function insertUser(string $name, string $itrole, bool $changepass, string $pass): string
{
    try
    {
        if ($changepass ===true) {
            $insert = "insert INTO users (employee, name, itrole, pass) values (:employee, :name, :itrole, :pass)";
            $stmt = getDb()->prepare($insert);
            $stmt->execute([":employee"=>"111", ":name" => $name, ":itrole"=>$itrole, ":pass"=>password_hash($pass, PASSWORD_DEFAULT)]);
        }else{
            $insert = "insert INTO users (employee, name, itrole) values (:employee, :name, :itrole)";
            $stmt = getDb()->prepare($insert);
            $stmt->execute([":employee"=>"111", ":name" => $name, ":itrole"=>$itrole]);
        }
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
function deleteUser(int $id): string
{
    try
    {
        $delete = "delete from users  where id=:id";
        $stmt = getDb()->prepare($delete);
        $stmt->execute([":id" => $id]);
    } catch (PDOException $e) {
            return 'Ошибка базы данных: ' . $e->getMessage();
    }
    return '';
}


function getUser(?int $id): array
{
    if (isset($id)) {
        $select = "select id, name, itrole FROM users WHERE id = :id";
        $stmt = getDb()->prepare($select);
        $stmt->execute([
            ':id' => $id
        ]);
        return $stmt->fetch();
    }
}

function validateUser(int $id, string $name, string $pass, string $role, string $email, string $tel): string
{
    $message = '';

    if (empty(htmlspecialchars($name)) or empty(htmlspecialchars($pass)) or
        empty(htmlspecialchars($role)) or empty(htmlspecialchars($email)) or
        empty(htmlspecialchars($tel))) {
        $message .= "Заполните все поля";
    }
    //проверяем есть ли такой пользователь
    $select = "select count() as cnt FROM users WHERE (name = :name) and id<>:id";
    $stmt = getDb()->prepare($select);
    $stmt->execute([
        ":id" => $id,
        ":name" => $name
    ]);
    $res = $stmt->fetch();
    //можно->удаляем
    if ($res['cnt'] > 0) {
        $message .= "Пользователь с таким именем или email уже есть в системе";
    }


    return $message;
}


function getListOfUsers(): array
{
    $select = "select id,name,itrole, itrole_name FROM users_info where blocking=0 and itrole<>'superadmin'";
    $stmt = getDb()->prepare($select);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUsers(int $numpg, int $order, string $strSearch): ?array
{
    if ($numpg<=0) {$numpg = 1;}
    $numpg -= 1;
    //die(print_r($numpg));
    if ($order<1 or $order>4)
        {$order=1;} //если сортировка за пределом списка полей, то сортируем по имени
    $strOrder = (string)$order;
    $conn = getDb();
    if ($strSearch==="") {
        $select = "select name, itrole_name, itrole, id, blocking  from users_info order by ".$strOrder." OFFSET 10 * :pg ROWS FETCH NEXT 10 ROWS ONLY";
        $stmt = getDb()->prepare($select);
        $stmt->execute([":pg" => $numpg]);
    }else{
        $select = "select name, itrole_name, itrole, id, blocking  from users_info  where (name like :searchStr or itrole_name like :searchStr) order by ".$strOrder." OFFSET 10 * :pg ROWS FETCH NEXT 10 ROWS ONLY";
        $str = htmlspecialchars($strSearch);
        $str = '%'.$str.'%';
        $stmt = getDb()->prepare($select);
        $stmt->execute([":searchStr"=> $str, ":pg" => $numpg]);
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function  getUsersCount(string $strSearch): int
{
    $str = "";
    $select = "select count(*) as cnt FROM categories_info ";
    $conn = getDb();
    if ($strSearch==="") {
        $stmt = getDb()->prepare($select);
        $stmt->execute();
    }else{
        $str = htmlspecialchars($strSearch);
        $str = '%'.$str.'%';
        $select = $select . "select count(*) as cnt FROM categories_info where (name like :searchStr or itrole_name like :searchStr)";
        $stmt = getDb()->prepare($select);
        $stmt->execute([":searchStr"=> $str]);
    }

    return $stmt->fetch()['cnt'];
}

function getItRoleById(int $userid)
{
    /*
    $str = "";
    $select = "select itrole FROM users where id=? ";
    $conn = getDb();
    $stmt = sqlsrv_prepare($conn, $select, array(&$userid));
    sqlsrv_execute($stmt);
    $userDb = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt( $stmt); //закрываем запрос, закрывать соединение НЕЛЬЗЯ !!!
    return $userDb['itrole'];
    */
    $select = "select itrole FROM users where id=:id ";
    $stmt = getDb()->prepare($select);
    $stmt->execute([":id" => $userid]);
    return $stmt->fetch()['itrole'];
}
