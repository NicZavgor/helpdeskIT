<?php


    // Получение задач для канбана
    function getKanbanOneTask(string $taskId) {
        $params = [];
        $sql = "select typeobject,kanban_status  as status,knid, id,title,dt, description_proc, main_description,deadline ,idcategory_name,urgency,urgency_name,status_proc,status_proc_name,iduser_proc ,itrole_proc  FROM kanban_tasks";
        $sql = $sql . " where (knid=:id )";

        $params["id"] = $taskId;

        $stmt = getDb()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Получение задач для канбана
    function getKanbanTasks(int $userid) {
        $selectWh = "";
        $strItRole = htmlspecialchars(getItRoleById($userid));
        if ($strItRole==="superadmin"){
            $strItRole = "";
        }
        $params = [];
        $sql = "select typeobject,kanban_status  as status,knid, id,title,dt, description_proc, main_description,deadline ,idcategory_name,urgency,urgency_name,status_proc,status_proc_name,iduser_proc ,itrole_proc  FROM kanban_tasks";
        if ($strItRole!=""){
            if ($selectWh!="") {$selectWh=$selectWh." and ";}
            $selectWh = $selectWh . " (itrole_proc=:itrole or iduser_proc=:userid)";
            $params["itrole"] = $strItRole;
            $params["userid"] = $userid;
        }
        if ($selectWh!='') {
            $sql = $sql . " where ".$selectWh;
        }
        $sql = $sql ." order by deadline";
        $stmt = getDb()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        /*
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

        return $tasks;*/
    }
/*
    // Создание новой задачи
    function createTask($data) {
        $sql = "insert INTO tasks (project_id, parent_id, title, description, task_type,
                                  status, priority, start_date, end_date, estimated_hours)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = getDb()->prepare($sql);
        return $stmt->execute([
            $data['project_id'], $data['parent_id'], $data['title'], $data['description'],
            $data['task_type'], $data['status'], $data['priority'], $data['start_date'],
            $data['end_date'], $data['estimated_hours']
        ]);
    }

    // Обновление задачи
    function updateTask($taskId, $data) {
        $allowedFields = ['title', 'description', 'status', 'priority', 'start_date', 'end_date', 'estimated_hours', 'actual_hours'];
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
    }
*/
    // Обновление задачи
    function updateProjectTaskStatus(int $id, string $description, int $status, int $request_userid):string
    {
        //статус не известен, т.к. меняют что-то другое
        try
        {
           /* if ($status==0){
                $params = [];
                $sql = "select status_proc  FROM kanban_tasks where (knid=:id )";
                //$sql = $sql . " where (knid=:id )";
                $params["id"] = 't'.$id;
                $stmt = getDb()->prepare($sql);
                $stmt->execute($params);
                $status = $stmt->fetch()['status_proc'];;
            }*/

            //echo json_encode(["id"=>$id, "desc"=> $description, "status"=>$status,"iduser"=> $request_userid]);

            $insert = "insert into proc_task  (idtask, iduser, dt, description, status, itrole) values (:id, :iduser, CURRENT_TIMESTAMP, :description, :status, :itrole)";
            $stmt = getDb()->prepare($insert);
            $stmt->execute([":iduser"=> $request_userid, ":id"=> $id, ":description"=> $description, ":status"=> $status, ":itrole"=> 'spec']);
        } catch (PDOException $e) {
                return 'Ошибка базы данных: ' . $e->getMessage();
        }
        return '';
    }
