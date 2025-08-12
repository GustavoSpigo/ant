<?php
    $temp = explode("/",$_GET['action']);
    //print_r($temp);
    $idProva = $temp[1];
    if(strlen($temp[2])==13)
    {
        $ra = $temp[2];
        include "provaSQL.corrigir.ra.php";
    }
    else
    {
        $idQuestao = $temp[2];
        include "provaSQL.corrigir.questao.php";
    }

?>