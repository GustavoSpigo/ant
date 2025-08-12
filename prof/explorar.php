
<div class="mui-panel panel-content">
    <div class="mui--text-headline" style="text-align: center;">Explorar entregar</div>
    <div class="mui-divider" style="margin-left: -20px;margin-right: -20px;"></div>
    <div class="mui--text-headline">Escolha os filtros</div>

    <form method="POST">
        <div class="mui-select">
            <select name="semestre">
                
                <?php
                $result = mysql_query("SELECT DISTINCT semestre FROM trabalho ORDER BY 1 DESC");
                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { 
                    echo "<option " . ((@$_POST['semestre']==$row['semestre'])?' selected ':'') . ">$row[semestre]</option>";
                }
                ?><option>%</option>
            </select>
            <label>Semestre</label>
        </div>
        <div class="mui-checkbox">
            <label>
            <input type="checkbox" value="" name="campoVazio" <?php echo ((isset($_POST['campoVazio']))?' checked ':'') ?>>
                Campo de arquivo vazio
            </label>
        </div>
        <div class="mui-select">
            <select name="materia">
                
                <?php
                $result = mysql_query("SELECT * FROM materia ORDER BY nome");
                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { 
                    echo "<option value='$row[idMateria]' " . ((@$_POST['materia']==$row['idMateria'])?' selected ':'') . ">$row[nome]</option>";
                }
                ?><option>%</option>
            </select>
            <label>Matéria</label>
        </div>
        <?php
        if(isset($_POST['materia'])){ ?>
            <div class="mui-select">
            <select name="trabalho">
                <?php
                $result = mysql_query("SELECT * FROM trabalho WHERE idMateria = " . $_POST['materia'] .
                                                              " AND semestre LIKE '" . $_POST['semestre'] . "'");
                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { 
                    echo "<option value='$row[idTrabalho]' " . ((@$_POST['trabalho']==$row['idTrabalho'])?' selected ':'') . ">$row[nome]</option>";
                }
                ?><option>%</option>
            </select>
            <label>Trabalho</label>
        </div>
        <?php
        }
        ?>
        <button type="submit" class="mui-btn mui-btn--raised  mui-btn--primary">Submit</button>
    </form>
    <pre>
    <?php
        //print_r($_POST);
        $sql = " SELECT m.nome as Materia, a.nome as Aluno, e.* 
                 FROM entrega e
                 INNER JOIN trabalho t ON t.idTrabalho = e.idTrabalho
                 INNER JOIN aluno a ON e.idAluno = a.ra
                 INNER JOIN materia m ON t.idMateria = m.idMateria
                 WHERE ";
        $filtros = array();
        $filtros[] = "1=1";
        if(isset($_POST['semestre'])){
            $filtros[] = " t.semestre LIKE '$_POST[semestre]'";
        }
        if(isset($_POST['campoVazio'])){
            $filtros[] = " e.files = '' ";
        }
        
        if(isset($_POST['materia'])){
            $filtros[] = " t.idMateria LIKE '$_POST[materia]'";
        }
        if(isset($_POST['trabalho'])){
            $filtros[] = " t.idTrabalho LIKE '$_POST[trabalho]'";
        }

        $sql .= implode("\nAND\n", $filtros);
        $sql .= " ORDER BY 1,2 ";
        //echo $sql;

        echo  "<table class=\"mui-table\">";
        $result = mysql_query($sql);
        $sssql = "";
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            echo "<tr>";
            foreach ($row as $item => $value) {
                if($item=="files"){
                    echo '<td>';
                    foreach(explode(";", $row[$item]) as $cadaLink){
                        if($cadaLink!=""){
                            echo '<a target="blank" href="' . meuLink($cadaLink) . '">'. basename($cadaLink) . '</a>';
                        }
                    }
                    echo '</td>';
                }else{
                    echo '<td>' . $row[$item] . '</td>'; 
                }
            }
            if(isset($_POST['campoVazio'])){
                $diretorioDestino = str_replace($_ENV["SCRIPT_FILENAME"], '', dirname($_SERVER['SCRIPT_FILENAME']));
                $diretorioDestino = str_replace("/prof", "", $diretorioDestino);
                $prefixoSqlPath = "/files/" . $SEMESTRE . "/" . $row['idAluno'] . "/" . $row['idTrabalho'];
                $diretorioDestino .= $prefixoSqlPath;
                $arquivos = scandir($diretorioDestino . "/");
                if(count($arquivos)==3){
                    $futurolink = $prefixoSqlPath."/".$arquivos[2];
                    $futurolink = str_replace("//", "/", $futurolink);
                    $sssql .= "UPDATE entrega SET files = '$futurolink' WHERE idTrabalho = $row[idTrabalho] AND idAluno = '$row[idAluno]' ;\n";
                    //echo "<td><pre>". $sssql ."</pre></td>";
                }else{
                    //echo "<td>Mais de um arquivo ou nenhum</td>";
                }                
            }
            echo '</tr>';
        }
        
        echo  "</table>";
        echo $sssql;
    ?>
    </pre>
</div>