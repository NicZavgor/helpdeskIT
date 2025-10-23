<?php
require_once __DIR__ . '/app/db.php';
$batFile = "C:\OSPanel\domains\HelpDesc\log\LoadEmployee.bat";
$txtFile = "C:\OSPanel\domains\HelpDesc\data.txt";
$domenTxtFile = "C:\OSPanel\domains\HelpDesc\domendata.txt";
//$subdiv="===SUBDIV===";
//$jobs="===JOBS===";
//$employee="===EMPLOYEE===";
$subdiv="===ПОДРАЗДЕЛЕНИЯ===";
$jobs="===ДОЛЖНОСТИ===";
$employee="===СОТРУДНИКИ===";
$maxWaitTime = 300;        // Максимальное время ожидания (5 минут)
$checkInterval = 5;        // Интервал проверки файла (секунды)
/*
// Настройки подключения к MSSQL
$serverName = "localhost"; // Имя сервера
$connectionOptions = array(
    "Database" => "YourDatabase",  // Имя базы данных
    "Uid" => "YourUsername",       // Имя пользователя
    "PWD" => "YourPassword"        // Пароль
);
*/
// Функция для логирования
function logMessage(string $message) {
    $timestamp = date('Y-m-d H:i:s');
    //echo "[$timestamp] $message\n";
    // Также можно записывать в файл лога
    file_put_contents('C:\OSPanel\domains\HelpDesc\log\msg.log', "$message\n", FILE_APPEND); //[$timestamp]
}

function checkJob(string $GUID)
{
    $select = "select count(*) cnt from jobs where guid=:guid";
    $stmt = getDb()->prepare($select);
    $stmt->execute([":guid"=> $GUID]);
    return $stmt->fetch()['cnt'];
}
function insertOrUpdateJob(string $GUID, string $name)
{
    try{
        if (checkJob($GUID)==0){
            $query = "insert into jobs (guid ,name) VALUES (:guid, :name)";
        }else{
            $query = "update jobs set name=:name where guid=:guid";
        }
    logMessage($query);
        $stmt = getDb()->prepare($query);
        $stmt->execute([":guid"=> $GUID, ":name"=>$name]);
        return '';
    } catch (PDOException $e) {
            return 'Ошибка базы данных: ' . $e->getMessage();
    }
}

function checkKodSubDiv(string $kod)
{
    $select = "select count(id) cnt from subdivisions where kod=:kod";
    $stmt = getDb()->prepare($select);
    $stmt->execute([":kod"=> $kod]);
    return $stmt->fetch()['cnt'];
}
function checkSubDiv(string $GUID)
{
    $select = "select count(id) cnt from subdivisions where guid=:guid";
    $stmt = getDb()->prepare($select);
    $stmt->execute([":guid"=> $GUID]);
    return $stmt->fetch()['cnt'];
}
function insertOrUpdateSubDiv(string $GUID, string $name, string $kod, string $kodParent)
{
    try{
        if (checkSubDiv($GUID)==0){
            $query = "insert into subdivisions (guid, guidparent, name, kod) VALUES (:guid, (select top 1 guid from subdivisions where kod=:kodP), :name, :kod)";
        }else{
            $query = "update subdivisions set guidparent=(select top 1 guid from subdivisions where kod=:kodP), name=:name, kod=:kod where guid=:guid";
        }
        logMessage($query);
        $stmt = getDb()->prepare($query);
        $stmt->execute([":guid"=> $GUID, ":kodP"=>$kodParent, ":name"=>$name, ":kod"=>$kod]);
        return '';
    } catch (PDOException $e) {
            return 'Ошибка базы данных: ' . $e->getMessage();
    }
}

