<style>
    .header{
        display:none;
    }
</style>
<?php
include("../functions.php");
include("../preview.php");

if(isset($_POST['comment'])){
    salvaNota($trabalho, $_POST);
    ?>
    <script>
        parent.opener.location.reload();
        parent.close();
    </script>
    <?php
    exit;
}
    $result2 = mysql_query("SELECT * FROM entrega e 
                             INNER JOIN trabalho t ON t.idTrabalho = e.idTrabalho
                             INNER JOIN aluno a ON e.idAluno = a.ra
                             WHERE t.idTrabalho = $trabalho AND e.idAluno = '$ra'");
    $row = mysql_fetch_array($result2, MYSQL_ASSOC);
    ?>
<table style="width: 100%;height: 100%;">
    <tr>
        <td width=25% style="vertical-align:top">
            <div class="mui--text-headline">Nome</div>
            <?php echo $row['nome']; ?>
            <div class="mui--text-headline">Comentário do aluno</div>
            <?php echo $row['comentarioAluno']; ?>
            <div class="mui--text-title">Prazo</div>
            <?php echo data($row['prazo']) ?>
            <div class="mui--text-title">Entrega</div>
            <?php echo data($row['dtEntrega']) ?>
            <?php 
            echo formPadrao($row);
            echo retornaIconesPadrao($row['files'] );
            ?>
            <iframe id="ifr" name="ifr"></iframe>
        </td>
        <td>
        	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/styles/default.min.css">
			<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/highlight.min.js"></script>
			
        	<pre><code class="sql"><?php
        		 echo file_get_contents("/home/spigo594/public_html/trabalhos" . str_replace(';','',$row['files'])); 
        	?></code></pre>
            <!--iframe style="width: 960px; height: 640px;" src="<?php echo meuLink(str_replace(';','',$row['files'])); ?>">
            </iframe-->
            <script>hljs.initHighlightingOnLoad();</script>
        </td>
    </tr>
</table>