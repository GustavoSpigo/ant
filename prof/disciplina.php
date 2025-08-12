<style>
	.reprovado{
		background-color: #F44336;
	}
	sup.material-icons{
		font-size: 11px;
	}
  .rodar {
    
  }
<?php
	$result = mysql_query("SELECT *
                         FROM  materia M
                        WHERE M.nomeCurto = '$disciplina' ");
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
?>
</style>
<div class="mui-panel panel-content">
	<div class="mui--text-headline" style="text-align: center;">Entregas da disciplina - <?php echo $row['nome'] ?></div>
    <div class="mui-divider" style="margin-left: -20px;margin-right: -20px;"></div>
	
	<ul class="mui-tabs__bar" style="text-align: right;">
	  <li class="mui--is-active"><a data-mui-toggle="tab" data-mui-controls="pane-events-1">Entregas individuais</a></li>
	  <li><a data-mui-toggle="tab" data-mui-controls="pane-events-2">Médias</a></li>
	</ul>
	
    <div class="mui-tabs__pane mui--is-active" id="pane-events-1">
    <table class="mui-table">
    <thead>
        <tr>
        <th style="width: 1%;">Aluno</th>
        <th style="width: 90%;">RA</th>
<?php
$result = mysql_query("SELECT * , T.nome as nomeTrabalho
                         FROM trabalho T
                        INNER JOIN materia M ON T.idMateria = M.idMateria
                        WHERE M.nomeCurto = '$disciplina' 
                          AND T.semestre = '$SEMESTRE' ");
$sqlMaterias = "";
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { 
        echo "<th><div class='rodar'>" . $row['nomeTrabalho'] . "</div></th>";
        $sqlMaterias .= ",COALESCE(( SELECT COALESCE(nota,'') 
                              FROM entrega 
                             WHERE idTrabalho = $row[idTrabalho]
                               AND idAluno = A.ra),'◌') as \"trabalho".$row['nomeTrabalho'] . "\"";
}
?>
        </tr>
    </thead>
    <tbody>
<?php

 $sql = "SELECT * $sqlMaterias
          FROM materia M
         INNER JOIN aluno_materia AM ON M.idMateria = AM.idMateria
         INNER JOIN aluno A ON A.ra = AM.idAluno
         WHERE nomeCurto = '$disciplina'
           AND AM.semestre = '$SEMESTRE'
          ORDER BY A.nome
         ";

$result = mysql_query($sql);
$i=false;
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { ?>
    
    <tr <?php echo ($i) ? 'style="background-color: #f1f1f1;"' : ''; $i=!$i;?>>
        <td style="white-space: nowrap;"><?php echo $row['nome']; ?></td>
        <td><?php echo $row['ra']; ?></td><?php
            foreach ($row as $key => $value) {
                if(substr($key,0,8)=="trabalho"){
                    echo "<td title='" . substr($key,8) . "'>" . $row[$key] . "</td>";
                }
            }
        ?>
    </tr>
<?php
}
?>
</table></div>
<div class="mui-tabs__pane" id="pane-events-2">
<?php
$GrupoPeso = mysql_query("SELECT * FROM pesos p
  INNER JOIN materia m ON p.idMateria = m.idMateria
  WHERE p.semestre = '$SEMESTRE'
  AND m.nomeCurto = '$disciplina'
  AND p.valor > 0");
  
$sql = "SELECT *, a.nome nomeAluno ";
echo '<table class="mui-table"><thead><tr><th style="width: 1%;">Aluno</th><th style="width: 90%;">RA</th>';
while ($cadaGrupoPeso = mysql_fetch_array($GrupoPeso, MYSQL_ASSOC)) {
	echo "<th>$cadaGrupoPeso[grupo]</th>";
	$sql .= "
	 ,(SELECT ROUND(SUM(COALESCE(ee.nota,0)) / COUNT(0)  * pp.valor, 2) nota
         FROM pesos pp
        INNER JOIN materia mm ON pp.idMateria = mm.idMateria
        INNER JOIN trabalho tt ON tt.idPeso = pp.idPeso
        INNER JOIN aluno_materia aamm ON mm.idMateria = aamm.idMateria AND aamm.semestre = '$SEMESTRE'
         LEFT JOIN entrega ee ON ee.idTrabalho = tt.idTrabalho AND aamm.idAluno = ee.idAluno
        WHERE aamm.idAluno = a.ra
          AND pp.valor > 0
          AND pp.grupo = '$cadaGrupoPeso[grupo]'
          AND mm.nomeCurto = '$disciplina'
          AND pp.semestre = '$SEMESTRE') as \"_$cadaGrupoPeso[grupo]\" ";
}
echo "<th><i title='Média' class='material-icons'>assignment_turned_in</i></th></tr>";
$sql .= "  FROM aluno a
 INNER JOIN aluno_materia am ON a.ra = am.idAluno AND am.semestre = '$SEMESTRE'
 INNER JOIN materia m ON am.idMateria = m.idMateria 
 WHERE m.nomeCurto = '$disciplina'
 ORDER BY a.nome";
@mysql_data_seek($GrupoPeso,0);
 
$resultado =  mysql_query($sql);
//echo "<pre>".$sql."</pre>";
$i=false;
while ($row = mysql_fetch_array($resultado, MYSQL_ASSOC)) {
?>
	<tr <?php echo ($i) ? 'style="background-color: #f1f1f1;"' : ''; $i=!$i;?>>
	<td style="white-space: nowrap;"><?php echo $row['nomeAluno'] ?></td>
	<td><?php echo $row['ra'] ?></td>
<?php	
	$media = 0;
	while ($cadaGrupoPeso = mysql_fetch_array($GrupoPeso, MYSQL_ASSOC)) {
		$media += $row["_".$cadaGrupoPeso['grupo']];
		?>
		<td><?php echo $row["_".$cadaGrupoPeso['grupo']] / $cadaGrupoPeso['valor'] ?></td>
		<?php
	} 
	@mysql_data_seek($GrupoPeso,0);
	?>
	<td style="white-space: nowrap;" <?php if($media<6){ echo "class='reprovado'";} ?>><strong><?php echo min($media,10);
	if($media>10){
		echo '<sup class="material-icons">grade</sup>';
	}
	
	?></strong></td>
	</tr>
	<?php
}
?>
	</div>
</div>
<script>
  // get toggle elements
  var toggleEls = document.querySelectorAll('[data-mui-controls^="pane-events-"]');

  function logFn(ev) {
    var s = '[' + ev.type + ']';
    s += ' paneId: ' + ev.paneId;
    s += ' relatedPaneId: ' + ev.relatedPaneId;
    console.log(s);
  }

  // attach event handlers
  for (var i=0; i < toggleEls.length; i++) {
    toggleEls[i].addEventListener('mui.tabs.showstart', logFn);
    toggleEls[i].addEventListener('mui.tabs.showend', logFn);
    toggleEls[i].addEventListener('mui.tabs.hidestart', logFn);
    toggleEls[i].addEventListener('mui.tabs.hideend', logFn);
  }
</script>