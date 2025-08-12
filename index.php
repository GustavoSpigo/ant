<?php
global $PDO, $SEMESTRE, $DEBUG;
require("conn.php");
if (!session_id()) @ session_start();
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'sair') {
        session_unset();
        header('Location: ' . meuLink(''));
    }
}
if (isset($_POST['ra']) && !isset($_SESSION['ra'])) {
    $ra = preg_replace('/[^A-Za-z0-9\-()<>= "\/]/', '', $_POST['ra']);
    $result = $PDO->prepare("SELECT * FROM aluno WHERE ra = '$ra'");
    $result->execute();
    $num_rows = $result->rowCount();
    if ($num_rows == 1) {
        $_SESSION['ra'] = $_POST['ra'];
        header('Location: ' . meuLink(''));
    } else {
        die("RA n&atilde;o encontrado <br><a href='" . meuLink('') . "'>Voltar</a>");
    }

}
?><!DOCTYPE html>
<html>
<head>
    <meta name="author" content=" Prof. Gustavo Gomes"/>
    <meta http-equiv="content-language" content="pt-br"/>
    <meta charset="UTF-8"/>
    <title>Ant - Entrega de trabalhos</title>
    <link rel="stylesheet" href="<?php echo meuLink('reset.css'); ?>">
    <link rel="stylesheet" href="<?php echo meuLink('mui.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo meuLink('style.css'); ?>">
    <link rel="icon" href="<?php echo meuLink('icons/ant.png'); ?>"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="<?php echo meuLink('mui.min.js'); ?>"></script>
</head>
<body><?php
include("menu.php");
include("functions.php");

if (!isset($_SESSION['ra'])) {
    include("identificar.php");
} else {
    if (isset($_GET['action'])) {
        if (explode('/', $_GET['action'])[0] == "entregar") {
            $trabalho = explode('/', $_GET['action'])[1];
            include("entregar.php");
        }
        if (explode('/', $_GET['action'])[0] == "entregue") {
            $trabalho = explode('/', $_GET['action'])[1];
            include("entregue.php");
        }
        if (explode('/', $_GET['action'])[0] == "entregues") {
            $trabalho = explode('/', $_GET['action'])[1];
            include("entregues.php");
        }
        if (explode('/', $_GET['action'])[0] == "historico") {
            $trabalho = explode('/', $_GET['action'])[1];
            include("historico.php");
        }
        if (explode('/', $_GET['action'])[0] == "preview") {
            $trabalho = explode('/', $_GET['action'])[1];
            $ra = explode('/', $_GET['action'])[2];
            $result = $PDO->prepare("SELECT tipoTrabalho FROM trabalho WHERE idTrabalho = $trabalho");
            $result->execute();
            $row = $result->fetch();
            global $previewDireto;
            $previewDireto = true;
            include("preview-" . $row['tipoTrabalho'] . ".php");
        }
    } else {
        include("home.php");
    }
}
if ($DEBUG) {
    include('showDebug.php');
} ?>
</body>
</html>