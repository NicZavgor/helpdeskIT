<?php


function getOneProject(int $id): ?array
{
    $select = "select title, description, start_date, end_date, urgency_name, category_name,urgency, idcategory, id  from projects_info where id=:id";
    $stmt = getDb()->prepare($select);
    $stmt->execute([":id" => $id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);


}

function getProjects(int $numpg, int $order, string $strSearch): ?array
{
    if ($numpg<=0) {$numpg = 1;}
    $numpg -= 1;
    //die(print_r($numpg));
    if ($order<1 or $order>4)
        {$order=3;} //если сортировка за пределом списка полей, то сортируем по имени
    $strOrder = (string)$order;
    if ($strSearch==="") {
        $select = "select title, description, start_date, end_date, urgency_name, category_name,urgency, idcategory, id  from projects_info order by ".$strOrder." OFFSET 10 * :pg ROWS FETCH NEXT 10 ROWS ONLY";
        $stmt = getDb()->prepare($select);
        $stmt->execute([":pg" => $numpg]);
    }else{

        $select = "select title, description, start_date, end_date, urgency_name, category_name, urgency, idcategory, id  from projects_info where (title like :searchStr1 or description like :searchStr2 or  urgency_name like :searchStr3 or category_name like :searchStr4) order by ".$strOrder." OFFSET 10 * :pg ROWS FETCH NEXT 10 ROWS ONLY";
        $str = htmlspecialchars($strSearch);
        $str = '%'.$str.'%';
        $stmt = getDb()->prepare($select);
        $stmt->execute([":searchStr1"=> $str, ":searchStr2"=> $str, ":searchStr3"=> $str, ":searchStr4"=> $str, ":pg" => $numpg]);
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function  getProjectsCount(string $strSearch): int
{

     $params = [];
     $select = "select COUNT(*) cnt FROM projects_info";

     if (!empty($strSearch)) {
         $searchTerm = htmlspecialchars($strSearch);
         $select .= " WHERE (title like :searchStr1 or description like :searchStr2 or  urgency_name like :searchStr3 or category_name like :searchStr4)"; //
         $stmt = getDb()->prepare($select);
         $stmt->execute([":searchStr1"=> "%" . $searchTerm . "%", ":searchStr2"=> "%" . $searchTerm . "%", ":searchStr3"=> "%" . $searchTerm . "%", ":searchStr4"=> "%" . $searchTerm . "%"]);
     }else{
        $stmt = getDb()->prepare($select);
        $stmt->execute();
     }
     return $stmt->fetch()['cnt'];
}
function getProjectTasksTree(int $idproject){

    try {
        //$sql = "with DepartmentCTE AS (  SELECT  d.id as id , d.title as name, d.idparent as parent_id FROM tasks d  WHERE d.idproject=:idproject1 and d.idparent IS NULL  UNION ALL  SELECT d.id as id, d.title as name, d.idparent as parent_id FROM tasks d  INNER JOIN DepartmentCTE cte ON d.idparent = cte.id where d.idproject=:idproject2 )   SELECT * FROM DepartmentCTE ORDER BY parent_id, name;";
        $sql="with DepartmentCTE AS (
               SELECT  d.id as id , d.title as name, d.idparent as parent_id, d.[urgency],d.[urgency_name],d.[start_date],d.[end_date],d.[status_proc],d.[status_proc_name], d.iduser_proc_name FROM task_info d
               WHERE d.idproject=:idproject1 and d.idparent IS NULL
               UNION ALL
               SELECT d.id as id, d.title as name, d.idparent as parent_id, d.[urgency],d.[urgency_name],d.[start_date],d.[end_date],d.[status_proc],d.[status_proc_name], d.iduser_proc_name FROM task_info d
               INNER JOIN DepartmentCTE cte ON d.idparent = cte.id where d.idproject=:idproject2 )
               SELECT * FROM DepartmentCTE ORDER BY parent_id, name;";
         $stmt = getDb()->prepare($sql);
         $stmt->execute([":idproject1"=> $idproject, ":idproject2"=> $idproject]);

        //$stmt = sqlsrv_query($conn, $sql);

        if ($stmt === false) {
            throw new Exception("Ошибка выполнения запроса: " . print_r(sqlsrv_errors(), true));
        }

        $departments = array();//$stmt->fetchAll(PDO::FETCH_ASSOC);  return $stmt->fetch()['cnt'];
        while ($row = $stmt->fetch()){//sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $departments[] = $row;
        }
        // Возвращаем данные в формате JSON
        echo json_encode($departments);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array("error" => $e->getMessage()));
    }

}
// Получение задач для канбана
function getProjectOneTask(int $taskId) {
    $params = [];
    $sql = "select id,idparent, title, task_description, dt_proc, urgency,urgency_name,status_proc,status_proc_name,deadline, start_date, end_date, idcategory, idcategory_name, description_proc, deadline ,iduser_proc ,iduser_proc_name, itrole_proc, title_parent  FROM task_info";
    $sql = $sql . " where (id=:id )";

    $params["id"] = $taskId;

    $stmt = getDb()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProjectTasksTreeItems(int $exceptTaskId) {
    $params = [];
    $sql = "select id, idproject, title  FROM tasks where id<>:id1 and (idparent<>:id2 or idparent is null) and idproject=(select idproject from tasks where id=:id3)";

    $params["id1"] = $exceptTaskId;
    $params["id2"] = $exceptTaskId;
    $params["id3"] = $exceptTaskId;

    $stmt = getDb()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getProjectTasksTreeByProject(int $idproject) {
    $params = [];
    $sql = "select id, idproject, title  FROM tasks where idproject=:idproject";

    $params["idproject"] = $idproject;

    $stmt = getDb()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // Обновление задачи
function updateProjectTask($taskId, $data) {
    try{
        $allowedFields = ['idproject', 'idparent', 'title', 'description', 'urgency', 'start_date', 'end_date'];
        $updates = [];
        $params = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $updates[] = "$key = ?";
                $params[] = $value;
            }
        }

        if (empty($updates)) return false;

        $params[] = $taskId;
        $sql = "update tasks SET " . implode(', ', $updates) . " WHERE id = ?";
        $stmt = getDb()->prepare($sql);

        return $stmt->execute($params);
    } catch (Exception $e) {
        //http_response_code(500);
        return  $e->getMessage();
    }

}
function insertProjectTask($data) {
    $allowedFields = ['idproject', 'idparent', 'title', 'description', 'urgency', 'start_date', 'end_date'];
    $fields = [];
    $placeholders = [];
    $params = [];

    foreach ($data as $key => $value) {
        if (in_array($key, $allowedFields)) {
            $fields[] = $key;
            $placeholders[] = "?";
            $params[] = $value;
        }
    }

    if (empty($fields)) return false;

    $sql = "INSERT INTO tasks (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
    $stmt = getDb()->prepare($sql);

    if ($stmt->execute($params)) {
        return getDb()->lastInsertId(); // Возвращаем ID новой задачи
    }

    return false;
}

function getTaskProc(int $id): ?array
{
    $select = "select * from task_proc_info where idtask =:id order by dt desc";
    $stmt = getDb()->prepare($select);
    $stmt->execute([":id" => $id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function canDeleteProjectTask(int $taskId){
    $select = "select count(*) cnt from tasks where idparent =:id";
    $stmt = getDb()->prepare($select);
    $stmt->execute([":id" => $taskId]);
    if ( $stmt->fetch()['cnt']==0) {return "";}
    else{return "Необходимо удалить подчинённые ветви";}

}

function DeleteProjectTask(int $taskId){
    try
    {
        $delete = "delete from proc_task  where idtask=:id";
        $stmt = getDb()->prepare($delete);
        $stmt->execute([":id"=> $taskId]);
        $delete = "delete from tasks  where id=:id";
        $stmt = getDb()->prepare($delete);
        $stmt->execute([":id"=> $taskId]);
    } catch (PDOException $e) {
            return 'Ошибка базы данных: ' . $e->getMessage();
    }
    return '';
}
function getProjectTasks($projectId, $parentId = null) {
    $sql = "select t.*,
                   (SELECT COUNT(*) FROM tasks WHERE parent_id = t.id) as child_count
            FROM tasks t
            WHERE t.project_id = ? AND t.parent_id " .
            ($parentId === null ? "IS NULL" : "= ?") .
            " ORDER BY t.sort_order, t.created_at";

    $stmt = getDb()->prepare($sql);
    if ($parentId === null) {
        $stmt->execute([$projectId]);
    } else {
        $stmt->execute([$projectId, $parentId]);
    }

    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Рекурсивно получаем подзадачи
    foreach ($tasks as &$task) {
        if ($task['child_count'] > 0) {
            $task['children'] = getProjectTasks($projectId, $task['id']);
        }
    }

    return $tasks;
}
function deleteProject(int $id): string
{
    try
    {
        $delete = "delete from proc_task where proc_task.id in (select proc_task.id from proc_task left join tasks on (proc_task.idtask=tasks.id) where idproject=:id)";
        $stmt = getDb()->prepare($delete);
        $stmt->execute([":id"=> $id]);
        $delete = "delete from tasks where idproject=:id";
        $stmt = getDb()->prepare($delete);
        $stmt->execute([":id"=> $id]);
        $delete = "delete from projects where id=:id";
        $stmt = getDb()->prepare($delete);
        $stmt->execute([":id"=> $id]);
    } catch (PDOException $e) {
            return 'Ошибка базы данных: ' . $e->getMessage();
    }
    return '';
}
function updateProject(int $id, $data):string
//int $id, string $title, string $description, int $idcategory, int $urgency, DateTime $start_date, DateTime $end_date):string
{
    try{
        $allowedFields = ['title', 'description', 'idcategory', 'urgency', 'start_date', 'end_date'];
        $updates = [];
        $params = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $updates[] = "$key = ?";
                $params[] = $value;
            }
        }
        if (empty($updates)) return 'пустые аргументы';
        $params[] = $id;
        $sql = "update projects SET " . implode(', ', $updates) . " WHERE id = ?";
        $stmt = getDb()->prepare($sql);
        $stmt->execute($params);
        return "";
    } catch (Exception $e) {
        //http_response_code(500);
        return  $e->getMessage();
    }
/*
    try
    {
        $update = "update project set topic=:topic, message=:message, idcategory=:idcategory, urgency=:urgency, deadline=:deadline, employeename=:employee, account=:account, pcname=:pcname  where id=:id";
        $stmt = getDb()->prepare($update);
        // iduser=:iduser, topic=:topic, message=:message, idcategory=:idcategory, urgency=:urgency, deadline=:deadline, employee=:employee, account=:account, PC_id=:PC_id
        $stmt->execute([":topic"=> $topic, ":message"=> $message, ":idcategory"=> $idcategory, ":urgency"=>$urgency, ":deadline"=>$deadline->format('Y.d.m'), ":employee"=>$employee, ":account"=>$account, ":pcname"=>$PCName,":id" => $id]);
        $str = updateRequestProc($id, "", 1, $iduser);
        if ($str==="") {$str = $id; }
        return '';
    } catch (PDOException $e) {
            return 'Ошибка базы данных: ' . $e->getMessage();
    }
    return '';*/
}

function insertProject($data):string
{
    try{
        $allowedFields = ['title', 'description', 'idcategory', 'urgency', 'start_date', 'end_date'];
        $fields = [];
        $placeholders = [];
        $params = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $fields[] = $key;
                $placeholders[] = "?";
                $params[] = $value;
            }
        }

        if (empty($fields)) return false;

        $sql = "insert INTO projects (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = getDb()->prepare($sql);

        if ($stmt->execute($params)) {
            return getDb()->lastInsertId(); // Возвращаем ID новой задачи
        }
    } catch (PDOException $e) {
        return 'Ошибка базы данных: ' . $e->getMessage();
    }

    /*
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
    return '';*/
}
