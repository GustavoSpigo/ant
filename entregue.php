<?php  
    function inserirTrabalho($trabalho,$ra, $arquivos, $comentarioAluno){
        $comentarioAluno = mysql_real_escape_string($comentarioAluno);
        $arquivos = mysql_real_escape_string($arquivos);
        $sqlInsert = "INSERT INTO `entrega`(`idTrabalho`, `idAluno`, `files`, `comentarioAluno`, dtEntrega) 
        VALUES ($trabalho,'$ra','$arquivos','$comentarioAluno', NOW())
        ON DUPLICATE KEY UPDATE files='$arquivos', comentarioAluno='$comentarioAluno', dtEntrega=NOW()
        ";
        //mysql_query($sqlInsert);
        mysql_query($sqlInsert);
        return mysql_error() . $sqlInsert;
    }
    $result = mysql_query($sql = "SELECT t.nome as nomeTrabalho, 
                                         t.descricao ,
                                         t.prazo, 
                                         m.nome as nomeMateria, 
                                         t.idTrabalho,
                                         tt.arquivos,
                                         t.maximoAlunos
                                    FROM `trabalho` t
                                  INNER JOIN `tipoTrabalho` tt ON t.tipoTrabalho = tt.idTipoTrabalho
                                  INNER JOIN `materia` m ON t.idMateria = m.idMateria
                                    WHERE idTrabalho = $trabalho");
    $row = mysql_fetch_array($result, MYSQL_ASSOC);

    $alunos = array();
    foreach ($_POST as $key => $value) {
        if(substr( $key, 0, 5 ) === "aluno"){
            $alunos[$value] = new Aluno($value);
        }
    }
    /*   Salvar os arquivos   */
    $diretorioDestino = str_replace($_ENV["SCRIPT_FILENAME"], '', dirname($_SERVER['SCRIPT_FILENAME']));
    $diretorioDestino = $diretorioDestino;
    $prefixoSqlPath = "/files/" . $SEMESTRE . "/" . $_SESSION['ra'] . "/" . $trabalho;
    $diretorioDestino .= $prefixoSqlPath;
    mkpath($diretorioDestino);

    $row['local']=$diretorioDestino;

    $arquivosSQL = "";
    foreach ($_FILES as $key => $value) {
        if (move_uploaded_file($value['tmp_name'],$diretorioDestino . "/" . $value['name'])) {
            sleep(2);
            $arquivosSQL .= $prefixoSqlPath . "/" . $value['name'] . ";";
        }
    }
    if(isset($_POST['link'])){
    	$arquivosSQL = $_POST['link'] . ";";
    }

?>
<div class="mui-panel panel-content">
    <div class="mui--text-headline" style="text-align: center;">Trabalho entregue</div>
    <div class="mui-divider" style="margin-left: -20px;margin-right: -20px;"></div>
    <br>

    <div class="mui--text-caption" style="color:#aaa;">Entregue por</div>
    <div class="mui--text-title">
        <?php echo $_SESSION["nome"];?>
        <pre><?php inserirTrabalho($trabalho, $_SESSION["ra"], $arquivosSQL, $_POST['comentario_aluno'])?></pre>
    </div>
<?php
if(count($alunos) > 1){
?>
    <div class="mui--text-caption" style="color:#aaa;">Demais integrantes</div>
    <div class="mui--text-title">
 <?php
    foreach ($alunos as $key => $value) {
        if($value->ra != $_SESSION['ra']){
            echo $value->nome .  "<br>";?>
            <pre><?php inserirTrabalho($trabalho, $value->ra, $arquivosSQL, $_POST['comentario_aluno'])?></pre><?php
        }
    }
}   
/* CORRIGIR ENTREGA EM BRANCO */
//$diretorioDestino

 ?>
    </div>
</div>