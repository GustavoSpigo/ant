<?php
$sql = "SELECT  t.nome as nomeTrabalho
             , t.descricao 
             , t.prazo
             , m.nome as nomeMateria
             , e.files
             , e.nota
             , t.idTrabalho
             , e.dtEntrega
             ,e.comentarioProfessor 
FROM `trabalho` t
INNER JOIN `tipoTrabalho` tt ON t.tipoTrabalho = tt.idTipoTrabalho
INNER JOIN `materia` m ON t.idMateria = m.idMateria
INNER JOIN `aluno_materia` am ON am.idMateria = m.idMateria AND am.semestre = t.semestre
INNER JOIN aluno a ON am.idAluno = a.ra
LEFT JOIN `entrega` e ON t.idTrabalho = e.idTrabalho AND e.idALuno = a.ra  
WHERE t.semestre  = '$SEMESTRE' 
AND a.ra = '$_SESSION[ra]'
ORDER BY  m.nome, t.prazo DESC";


$result = mysql_query($sql);
$resultado = Array();

$rows = array();
$materias = array();
while(($row = mysql_fetch_array($result))) {
  $rows[] = $row;
  $materias[$row['nomeMateria']]="";
}

?><div class="mui-panel panel-content">
    <div class="mui--text-headline" style="text-align: center;">Resultados</div>
    <div class="mui-divider" style="margin-left: -20px;margin-right: -20px;"></div>
    <br>
    <ul class="mui-tabs__bar">
    <li class="mui--is-active"><a data-mui-toggle="tab" data-mui-controls="pane-default-1">Lista de Trabalhos</a></li>
    <li><a data-mui-toggle="tab" data-mui-controls="pane-default-3">Gráfico de entregas</a></li>
    </ul>
    <div class="mui-tabs__pane mui--is-active" id="pane-default-1">
        <?php include("entregues-lista.php"); ?>
    </div>
    <div class="mui-tabs__pane" id="pane-default-3">
        <?php include("entregues-graficos.php"); ?>
    </div>
</div>