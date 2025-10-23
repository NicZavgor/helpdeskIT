<?php

function getDb() {
    static $db = null;
    if (is_null($db)) {

        $serverName = "DESKTOP-0JQ2EKE\SQLEXPRESS";
        $database = "HelpDesk";

        try {
            $db = new PDO(
                "sqlsrv:Server=$serverName;Database=$database",
                null,
                null
            );
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            //die( print_r( phpinfo(), true));
            //echo "Подключение успешно установлено";
        } catch (PDOException $e) {
            die("Ошибка подключения: " . $e->getMessage());
        }
    }
    return $db;

/*
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
        $db = sqlsrv_connect($serverName, $connectionOptions);
        //die(" getDB ".print_r($db));
        if( $db === false ) {

             die( print_r( "Ошибка подключения !!! ".sqlsrv_errors(), true));
        }

    }

    ///die( print_r($db));
    return $db;*/
}