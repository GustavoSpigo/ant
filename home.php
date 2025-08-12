<?php
global $PDO, $SEMESTRE, $DEBUG, $trabalho;

$sql = "SELECT t.nome as nomeTrabalho, t.descricao ,t.prazo, m.nome as nomeMateria, e.files, e.nota, t.idTrabalho, 
              e.dtEntrega, e.idAluno
  FROM `trabalho` t
 INNER JOIN `tipoTrabalho` tt ON t.tipoTrabalho = tt.idTipoTrabalho
 INNER JOIN `materia` m ON t.idMateria = m.idMateria
 INNER JOIN `aluno_materia` am ON am.idMateria = m.idMateria AND am.idAluno = '$_SESSION[ra]' AND am.semestre = '$SEMESTRE'
 LEFT JOIN `entrega` e ON t.idTrabalho = e.idTrabalho and e.idAluno = '$_SESSION[ra]'
WHERE t.semestre  = '$SEMESTRE'
ORDER BY t.prazo DESC";

$result = $PDO->prepare($sql);
$result->execute();
$meteria = "";
?>
<div class="mui-panel panel-content">
    <div class="mui--text-headline" style="text-align: center;">Trabalhos</div>
    <div class="mui-divider" style="margin-left: -20px;margin-right: -20px;"></div>
<?php
$temTrabalhos = false;
while ($row = $result->fetch()) {
    if($row["files"]==""){
        if($row["dtEntrega"]!=""){
            $diretorioDestino = str_replace($_ENV["SCRIPT_FILENAME"], '', dirname($_SERVER['SCRIPT_FILENAME']));
            $diretorioDestino = str_replace("/prof", "", $diretorioDestino);
            $prefixoSqlPath = "/files/" . $SEMESTRE . "/" . $row['idAluno'] . "/" . $row['idTrabalho'];
            $diretorioDestino .= $prefixoSqlPath;
            $arquivos = scandir($diretorioDestino . "/");
            $arquivos = array_diff($arquivos, array('..', '.'));
            if(count($arquivos)>0){
                foreach ($arquivos as $key => $value) {
                    $arquivos[$key] = $prefixoSqlPath."/".$value;
                }
                $futurolink = implode(";", $arquivos);
                $futurolink = str_replace("//", "/", $futurolink);
                $sssql = "UPDATE entrega SET files = '$futurolink' WHERE idTrabalho = $row[idTrabalho] AND idAluno = '$row[idAluno]' ;\n";
                $PDO->exec($sssql);
                $row["files"]=$futurolink;
                //echo "<pre>". $sssql ."</pre>";
            }else{
                //echo "<td>Mais de um arquivo ou nenhum</td>";
            }                
        }
    }
    $temTrabalhos = true;
    ?>
    <?php if($materia!=$row['nomeMateria']){ ?>
        <br>
        <div class="mui--text-caption" style="color:#aaa;">Matéria</div>
        <div class="mui--text-headline"><?php echo $row['nomeMateria'] ?></div>
    <?php } ?>
    <div class="cada_trabalo">
        <div class="mui--text-caption" style="color:#aaa;">Nome do trabalho</div>
        <div class="mui--text-title" title="<?php echo strip_tags($row["descricao"]) ?>">
            <?php echo $row["nomeTrabalho"] ?>
        </div>
    <?php
    
    $prazo  = date_format(date_create($row["prazo"]) , 'Y-m-d');
    $hoje  = date('Y-m-d');
    $corBotao = ($prazo==$hoje)? " mui-btn--primary" : "";
    if($row["files"]==""){ 
        if($hoje <= $prazo){?>
                <div class="mui--text-caption" style="color:#aaa;">Prazo</div>
                <div class="mui--text-title">
                    <?php echo date_format(date_create($row["prazo"]) , 'd/m/Y');?>
                </div>
                <a class="mui-btn mui-btn--raised<?php echo $corBotao; ?>" href="<?php echo meuLink('entregar/'.$row['idTrabalho'] ); ?>">
                    <i class="material-icons" style="position: relative; top: 6px;">check_box_outline_blank</i> 
                    Entregar
                </a>
        <?php
        }
        else
        { ?>
            <i class="material-icons" style="position: relative; top: 6px;">timer_off</i> 
            Prazo esgotado (<?php echo date_format(date_create($row["prazo"]) , 'd/m/Y');?>)
            <br>
            <a class="mui-btn mui-btn--raised mui-btn--danger" href="<?php echo meuLink('entregar/'.$row['idTrabalho'] ); ?>">
                <i class="material-icons" style="position: relative; top: 6px;">timer_off</i> 
                Entregar mesmo atrasado
            </a>
            <br>
            Ao clicar em entregar mesmo atrasado concordo que a validade do trabalho está a critério do professor
            <?php
        }
    }
    else
    { ?>
        <i class="material-icons" style="position: relative; top: 6px;">check_box</i> 
        Entregue
    <?php
    }

    $materia=$row['nomeMateria'];
    ?>
    </div>
        <br>
        <br>
        <pre style="display:none">
            <?php //echo print_r($sql); ?>
        </pre>
    <?php    
}

if(!$temTrabalhos){
    echo "<br><br><br><br>Nenhum trabalho ainda...<i class=\"material-icons\">tag_faces</i>";
}
?>
</div>