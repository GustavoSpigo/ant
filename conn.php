<?php
//http://rberaldo.com.br/pdo-mysql/
global $PDO, $SEMESTRE, $DEBUG;
$PDO = new PDO('mysql:host=spigo.net;dbname=spigo594_trabalhos;charset=utf8', 'spigo594_trabalh', '{8rUSImJ4J5)');
$PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$PDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


// Set the character set to UTF-8
$PDO->exec("SET NAMES 'utf8'");
$PDO->exec("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");


function meuLink($end){
    return "https://trabalhos.test/" . $end;
    //return "https://spigo.net/trabalhos/" . $end;
}
$DEBUG=false;

$resConf = $PDO->prepare("SELECT * FROM  `configuracoes`");
$resConf->execute();
if ($resConf->rowCount() == 0) {
    die("Erro: Configurações não encontradas.");
}
if ($resConf->rowCount() > 1) {
    die("Erro: Mais de uma configuração encontrada.");
}
// Fetch the configuration
$resConf = $resConf->fetchAll(PDO::FETCH_ASSOC);
$SEMESTRE = $resConf[0]['semestreAtual'];