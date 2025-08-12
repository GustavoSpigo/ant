<?php

class Aluno{
    public $nome;
    public $ra;
    function Aluno($ra){
        $result = mysql_query($sql = "SELECT ra, nome FROM `aluno` WHERE ra = $ra");
        $row = mysql_fetch_array($result, MYSQL_ASSOC);
        $this->ra = $ra;
        $this->nome = $row["nome"];
    }
}

function mkpath($path)
{
  if(@mkdir($path) or file_exists($path)) return true;
  return (mkpath(dirname($path)) and mkdir($path));
}

function data($data, $format = 'd/m/Y'){
    if(date_format(date_create($data) , 'd/m/Y')=='30/11/-0001'){
        return "";
    }
    if($data==''){
        return "";
    }
    return date_format(date_create($data) , $format);
    
}

function formataSemestre($t){
    return substr($t, 5, 1) . "&deg; semestre de " . substr($t, 0, 4);
}
function getIcon($t){
    return "<img class='icon' src='" . meuLink('') . "/icons/$t.png' />";
}
function getTipo($t){
    return explode(".",$t)[count(explode(".",$t))-1];
}
function formPadrao($row){
	
	$retorno = <<<EOT
	<form class="mui-form" method="POST" target="ifr"> 
        <div class="mui-textfield">
            <textarea style="width: 100%;height: 150px;" placeholder="Comentário do professor" name="comment">$row[comentarioProfessor]</textarea>
        </div>
        <div class="mui-textfield" style="display: inline-block;">
            <input type="text" placeholder="Nota" value="$row[nota]" style="width: 120px;font-size: 50px;height: 60px;text-align: right;" name="nota" >
        </div>
        <button type="submit" class="mui-btn mui-btn--raised">Enviar</button>
        <br>
        <input type="submit" name="pre_nota" value="10" class="mui-btn mui-btn--raised">
        <input type="submit" name="pre_nota" value="6" class="mui-btn mui-btn--raised">
        <input type="submit" name="pre_nota" value="0" class="mui-btn mui-btn--raised">
        <input type="hidden" name="ra" value="$ra">
        <input type="hidden" name="filesRaw" value="$row[files]">
    </form>	
    <br>
EOT;
	return $retorno;
}
function salvaNota($trabalho, $post){
	$alterar = mysql_query(" SELECT * FROM entrega WHERE idTrabalho = " . $trabalho . 
                          " AND files = '$post[filesRaw]' ");

    $comment = mysql_real_escape_string($post['comment']);
    
    $nota = str_replace(",",".",$post['nota']);
    if(isset($post['pre_nota'])){
    	$nota = $post['pre_nota'];
    }
    while ($linha = mysql_fetch_array($alterar, MYSQL_ASSOC)) {
       mysql_query( " UPDATE `entrega` SET `nota`=$nota,`comentarioProfessor`='$comment' 
        WHERE `idTrabalho`=$trabalho AND `idAluno`='$linha[idAluno]' ");
        
    }
}