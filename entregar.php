<?php
global $PDO, $SEMESTRE, $DEBUG, $trabalho;
$result = $PDO->prepare($sql = "SELECT t.nome as nomeTrabalho, 
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
$result->execute();
// Fetch o resultado
if ($result->rowCount() == 0) {
    die("Erro: Trabalho não encontrado.");
}
$row = $result->fetchAll(PDO::FETCH_ASSOC);

echo "<h2>Entregar trabalho de <strong>" . $row['nomeMateria'] . '</strong></h2>';
$arquivos = explode('|', $row['arquivos']);
//echo $sql;
?>
<div class="mui-panel panel-content">
    <div class="mui--text-headline" style="text-align: center;">Entrega de trabalho</div>
    <div class="mui-divider" style="margin-left: -20px;margin-right: -20px;"></div>
    <br>
    <div class="mui--text-caption" style="color:#aaa;">Matéria</div>
    <div class="mui--text-title">
        <?php echo $row["nomeMateria"]; ?>
    </div>
    <div class="mui--text-caption" style="color:#aaa;">Nome do Trabalho</div>
    <div class="mui--text-title">
        <?php echo $row["nomeTrabalho"]; ?>
    </div>
    <div class="mui--text-caption" style="color:#aaa;">Descrição do Trabalho</div>
    <div class="mui--text-title">
        <?php echo $row["descricao"]; ?>
    </div>
    <br>
    <br>
    <form method="POST" enctype="multipart/form-data" action="<?php echo meuLink("entregue/" . $trabalho) ?>">
        <?php if ($row["maximoAlunos"] > 1) {
            ?>
            <div class="mui--text-headline">Integrantes da equipe (<?php echo $row['maximoAlunos'] ?>)</div>
            <div class="mui-panel" id="containerAluno">
                <div class="aluno">
                    <script>
                        numMaximoAlunos = <?php echo $row["maximoAlunos"]; ?>;
                    </script>
                    <?php include("addAluno.php"); ?>
                    <div class="mui--text-title">
                        <?php echo $_SESSION['nome']; ?> (<?php echo $_SESSION['ra']; ?>)
                        <!--a href="#"><i class="material-icons">delete</i></a-->
                        <input name="aluno" value="<?php echo $_SESSION['ra']; ?>" type="hidden">
                    </div>
                </div>
            </div>
            <?php
        }
        if (@$arquivos[0] == "link") {
            ?>
            <div class="mui-textfield">
                <input type="text" placeholder="Link" name="link"/>
            </div>

            <?php
        } else {
            ?>


            <div class="mui--text-headline">Arquivo<?php echo (count($arquivos) > 1) ? "s" : ""; ?> para entrega</div>

            <?php foreach ($arquivos as $key => $value) { ?>
                <label>Arquivo (<?php echo $value; ?>)</label>
                <br>
                <?php if ($value == 'pasta') { ?>
                    <input type="file" name="arquivos[]" multiple="" directory="" webkitdirectory="" mozdirectory=""/>
                <?php } else { ?>
                    <input type="file" accept=".<?php echo $value; ?>" name="arquivo<?php echo $key ?>"/>
                <?php } ?>
                <br><br>
            <?php }
        }
        ?>
        <div class="mui-textfield">
            <textarea placeholder="Comentários do aluno" name="comentario_aluno"></textarea>
        </div>
        <input type="submit" class="mui-btn mui-btn--raised" value="Enviar"/>
    </form>
    <br>
</div>