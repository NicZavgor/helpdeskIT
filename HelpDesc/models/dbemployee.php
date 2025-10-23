<?php

function getEmployee(string $account): ?array
{
    $select = "select guidemployee, guidjob,guidsubdivision,account,employee_name,job_name,subdivision_name  from full_employee_info where account = :account";

    $str = htmlspecialchars($account);
    $str = '%'.$str.'%';
    $stmt = getDb()->prepare($select);
    $stmt->execute([":account" => $str]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllEmployees(int $numpg, int $order, string $strSearch): ?array
{

    if ($numpg<=0) {$numpg = 1;}
    $numpg -= 1;
    if ($order<1 or $order>4)
        {$order=1;} //если сортировка за пределом списка полей, то сортируем по имени
    $strOrder = (string)$order;
    if ($strSearch==="") {
        $select = "select employee_name,job_name,subdivision_name, account, idemployee, guidemployee,guidjob,guidsubdivision FROM full_employee_info order by ".$strOrder." OFFSET 10 * :pg ROWS FETCH NEXT 10 ROWS ONLY";
        $stmt = getDb()->prepare($select);
        $stmt->execute([":pg" => $numpg]);
    }else{
        $select = "select employee_name,job_name,subdivision_name, account, idemployee, guidemployee,guidjob,guidsubdivision FROM full_employee_info where  (account like :searchStr1 or employee_name like :searchStr2 or job_name like :searchStr3 or subdivision_name like :searchStr4) order by ".$strOrder." OFFSET 10 * :pg ROWS FETCH NEXT 10 ROWS ONLY";
        $str = htmlspecialchars($strSearch);
        $str = '%'.$str.'%';
        $stmt = getDb()->prepare($select);
        $stmt->execute([":searchStr1"=> $str, ":searchStr2"=> $str, ":searchStr3"=> $str, ":searchStr4"=> $str, ":pg" => $numpg]);
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}

function getEmployeesCombobox(string $strSearch): ?array
{
    if ($strSearch==="") {
        $select = "select top 10 employee_name as name, job_name, account, guidemployee as guid FROM full_employee_info order by name";
        $stmt = getDb()->prepare($select);
        $stmt->execute();
    }else{

        $select = "select top 10 employee_name as name, job_name, account, guidemployee as guid FROM full_employee_info where employee_name like :searchStr order by name ";
        $str = htmlspecialchars($strSearch);
        $str = '%'.$str.'%';
        $stmt = getDb()->prepare($select);
        $stmt->execute([":searchStr"=> $str]);
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}


function  getAllEmployeesCount(string $strSearch): int
{
    $str = "";
    $select = "select count(*) as cnt FROM full_employee_info ";
    if ($strSearch==="") {
        $stmt = getDb()->prepare($select);
        $stmt->execute();
    }else{
        $str = htmlspecialchars($strSearch);
        $str = '%'.$str.'%';
        $select = $select . "select count(*) as cnt FROM full_employee_info where (account like :searchStr1 or employee_name like :searchStr2 or job_name like :searchStr3 or subdivision_name like :searchStr4)";
        $stmt = getDb()->prepare($select);
        $stmt->execute([":searchStr1"=> $str, ":searchStr2"=> $str, ":searchStr3"=> $str, ":searchStr4"=> $str]);
    }
    return $stmt->fetch()['cnt'];
}


function getSubdivision(): ?array
{
    $select = "with DepartmentCTE AS ( SELECT  d.guid, d.name, d.guidparent FROM subdivisions d WHERE d.guidparent IS NULL UNION ALL SELECT d.guid, d.name, d.guidparent FROM subdivisions d INNER JOIN DepartmentCTE cte ON d.guidparent = cte.guid)  SELECT * FROM DepartmentCTE ORDER BY guidparent, name;";
    $stmt = getDb()->prepare($select);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
