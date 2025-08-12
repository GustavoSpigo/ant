<?php include("preview.php"); ?>
<style>
    .icon-container{
        display: inline-block;
        border: solid 1px #eaeaea;
        padding: 7px;
        text-align: center;
    }
</style>
<div class="mui-panel panel-content">
    <div class="mui--text-headline" style="text-align: center;">Histórico</div>
    <div class="mui-divider" style="margin-left: -20px;margin-right: -20px;"></div>
    
<?php
global $PDO;
    $result = $PDO->prepare(
  " SELECT * 
    FROM aluno_materia am 
   INNER JOIN materia m ON am.idMateria = m.idMateria
   WHERE idAluno = '$_SESSION[ra]'
   ORDER BY am.semestre DESC, m.nome");
$result->execute();
$semestreAnterior = "";
$idTrabalho = 0;
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    
    if($semestreAnterior!=$row["semestre"]){ 
        ?>
        <div class="mui--text-display1"><?php echo formataSemestre($row["semestre"]); ?></div>
        <?php
    }
    $semestreAnterior = $row["semestre"];

    ?><div style="padding-left: 20px;">
        <div class="mui--text-headline">
        <?php
        echo $row['nome'] . "<br></div>";
        $result2 = $PDO->prepare("SELECT * FROM entrega e INNER JOIN trabalho t ON t.idTrabalho = e.idTrabalho
                                WHERE t.idMateria = $row[idMateria] AND idAluno = '$row[idAluno]'");
        $result2->execute();
        while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) {
            $idTrabalho = $row2['idTrabalho'];
            ?><div style="padding-left: 20px;"><?php
                echo $row2['nome'];
                echo "<br>";
                echo retornaIcones($row2['files'], $row2['tipoTrabalho']);
                //foreach(explode(";", ) as $cadaTxt){
                //    if($cadaTxt){
                //        echo retornaIcone($cadaTxt);
                //    }
                //}
            ?></div><?php
        }
    ?></div><?php
}
    

?> 
</div>