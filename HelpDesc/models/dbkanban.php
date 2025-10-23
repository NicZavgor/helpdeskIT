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
    }
    // Обновление задачи
    function updateProjectTaskStatus(int $id, string $description, int $status, int $request_userid):string
    {
        //статус не известен, т.к. меняют что-то другое
        try
        {

            $insert = "insert into proc_task  (idtask, iduser, dt, description, status, itrole) values (:id, :iduser, CURRENT_TIMESTAMP, :description, :status, :itrole)";
            $stmt = getDb()->prepare($insert);
            $stmt->execute([":iduser"=> $request_userid, ":id"=> $id, ":description"=> $description, ":status"=> $status, ":itrole"=> 'spec']);
        } catch (PDOException $e) {
                return 'Ошибка базы данных: ' . $e->getMessage();
        }
        return '';
    }
