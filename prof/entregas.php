<?php
require("../functions.php");
$array = split('-', $_GET['action']);
$idTrabalho = $array[count($array) - 1];
$result = mysql_query(" SELECT * FROM trabalho WHERE idTrabalho = " . $idTrabalho);
$row = mysql_fetch_array($result, MYSQL_ASSOC);

if(isset($_POST['comment'])){
    $alterar = mysql_query(" SELECT * FROM entrega WHERE idTrabalho = " . $idTrabalho . 
                          " AND files = '$_POST[filesRaw]' ");
    $comment = mysql_real_escape_string($_POST['comment']);
    $nota = str_replace(",",".",$_POST[nota]);
    while ($linha = mysql_fetch_array($alterar, MYSQL_ASSOC)) {

        mysql_query(" UPDATE `entrega` SET `nota`=$nota,`comentarioProfessor`='$comment' 
        WHERE `idTrabalho`=$idTrabalho AND `idAluno`='$linha[idAluno]' ");
    }
}

?>
<script>
  
  function avaliar(ra,files,entrega,prazo,comentarioAluno,nota,comentarioProfessor){
    var modalEl = document.createElement('div');
    modalEl.style.display = 'table';
    modalEl.style.margin = '100px auto';
    modalEl.style.borderRadius = '5px';
    modalEl.className = "mui-panel";
    futuroHTML = document.getElementById("moldeModal").innerHTML;
    futuroHTML = futuroHTML.replace("$prazo$", prazo);
    futuroHTML = futuroHTML.replace("$entregue$", entrega);
    futuroHTML = futuroHTML.replace("$comentario$", comentarioAluno);
    futuroHTML = futuroHTML.replace("$ra$", ra);
    futuroHTML = futuroHTML.replace("$filesRaw$", files);
    futuroHTML = futuroHTML.replace("$comentarioProfessor$", comentarioProfessor);
    futuroHTML = futuroHTML.replace("$nota$", nota);

    var a = files.split(";");
    var futuroFiles = "";
    for (i in a) {
        if(a[i]!=""){
            futuroFiles += "<div class='cada-arquivo'>" + 
                                    "<a href='<?php echo meuLink('') ?>" + a[i] + "'>" + a[i].split("/")[a[i].split("/").length-1] + "</a>" +
                                 "</div>";
        }
    }
    futuroHTML = futuroHTML.replace("$files$", futuroFiles);
    
    modalEl.innerHTML = futuroHTML;
    mui.overlay('on', modalEl);
  }
</script>
<style>
    .cada-arquivo a::before{
        content: "file_download";
        font-family: 'Material Icons';
        font-size: 50px;
        vertical-align: middle;
    }
    .botoes-acao{
        position: absolute;
        margin-top: -8px;
    }
    .botoes-acao .mui-btn{
        padding: 5px 5px 0px 5px;
        margin: 0px;
    }
    .mui-table{
        width: 400px;
    }
    .meu-badge{ 
    	/*content: attr(data-badge);*/
    	font-weight: 600;
	    font-size: 12px;
	    border-radius: 31%;
	    background: #2196f3;
	    color: #fff;
	    padding-right: 3px;
	    padding-left: 3px;
	    position: absolute;
	    top: -5px;
	    left: 40px;
	    font-style: initial;
	    box-shadow: -2px 2px 8px #000000b0;
    }
   /* .botoes-acao .meu-badge{
    	display:none;
    }
    .botoes-acao:hover .meu-badge{
    	display:initial;
    }*/
    .green{
    	background-color: green;
    }
    .red{
    	background-color: #F44336;
    }
    .green.mui-btn--primary:active, .green.mui-btn--primary:focus, .green.mui-btn--primary:hover {
	    color: #FFF;
	    background-color: #42a542;
	}
    .meu-comment{
		position: absolute;
		background: rgba(0, 0, 0, 0.87);
		border-radius: .4em;
		padding: 5px;
		border-top-left-radius: 0;
		top: 30px;
		left: 78px;
		max-width: 220px !important;
		width: max-content;
		z-index: 50;
		color: white;
    }
    .meu-comment:after {
		content: '';
		position: absolute;
		top: 0;
		left: 17px;
		width: 0;
		height: 0;
		border: 17px solid transparent;
		border-bottom-color: rgba(0, 0, 0, 0.87);
		border-top: 0;
		margin-left: -17px;
		margin-top: -17px;
		border-left: 0;
	}
	.comment-container .meu-comment{
    	display:none;
    }
    .comment-container:hover .meu-comment{
    	display:initial;
    }
</style>
<div class="mui-panel panel-content">
    <center>
    <div class="mui--text-headline" style="text-align: center;"><?php echo $row['nome']; ?></div>
    <div class="mui-divider" style="margin-left: -20px;margin-right: -20px;"></div>
    <br>
    <div class="mui--text-title"><?php echo $row['descricao']; ?></div>
    Prazo: <?php echo date_format(date_create($row["prazo"]) , 'd/m/Y');?>
    <br><br>
    <table class="mui-table">
    <thead>
        <tr>
        <th style="width: 1%;">Aluno</th>
        <th style="width: 90%;">RA</th>
        <th>Informações</th>
        <th>Ações</th>
        </tr>
    </thead>
    <tbody>
<?php 
$prazo  = date_format(date_create($row["prazo"]) , 'Y-m-d');

$result = mysql_query(
" SELECT *
FROM trabalho T
INNER JOIN materia M ON M.idMateria = T.idMateria
RIGHT JOIN aluno_materia AM ON AM.idMateria = M.idMateria AND T.semestre = AM.semestre
RIGHT JOIN aluno A ON AM.idAluno = A.ra
 LEFT JOIN entrega E ON E.idTrabalho = T.idTrabalho AND E.idAluno = A.ra
WHERE T.idTrabalho = $idTrabalho
   ORDER BY A.nome ");
$i=false;
   while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
?>
        <tr <?php echo ($i) ? 'style="background-color: #f1f1f1;"' : ''; $i=!$i;?>>
        <td style="white-space: nowrap;"><?php echo $row['nome']; ?></td>
        <td><?php echo $row['ra']; ?></td>
        <td><div class="botoes-acao" style="margin-top: -2px;"><?php
        $entrega  = data($row["dtEntrega"], 'Y-m-d');

        if($row['files']!="") { ?>
                <i class="material-icons">attach_file</i>
    <?php if($entrega <= $prazo) { ?>
    			<span class="comment-container">
	                <i class="material-icons">timer</i>
	            	<span class="meu-comment" style="font-family: 'Roboto Mono', monospace;">
	            		<?php echo "Prazo: &nbsp;&nbsp;". date_format(date_create($prazo) , 'd/m/Y'); ?>
	            		<br>
	            		<?php echo "Entrega: ". date_format(date_create($row["dtEntrega"]) , 'd/m/Y H\hi'); ?>
	            	</span>
	            </span>
    <?php
           }else{ ?>
        		<span class="comment-container">
                	<i class="material-icons">timer_off</i>
                	<span class="meu-comment">
	            		<?php echo "Prazo: ". date_format(date_create($prazo) , 'd/m/Y'); ?>
	            		<br>
	            		<?php echo "Entrega: ". date_format(date_create($row["dtEntrega"]) , 'd/m/Y H\hi'); ?>
	            	</span>
                </span>
	<?php
           }   
        }
        
        if($row['comentarioAluno']!="") { ?>
                <span class="comment-container">
	                <i class="material-icons">comment</i>
	                <span class="meu-comment"><?php echo $row['comentarioAluno'] ?></span>
                </span>
  <?php }
       //echo data($row["dtEntrega"]); 
?></div></td>
            <td>
                <?php if($row['files']!="") { ?>
                <div class="botoes-acao">&nbsp;&nbsp;
                <?php if($row['nota']==""){ ?>
                    <button class="mui-btn mui-btn--primary" 
                <?php }else if($row['nota'] < 6){ ?>
                    <button class="mui-btn mui-btn--danger red" 
                <?php }else{ ?>
                    <button class="mui-btn mui-btn--primary green"
                <?php } 
                    $result2 = mysql_query("SELECT * FROM trabalho t 
                                             WHERE t.idTrabalho = $idTrabalho ");
                    $row2 = mysql_fetch_array($result2, MYSQL_ASSOC);
                    if(file_exists("avaliar-" . $row2['tipoTrabalho'] . ".php")){
                    ?>  
                        onclick="javascript:window.open('<?php echo meuLink("prof/avaliar/". $idTrabalho . "/" . $row["ra"]); ?>','winname','toolbar=yes,scrollbars=yes,resizable=yes,top=0,left=0,height=' + (screen.height - 100) + ',width=' + screen.width );">
                    <?php
                    }else{
                    ?>
                        onclick="avaliar('<?php echo $row['ra'] ?>',
                                         '<?php echo $row['files'] ?>',
                                         '<?php echo data($entrega) ?>', 
                                         '<?php echo data($prazo) ?>',
                                         '<?php echo str_replace( array("\r\n","\r","\n"), '<br />', htmlentities($row['comentarioAluno'])) ?>',
                                         '<?php echo ($row['nota']=="") ? "" : number_format($row['nota'], 1, ',', ' '); ?>',
                                         '<?php echo str_replace( "\r", '<br />', htmlentities($row['comentarioProfessor'])) ?>')">
                <?php
                    }
                ?>        
                <?php if($row['nota']==""){ ?>
                    <i class="material-icons">thumbs_up_down</i>
                </button>
                <?php }else if($row['nota'] < 6){ ?>
                    <i class="material-icons">thumb_down</i>
                </button>
                <i class="meu-badge red"><?php echo $row['nota']; ?></i>
                <?php }else{ ?>
                    <i class="material-icons">thumb_up</i>
                </button>
                <i class="meu-badge green"><?php echo $row['nota']; ?></i>
                <?php } ?>
                
                
                <?php }else{ ?>
                    <div class="botoes-acao" style="margin-top: -2px;">&nbsp;&nbsp;&nbsp;
                        
                            <i class="material-icons">sentiment_very_dissatisfied</i>
                        
                    </div>
                <?php } ?>
                </div>
            </td>
        </tr>
   <?php } ?>
    </tbody>
    </table>
    </center>
</div>
<div id="moldeModal" style="display:none;">
    <table style="width:600px;">
        <tr>
            <td>Prazo: $prazo$</td>
            <td>Entregue em: $entregue$</td>
        </tr>
        <tr>
            <td colspan="2"><br><br>$files$</td>
        </tr>
        <tr>
            <td colspan="2">$comentario$</td>
        </tr>
        <tr>
            <td colspan="2">
                <form class="mui-form" method="POST"> 
                <table><tr><td style="padding-right: 30px;">
                    <div class="mui-textfield">
                        <textarea style="width: 444px;height: 92px;" placeholder="Comentário do professor" name="comment">$comentarioProfessor$</textarea>
                    </div>
                    </td><td>
                    <div class="mui-textfield">
                        <input type="text" placeholder="Nota" style="width: 110px;font-size: 50px;height: 60px;text-align: right;" name="nota" value="$nota$">
                    </div>
                    <button type="submit" class="mui-btn mui-btn--raised">Enviar</button>
                    <input type="hidden" name="ra" value="$ra$">
                    <input type="hidden" name="filesRaw" value="$filesRaw$">
                    </td></tr></table>
                </form>
            </td>
        </tr>
    </table>
</div>