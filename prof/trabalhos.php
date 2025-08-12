<?php ?><div class="mui-panel panel-content">
    <div class="mui--text-headline" style="text-align: center;"><?php
if($trabalho==""){
    echo "Criar uma entrega de trabalho";
    $row = null;
    $semestre = date("Y") . '/' . ((date("m") < 7)? "1": "2");
}else{
    $result = mysql_query($sql = "SELECT * 
                            FROM trabalho
                            WHERE idTrabalho = $trabalho");
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    echo $row["nome"];
    $semestre = $row['semestre'];
}
?></div><br><?php
if(isset($_POST['idTrabalho'])){
    if($trabalho==""){
        mysql_query("INSERT INTO trabalho
                   (semestre, nome, descricao, idMateria, tipoTrabalho, prazo, maximoAlunos) 
                    VALUES
                   ('" . mysql_real_escape_string($_POST['semestre']) . "',
                    '" . mysql_real_escape_string($_POST['nome']) . "',
                    '" . mysql_real_escape_string($_POST['descricao']) . "',
                    " . mysql_real_escape_string($_POST['idMateria']) . ",
                    " . mysql_real_escape_string($_POST['tipoTrabalho']) . ",
                    '" . mysql_real_escape_string($_POST['prazo']) . "',
                    " . mysql_real_escape_string($_POST['maximoAlunos']) . " )");
        echo "Trabalho '" . $_POST['nome'] . "' inserido com sucesso";
    }else{
        mysql_query("UPDATE trabalho SET 
                            semestre='" . mysql_real_escape_string($_POST['semestre']) . "',
                            nome='" . mysql_real_escape_string($_POST['nome']) . "',
                            descricao='" . mysql_real_escape_string($_POST['descricao']) . "',
                            idMateria=" . mysql_real_escape_string($_POST['idMateria']) . ",
                            tipoTrabalho=" . mysql_real_escape_string($_POST['tipoTrabalho']) . ",
                            prazo='" . mysql_real_escape_string($_POST['prazo']) . "',
                            maximoAlunos=" . mysql_real_escape_string($_POST['maximoAlunos']) . " 
                            WHERE idTrabalho=" . mysql_real_escape_string($_POST['idTrabalho']));
        echo "Trabalho '" . $_POST['nome'] . "' alterado com sucesso";
    }
        
    //echo "<pre>";
    //print_r($row);
    //echo "</pre>";
}else{
    ?>
    <div class="mui-divider" style="margin-left: -20px;margin-right: -20px;"></div>
    <br><br>
    <form method="POST">
    <input type="hidden" name="idTrabalho" value="<?php echo $row['idTrabalho'] ?>" >
    <input type="hidden" name="semestre" value="<?php echo $semestre ?>" >
    <div class="mui-textfield mui-textfield--float-label">
        <input type="text" name="nome" value="<?php echo $row['nome'] ?>" required>
        <label>Nome do Trabalho</label>
    </div>

    <div class="mui-textfield mui-textfield--float-label">
        <textarea name="descricao" required><?php echo $row['descricao'] ?></textarea>
        <label>Descrição</label>
    </div>

    <div class="mui-select">
    	<select name="idMateria" required>
            <option></option><?php
            $combo = mysql_query("SELECT m.* 
            FROM materia m
           INNER JOIN professorMateria p ON m.idMateria = p.idMateria 
                                        AND p.semestre = '$SEMESTRE' 
                                        AND p.idProfessor = $_SESSION[idProfessor]
           ORDER BY m.nome");
            while($cadaCombo = mysql_fetch_array($combo, MYSQL_ASSOC)){
                ?><option <?php
                    echo "value='$cadaCombo[idMateria]'";
                    echo $cadaCombo['idMateria']==$row['idMateria']? " selected": "";
                ?>><?php
                    echo $cadaCombo['nome'] 
                ?></option><?php
            }
        ?>
        </select>
        <label>Disciplina</label>
    </div>

    <div class="mui-select">
        <select name="tipoTrabalho" required>
            <option></option><?php
            $combo = mysql_query("SELECT * FROM tipoTrabalho");
            while($cadaCombo = mysql_fetch_array($combo, MYSQL_ASSOC)){
                ?><option <?php
                    echo "value='$cadaCombo[idTipoTrabalho]'";
                    echo $cadaCombo['idTipoTrabalho']==$row['tipoTrabalho']? " selected": "";
                ?>><?php
                    echo "$cadaCombo[descricao] ($cadaCombo[arquivos])";
                ?></option><?php
            }
        ?>
        </select>
        <label>Tipo de Entrega</label>
    </div>

    
    <div class="mui-textfield">
        <input type="date" name="prazo" value="<?php echo $row['prazo'] ?>" required />
        <label>Prazo</label>
    </div>

    <div class="mui-textfield mui-textfield--float-label">
        <input type="number" name="maximoAlunos" value="<?php echo $row['maximoAlunos'] ?>" required >
        <label>Quantidade máxima de alunos por trabalho</label>
    </div>
    <button type="submit" class="mui-btn mui-btn--raised">Salvar</button>
    </form>
</div><?php
}
?>