function checkEmployee(string $GUID)
{
    $select = "select count(*) cnt from employees where guid=:guid";
    $stmt = getDb()->prepare($select);
    $stmt->execute([":guid"=> $GUID]);
    return $stmt->fetch()['cnt'];
}
function checkEmployeePos(string $GUID)
{
    $select = "select count(*) cnt from employee_position where guidemployee=:guid";
    $stmt = getDb()->prepare($select);
    $stmt->execute([":guid"=> $GUID]);
    return $stmt->fetch()['cnt'];
}
function insertOrUpdateEmployee($domenLines, string $fullname, string $GUID, string $name, string $GUIDJob, string $kodSubDiv)
{
    try{
        if (checkKodSubDiv($kodSubDiv) == 0 or checkJob($GUIDJob) == 0)
        {
            return '';
        }

        $domainUser = getDomenName($domenLines, $fullname);


        logMessage("Загружаем сотрудника ".$name);
        //
        if (checkEmployee($GUID)==0){
            $query = "insert into employees (guid, name) VALUES (:guid, :name, :account)";
        }else{
            $query = "update employees set name=:name, account=:account where guid=:guid";
        }
        $stmt = getDb()->prepare($query);
        $stmt->execute([":guid"=> $GUID, ":name"=>$name, ":account"=>$domainUser]);

        if (checkEmployeePos($GUID)==0){
            $query = "insert into employee_position (guidemployee, guidjob, guidsubdivision, ondate) VALUES (:guidemployee, :guidjob, (select top 1 guid from subdivisions where kod=:kod), CURRENT_TIMESTAMP)";
        }else{
            $query = "update employees set guidjob=:guidjob, guidsubdivision=(select top 1 guid from subdivisions where kod=:kod), ondate=CURRENT_TIMESTAMP where guidemployee=:guidemployee";
        }
        $stmt = getDb()->prepare($query);
        $stmt->execute([":guidemployee"=>$GUID, ":guidjob"=>$GUIDJob, ":kod"=>$kodSubDiv]);
        return '';
    } catch (PDOException $e) {
            return 'Ошибка базы данных: ' . $e->getMessage();
    }
}

function compareCyrillicStrings($str1, $str2) {
    // Приводим к нижнему регистру, обрезаем пробелы, нормализуем
    $str1 = trim($str1);
    $str2 = trim($str2);

    if (function_exists('normalizer_normalize')) {
        $str1 = normalizer_normalize($str1);
        $str2 = normalizer_normalize($str2);
    }

    $str1 = mb_strtolower($str1, 'UTF-8');
    $str2 = mb_strtolower($str2, 'UTF-8');

    return $str1 === $str2;
}
function getDomenName($domenLines, string $username)
{
                //logMessage("Ищем доменное имя сотрудника ");
                $str1 = mb_strtolower( $username, 'UTF-8');
                foreach ($domenLines as $lineNumber => $d_line) {
                    //logMessage("доменные имена ".$line);
                    $d_line = iconv('Windows-1251', 'UTF-8', $d_line);
                    $d_fields = str_getcsv($d_line, "-");
                    if (empty($d_fields[2])) continue;
                    $str2 = str_replace('"', '', mb_strtolower($d_fields[0], 'UTF-8'));
                    //logMessage("стр1 ".$str1);
                    //logMessage("стр2 ".$d_line);
                    //logMessage("стр2 ".$d_fields[0]);
                    //logMessage("стр2 ".mb_strtolower($d_fields[0], 'UTF-8'));
                    //logMessage("стр2 ".$str2);
                    if ($str2==$str1 ){
                        logMessage("возврат ".$d_fields[2]);
                        return $d_fields[2];
                    }

                }
                return "";
}

