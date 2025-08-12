<?php
$sql = "SELECT M.nome as nomeMateria,
               M.nomeCurto,
               T.nome as nomeTrabalho,
               T.prazo,
               T.idTrabalho,
               (SELECT COUNT(0)
                  FROM entrega E 
                 WHERE E.idTrabalho = T.idTrabalho
                   AND E.files is not null) as entregas,
               (SELECT COUNT(0)
                  FROM aluno_materia AM 
                 WHERE AM.idMateria = M.idMateria
                   AND AM.semestre = '$SEMESTRE') as alunos,
               (SELECT COUNT(0)
                  FROM entrega E 
                 WHERE E.idTrabalho = T.idTrabalho
                   AND E.nota is not null) as correcoes
                 FROM trabalho T
         INNER JOIN materia M ON M.idMateria = T.idMateria
         INNER JOIN professorMateria PM ON PM.idMateria = M.idMateria AND PM.semestre = '$SEMESTRE'
         INNER JOIN professor P ON PM.idProfessor = P.idProfessor AND P.login = '$_SESSION[prof]'
         WHERE T.semestre = '$SEMESTRE'
         ORDER BY T.prazo desc
";
$result = mysql_query($sql);
$meteria = "";
?>
<style>
    .progress-container{
        width:100%; 
        height:26px; 
        border-radius: 5px;
        background-color: #F5F5F5; 
    }
    .progress{
        border-radius: 5px;
        height: 26px;
    }
</style>
<div class="mui-panel panel-content">
    <div class="mui--text-headline" style="text-align: center;">Trabalhos</div>
    <div class="mui-divider" style="margin-left: -20px;margin-right: -20px;"></div>
<?php
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $materiaAmigavel = str_replace(' ','-',strtolower($row['nomeTrabalho'] . " " . $row['idTrabalho']));
?>
    <?php if($materia!=$row['nomeMateria']){ ?>
        <br>
        <!--div class="mui--text-caption" style="color:#aaa;">Matéria</div-->
        <div class="mui--text-headline">
            <a style="color:rgb(148,22,17) !important;" href="<?php echo meuLink('prof/disciplina/' . $row['nomeCurto']) ?>">
            <?php echo $row['nomeMateria'] ?></a>
            (<?php echo $row["alunos"]; ?> alunos)
        </div>
    <?php } ?>
    <div class="cada_trabalo">
        <!--div class="mui--text-caption" style="color:#aaa;">Nome do trabalho</div-->
        <div class="mui--text-title" title="<?php echo $row["descricao"] ?>">
            <?php echo $row["nomeTrabalho"] ?>
            <br>
            <div class="mui--text-subhead">
                <a href="<?php echo meuLink('prof/trabalhos/' . $row['idTrabalho']) ?>" style="color: rgba(0,0,0,.87);">
                    <i class="material-icons">edit</i>
                    Editar
                </a>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <a href="<?php echo meuLink('prof/entregas/' . $materiaAmigavel) ?>" style="color: rgba(0,0,0,.87);">
                    <i class="material-icons">spellcheck</i>
                    Avaliar
                </a>
            </div>
        </div>
    <?php
$prazo  = date_format(date_create($row["prazo"]) , 'Y-m-d');    
?>

    <div style="padding-right: 30px;">
        <div class="progress-container">
            <div class="progress" style="background-color: #90CAF9; width: <?php echo $row["entregas"] / $row["alunos"] * 100 ?>%;">
                <span style="position: absolute; padding-top: 3px; padding-left: 5px;"><?php echo $row["entregas"] ?> trabalhos entregues</span>
            </div>
        </div>
        <div class="progress-container">
            <div class="progress" style="background-color: #80CBC4; width: <?php echo $row["correcoes"] / $row["alunos"] * 100 ?>%;">
                <span style="position: absolute; padding-top: 3px; padding-left: 5px;"><?php echo $row["correcoes"] ?> trabalhos avaliados</span>
            </div>
        </div>
    </div>
</div>
        <br>
        <br>
<?php    $materia=$row['nomeMateria'];
 /*   echo "<pre>";
    print_r($row);
    echo "</pre>";*/
}
?>
</div>
    