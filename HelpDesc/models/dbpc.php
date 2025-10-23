<?php

function getAllPC(int $numpg, int $order, string $strSearch): ?array
{
    if ($numpg<=0) {$numpg = 1;}
    $numpg -= 1;
    //если сортировка за пределом списка полей, то сортируем по имени
    if ($order<1 or $order>8)
        {$order=2;}
    $strOrder = (string)$order;
    if ($strSearch==="") {
        $select = "select idpc, pcname, OS, CPU,RAM, HDD, Video, Net, full_OS, full_RAM, full_HDD, full_Video, full_CPU, full_Net  from PC_composition_info_separately order by ".$strOrder." OFFSET 10 * :pg ROWS FETCH NEXT 10 ROWS ONLY";
        $stmt = getDb()->prepare($select);
        $stmt->execute([":pg"=>$numpg]);

    }else{
        $select = "select idpc, pcname, OS, CPU,RAM, HDD, Video, Net, full_OS, full_RAM, full_HDD, full_Video, full_CPU, full_Net  from PC_composition_info_separately where (pcname like :searchStr1 or  OS like :searchStr2 or  RAM like :searchStr3 or  HDD like :searchStr4 or  Video like :searchStr5 or  CPU like :searchStr6 or  Net  like :searchStr7) order by  ".$strOrder."   OFFSET 10 * :pg ROWS FETCH NEXT 10 ROWS ONLY";
        $str = htmlspecialchars($strSearch);
        $str = '%'.$str.'%';
        $stmt = getDb()->prepare($select);
        $stmt->execute([":searchStr1" => $str, ":searchStr2" => $str, ":searchStr3" => $str, ":searchStr4" => $str, ":searchStr5" => $str, ":searchStr6" => $str, ":searchStr7" => $str, ":pg"=>$numpg]);

    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function  getAllPCCount(string $strSearch): int
{
    $str = "";
    $select = "select count(*) as cnt FROM PC_composition_info_separately ";
    $conn = getDb();
    if ($strSearch==="") {
        $stmt = getDb()->prepare($select);
        $stmt->execute();
    }else{
        $str = htmlspecialchars($strSearch);
        $str = '%'.$str.'%';
        $select = $select . "select count(*) as cnt FROM PC_composition_info_separately where (pcname like :searchStr1 or  OS like :searchStr2 or  RAM like :searchStr3 or  HDD like :searchStr4 or  Video like :searchStr5 or  CPU like :searchStr6 or  Net  like :searchStr7)";
        $stmt = getDb()->prepare($select);
        $stmt->execute([":searchStr1" => $str, ":searchStr2" => $str, ":searchStr3" => $str, ":searchStr4" => $str, ":searchStr5" => $str, ":searchStr6" => $str, ":searchStr7" => $str]);
    }
    return $stmt->fetch()['cnt'];
}

function getPCNameCombobox(string $strSearch): ?array
{
    if ($strSearch==="") {
        $select = "select top 10 pcname as name, id  FROM PC order by name";
        $stmt = getDb()->prepare($select);
        $stmt->execute();
    }else{

        $select = "select top 10 pcname as name, id  FROM PC where pcname like :searchStr order by name ";
        $str = htmlspecialchars($strSearch);
        $str = '%'.$str.'%';
        $stmt = getDb()->prepare($select);
        $stmt->execute([":searchStr"=> $str]);
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}




function getPC(string $pcname): ?array
{
    $select = "select id, pcname, cabinet  from PC where pcname = :pcname";
    $stmt = getDb()->prepare($select);
    $stmt->execute([":pcname" => $pcname]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPC_composition(string $pcname): ?array
{
    $select = "select idpc,pcname,cabinet,typename,modelname,cnt,fulldescription,dt FROM PC_composition_info where pcname=:pcname";
    $stmt = getDb()->prepare($select);
    $stmt->execute([":pcname" => $pcname]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}

