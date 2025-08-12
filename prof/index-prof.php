<?php
  require("../conn.php");
  if ( ! session_id() ) @ session_start();
?><!DOCTYPE html>
<html>
<head>
    <meta name="author" content=" Prof. Gustavo Gomes" />
    <meta http-equiv="content-language" content="pt-br" />
    <meta charset="UTF-8"/>
    <title>Ant - Correções de trabalhos</title>
    <link rel="stylesheet" href="<?php echo meuLink('reset.css'); ?>">
    <link rel="stylesheet" href="<?php echo meuLink('mui.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo meuLink('style.css'); ?>">
    <link rel="icon" href="<?php echo meuLink('icons/ant.png'); ?>"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Mono" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="<?php echo meuLink('mui.min.js'); ?>"></script>
</head>
<body><?php
if(!isset($_SESSION['prof'])){
    include("identificar.php");
}else{
    include("menu-prof.php");
    if(isset($_GET['action'])){
        if(explode('/', $_GET['action'])[0]=="entregas"){
            $trabalho = explode('/', $_GET['action'])[1];
            include("entregas.php");
        }
        if(explode('/', $_GET['action'])[0]=="trabalhos"){
            $trabalho = explode('/', $_GET['action'])[1];
            include("trabalhos.php");
        }
        if(explode('/', $_GET['action'])[0]=="disciplina"){
            $disciplina = explode('/', $_GET['action'])[1];
            include("disciplina.php");
        }
        if(explode('/', $_GET['action'])[0]=="explorar"){
            include("explorar.php");
        }
        if(explode('/', $_GET['action'])[0]=="config"){
            include("config.php");
        }
        if(explode('/', $_GET['action'])[0]=="provaSQL"){
            include("provaSQL.php");
        }
        if(explode('/', $_GET['action'])[0]=="avaliar"){
            $trabalho = explode('/', $_GET['action'])[1];
            $ra = explode('/', $_GET['action'])[2];
            $result = mysql_query("SELECT tipoTrabalho FROM trabalho WHERE idTrabalho = $trabalho");
            $row = mysql_fetch_array($result, MYSQL_ASSOC);
            include("avaliar-" . $row['tipoTrabalho'] . ".php");
        }
        if(explode('/', $_GET['action'])[0]=="alunos"){
            include("alunos.php");
        }
    }else{
        include("home.php");
    }
}
if($DEBUG){ 
    include('../showDebug.php');   
} 
?></body>
</html>