try {
    logMessage("Запуск скрипта");
/*
    // Проверяем существование bat-файла
    if (!file_exists($batFile)) {
        throw new Exception("Файл $batFile не найден");
    }

    logMessage("Запуск файла: $batFile");

    // Запускаем bat-файл
    $output = [];
    $returnCode = 0;
    $command = escapeshellcmd($batFile);
    exec($command, $output, $returnCode);

    if ($returnCode !== 0) {
        throw new Exception("Ошибка выполнения bat-файла. Код возврата: $returnCode");
    }

    logMessage("Bat-файл выполнен успешно");
*/
    // Ожидаем появления файла 1.txt
    logMessage("Ожидание появления файла: $txtFile");
    $startTime = time();
    $fileExists = false;
    $loadRazdel = 0; //1-должности, 2-подразделения, 3-сотрудники
    $sMsg="";

    while ((time() - $startTime) < $maxWaitTime) {
        if (file_exists($txtFile)) {
            $fileExists = true;
            break;
        }
        sleep($checkInterval);
    }

    if (!$fileExists) {
        throw new Exception("Файл $txtFile не появился в течение $maxWaitTime секунд");
    }

    logMessage("Файл найден: $txtFile");

    $content = file_get_contents($txtFile);
    if ($content === false) {
        throw new Exception("Не удалось прочитать файл: $txtFile");
    }
    logMessage("Подключение к MSSQL...");
    $conn = getDb();

    if ($conn === false) {
        $errors = sqlsrv_errors();
        throw new Exception("Ошибка подключения к MSSQL: " . print_r($errors, true));
    }

    logMessage("Подключение к MSSQL установлено");

    $lines = explode("\n", trim($content));

    logMessage("Открытие информации о домене");
    $domen = file_get_contents($domenTxtFile);
    if ($domen === false) {
        throw new Exception("Не удалось прочитать домен-файл: $domenTxtFile");
    }
    $domenLines = explode("\n", trim($domen));
    logMessage("Реестр УЗ домена открыт");

    foreach ($lines as $lineNumber => $line) {
        //$line = trim($line);
        if (empty($line)) continue;
        $fields = str_getcsv($line, "|");
        if (empty($fields[0])) continue;
        if ($fields[0]=="GUID") continue;


            if (str_contains($fields[0], $subdiv))
            {
                $loadRazdel = 2;
                logMessage("Загружаем подразделения");
                continue;
            }elseif (str_contains($fields[0], $jobs))
            {
                $loadRazdel = 1;
                logMessage("Загружаем должности");
                continue;
            }elseif (str_contains($fields[0], $employee))
            {
                $loadRazdel = 3;
                logMessage("Загружаем сотрудников");
                continue;
            }elseif (str_contains($line, "==="))
            {
                $loadRazdel = 0;
                //logMessage($line);
                continue;
            }
        /*elseif (substr($fields[0],1,2) == "=="){
                $loadRazdel = 0;
                continue;
            }*/
        switch ($loadRazdel ) {
            case 1:
                if (stripos(mb_strtolower("__".$fields[1], 'UTF-8'), "дисп")!=false or
                    stripos(mb_strtolower("__".$fields[1], 'UTF-8'), "локац")!=false or
                    stripos(mb_strtolower("__".$fields[1], 'UTF-8'), "радио")!=false or
                    stripos(mb_strtolower("__".$fields[1], 'UTF-8'), "полет")!=false) {

                }else{

                    logMessage("Загружаем должность ".$fields[1]);
                }

                break;
            case 2:
                if ($fields[5]=="" and stripos("__".$fields[1], "1302")!=false and stripos("__".$fields[1], "1302.8")==false) {
                    logMessage("Загружаем подразделения ".$fields[0]);
                    if ($sMsg!="") {logMessage("Ошибка ".$sMsg);}
                }
                break;
            case 3:
                $sMsg = insertOrUpdateEmployee($domenLines, $fields[2], $fields[0], str_getcsv($fields[2], " ")[0], $fields[4], $fields[3]);//, $fields[2], $fields[4], $fields[3]);
                logMessage("Обработка сотрудника ".str_getcsv($fields[2], " ")[0]);
            break;
        }
    }
    logMessage("Данные успешно загружены в базу данных");
    logMessage("Соединение с базой данных закрыто");

    logMessage("Скрипт завершен успешно");

} catch (Exception $e) {
    logMessage("ОШИБКА: " . $e->getMessage());
    exit(1); // Возвращаем код ошибки для планировщика
}

exit(0); // Успешное завершение
?>