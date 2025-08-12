<?php
    $result = mysql_query("SELECT * FROM questao WHERE idQuestao = " . $idQuestao);
    $linhaQuestao = mysql_fetch_array($result, MYSQL_ASSOC);

    if(isset($_POST['pontuacao']) &&
       isset($_POST['idProva']) &&
       isset($_POST['idAluno']) &&
       isset($_POST['idQuestao']))
    {
        mysql_query( "UPDATE prova_resposta SET pontuacao = $_POST[pontuacao]
               WHERE idProva = '$_POST[idProva]'
                 AND idAluno = '$_POST[idAluno]'
                 AND idQuestao = '$_POST[idQuestao]'");
        ?>
<script>
parent.location.reload();
</script>
        <?php
        exit();
    }
    
?>
<a href="../<?php echo $idProva ?>" class="mui-btn mui-btn--primary mui-btn--flat">Voltar para a prova</a>
<div class="mui--text-headline" style="text-align: center;">Enunciado</div>
<pre><?php echo $linhaQuestao['enunciado'] ?></pre>
<div class="mui--text-headline" style="text-align: center;">Query Correta</div>
<br><br>
<pre><?php echo $linhaQuestao['queryCorreta'] ?></pre>
<br><br>
<table class="mui-table mui-table--bordered">
  <thead>
    <tr>
      <th>Query Aluno</th>
      <th>Pré</th>
      <th>Nota</th>
    </tr>
  </thead>
  <tbody>
<?php 
    $result = mysql_query("SELECT *
                            FROM prova_resposta pr
                           INNER JOIN aluno a ON a.ra = pr.idAluno
                           WHERE idProva = $idProva AND idQuestao = " . $idQuestao); 
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { 
?>
    <tr title="<?php echo $row['nome'] ?>">
      <td style="max-width: 700px;"><pre style="white-space: normal;"><?php echo trim($row['resposta']) ?></pre></td>
      <td><i class="material-icons">filter_<?php echo $row['preAvaliacao'] ?></i></td>
      <td>
         <form method="post" target="ifr">
            <input type="hidden" name="idProva" value="<?php echo $idProva ?>">
            <input type="hidden" name="idQuestao" value="<?php echo $idQuestao ?>">
            <input type="hidden" name="idAluno" value="<?php echo $row['ra'] ?>">            
            <input type="submit" name="pontuacao" value="0" class="mui-btn mui-btn--small mui-btn--primary <?php echo (($row['pontuacao']==0)? '' : 'mui-btn--flat' ) ?>" />
            <input type="submit" name="pontuacao" value="1" class="mui-btn mui-btn--small mui-btn--primary <?php echo (($row['pontuacao']==1)? '' : 'mui-btn--flat' ) ?>" />
            <input type="submit" name="pontuacao" value="2" class="mui-btn mui-btn--small mui-btn--primary <?php echo (($row['pontuacao']==2)? '' : 'mui-btn--flat' ) ?>" />
            <input type="submit" name="pontuacao" value="3" class="mui-btn mui-btn--small mui-btn--primary <?php echo (($row['pontuacao']==3)? '' : 'mui-btn--flat' ) ?>" />
            <input type="submit" name="pontuacao" value="4" class="mui-btn mui-btn--small mui-btn--primary <?php echo (($row['pontuacao']==4)? '' : 'mui-btn--flat' ) ?>" />
         </form>
      </td>
    </tr>
<?php } ?>
  </tbody>
</table>
<iframe src="" frameborder="0" name="ifr" id="ifr" style="display:none"></iframe>