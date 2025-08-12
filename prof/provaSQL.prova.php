<style>
a.material-icons{
    color: rgba(0,0,0,.87);
    text-decoration: none;
}
</style>
<?php
    $idProva = explode("/",$_GET['action'])[1];
    $result = mysql_query("SELECT * FROM prova WHERE idProva = $idProva");
    $linhaProva = mysql_fetch_array($result, MYSQL_ASSOC);
    
    $sql = "SELECT DISTINCT a.ra, a.nome 
     FROM prova_resposta pr
    INNER JOIN aluno a ON a.ra = pr.idAluno
    WHERE pr.idProva = $idProva
    ORDER BY a.nome";

if(isset($_POST['nota']))
{
    
    mysql_query("INSERT INTO entrega(idTrabalho, idAluno, files, comentarioAluno, dtEntrega, nota, comentarioProfessor)
                      VALUES ($_POST[idTrabalho],
                              '$_POST[idAluno]',
                              '//',
                              'Entrga automática',
                              NOW(),
                              $_POST[nota],
                              '')");
    ?>
    <script>
    parent.location.reload();
    </script>
            <?php
            exit();
}
?>
<table class="mui-table mui-table--bordered">
  <thead>
    <tr>
      <th>RA</th>
      <th>Nome</th>
      <th>Pré-Avaliação</th>
      <th>Avaliação</th>
      <th style="text-align:right">Nota Final</th>
    </tr>
  </thead>
  <tbody><?php
  $result = mysql_query($sql);
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {  ?>
    <tr>
      <td><a class="mui-btn mui-btn--small mui-btn--flat" href="<?php echo $idProva . "/" . $row['ra']; ?>">
      <?php echo $row['ra']; ?>
                    </a></td>
      <td><?php echo $row['nome']; ?></td>
      <td><?php 
       $q = mysql_query("SELECT * FROM prova_resposta
        WHERE idProva = $idProva 
          AND idAluno = '$row[ra]' ORDER BY idQuestao");
      while ($cq = mysql_fetch_array($q, MYSQL_ASSOC)) {
          $pre = $cq['preAvaliacao'];
          if($pre==0){
            $pre="none";
          }
        echo "<i class=\"material-icons\">filter_$pre</i>";
      }
      @mysql_data_seek($q,0);
      ?></td>
      <td><?php 
      $qtdeQ = 0;
      $somaQ = 0;
      while ($cq = mysql_fetch_array($q, MYSQL_ASSOC)) {
          $qtdeQ++;
          $somaQ += $cq['pontuacao'];
          $pre = $cq['pontuacao'];
          if($pre==0){
            $pre="none";
          }
        echo "<a href=\"$idProva/$cq[idQuestao]\" class=\"material-icons\">filter_$pre</a>";
      }
      @mysql_data_seek($q,0);
      ?></td>
      <td style="text-align:right"><?php
        $resEntrega = mysql_query("SELECT * FROM entrega
                                    WHERE idTrabalho =  $linhaProva[idTrabalho] 
                                      AND idAluno = '$row[ra]' ");

        if(mysql_num_rows($resEntrega) > 0){
            echo number_format($nota = ($somaQ / ($qtdeQ * 4) * 10), 2, ',' ,'.');    
        }else{
        ?>
      <form action="" method="post" target="ifr">
      <?php
        echo number_format($nota = ($somaQ / ($qtdeQ * 4) * 10), 2, ',' ,'.');
      ?>
        <input type="hidden" name="idTrabalho" value="<?php echo $linhaProva['idTrabalho'] ?>">
        <input type="hidden" name="idAluno" value="<?php echo $row['ra'] ?>">
        <input type="hidden" name="nota" value="<?php echo $nota ?>">
        <button class="mui-btn mui-btn--small mui-btn--flat material-icons">send</button>
      </form>
        <?php } ?>
      </td>
    </tr>
<?php } ?></tbody>
</table>
<iframe src="" frameborder="0" name="ifr" id="ifr" style="display:none"></iframe>