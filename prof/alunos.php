<div class="mui-panel panel-content">
    <div class="mui--text-headline" style="text-align: center;">Gerenciar alunos</div>
    <div class="mui-divider" style="margin-left: -20px;margin-right: -20px;"></div>
    <br>
    
<?php 
if(isset($_POST['SQL'])){ 
	foreach(explode("\n", $_POST['SQL']) as $cadaLinha){
		mysql_query($cadaLinha); 		
		echo "Comando executado (" . mysql_affected_rows() . ")";
		echo "<br>";	
	}
	
	echo mysql_error();
	echo "<br><br>";	
	echo $_POST['SQL'];
	exit();
}


if(!isset($_POST['materia'])){ ?>
    <form method="post">
        <div class="mui-select">
            <select name="materia">
                <?php 
                $result = mysql_query("SELECT * FROM materia");
                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { 
                    echo "<option value='" . $row['idMateria'] . "'>" . $row['nome'] . "</option>";
                } ?>
            </select>
            <label>Selecione a disciplina</label>
        </div>
        <div class="mui-textfield mui-textfield--float-label">
            <textarea name="ras"></textarea>
            <label>RAs e Nomes do SIGA (000000000\tNNNNNN\n)</label>
        </div>
        <button type="submit" class="mui-btn mui-btn--raised">Enviar</button>
    </form>
    <?php
}else{
    $sql  = "/* Disciplina: ". $_POST['materia'] . "*/ \n";
    $sql .= "/* Semestre: ". $SEMESTRE . "*/ \n";
    foreach(explode("\n", $_POST['ras']) as $cadaLinha){
    	$cadaAluno = array();
    	$ra = explode("\t", $cadaLinha)[0];
    	$nome = trim(explode("\t", $cadaLinha)[1]);
    	//print_r($cadaAluno);
    	$sql .= "INSERT INTO aluno (ra,nome) VALUES ('$ra', '$nome') ON DUPLICATE KEY UPDATE nome = '$nome'; \n";
    	$sql .= "INSERT INTO aluno_materia(idAluno, idMateria, semestre) VALUES ('$ra','$_POST[materia]','$SEMESTRE') ON DUPLICATE KEY UPDATE semestre = '$SEMESTRE'; \n";
    } ?>
    <form method="post">
    <div class="mui-textfield mui-textfield--float-label">
        <textarea name="SQL" style="height: 400px;"><?php echo $sql ?></textarea>
        <label>Comando</label>
    </div>
        <button type="submit" class="mui-btn mui-btn--raised">Confirmar</button>
    </form>
    
<?php    
}    ?>
</div>