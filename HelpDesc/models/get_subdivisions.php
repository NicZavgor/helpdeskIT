<?php

    header('Content-Type: application/json');
    try {
        // Устанавливаем соединение


        $serverName = "DESKTOP-0JQ2EKE\SQLEXPRESS"; // Например: "localhost\SQLEXPRESS"
        $connectionOptions = array(
            "Database" => "HelpDesk",
            "Uid" => "", // Оставьте пустым для Windows Authentication
            "PWD" => "",  // Оставьте пустым для Windows Authentication
            "TrustServerCertificate" => true, // Рекомендуется для Windows Authentication
            "CharacterSet" => "UTF-8"  // Опционально, для поддержки UTF-8
        );
        //die( print_r( phpinfo(), true));
        //die( print_r( $serverName, true));
        $conn = sqlsrv_connect($serverName, $connectionOptions);
        ///die( print_r("12111q", true));
        //die(" getDB ".print_r($db));
        if( $conn === false ) {

             die( print_r( "Ошибка подключения !!! ".sqlsrv_errors(), true));
        }



//        $conn = getDb();
        if ($conn === false) {
            throw new Exception("Не удалось подключиться к SQL Server: " . print_r(sqlsrv_errors(), true));
        }

        // SQL-запрос для получения дерева подразделений
        $sql = "with DepartmentCTE AS ( SELECT  d.guid as id , d.name, d.guidparent as parent_id FROM subdivisions d WHERE d.guidparent IS NULL UNION ALL SELECT d.guid as id, d.name, d.guidparent as parent_id FROM subdivisions d INNER JOIN DepartmentCTE cte ON d.guidparent = cte.id)  SELECT * FROM DepartmentCTE ORDER BY parent_id, name;";

        $stmt = sqlsrv_query($conn, $sql);

        if ($stmt === false) {
            throw new Exception("Ошибка выполнения запроса: " . print_r(sqlsrv_errors(), true));
        }

        $departments = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $departments[] = $row;
        }
        //die(print_r($departments));
        // Закрываем соединение
        //sqlsrv_free_stmt($stmt);
        //sqlsrv_close($conn);

        // Возвращаем данные в формате JSON
        echo json_encode($departments);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array("error" => $e->getMessage()));
    }
?>
