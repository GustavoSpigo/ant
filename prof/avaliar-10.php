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
    function tem($o,$s){
        if ($handle = opendir($o)) {
            while (false !== ($file = readdir($handle))){
                if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == $s){
                    return true;
                }
            }
            closedir($handle);
        }
        return false;
    }
    $result2 = mysql_query("SELECT * FROM entrega e 
                             INNER JOIN trabalho t ON t.idTrabalho = e.idTrabalho
                             WHERE t.idTrabalho = $trabalho AND idAluno = '$ra'");
    $row = mysql_fetch_array($result2, MYSQL_ASSOC);
    
    
    $diretorioDestino = str_replace($_ENV["SCRIPT_FILENAME"], '', dirname($_SERVER['SCRIPT_FILENAME']))
                      . str_replace(substr($row['files'], strrpos($row['files'], '/') + 1),"", $row['files']) ;
    $diretorioDestino = str_replace("/prof/","/",$diretorioDestino);

    if(!tem($diretorioDestino, 'html')){
        $arquivoZIP = $diretorioDestino . substr($row['files'], strrpos($row['files'], '/') + 1);
        $arquivoZIP = str_replace(";","",$arquivoZIP);
        //echo $arquivoZIP;
        $zip = new ZipArchive;
        if ($zip->open($arquivoZIP) === TRUE) {
            $zip->extractTo($diretorioDestino);
            $zip->close();
            //echo 'Arquivo descompactado';
        } else {
            echo 'Falha ao descompactar o arquivo';
        }
    }?>
<table style="width: 100%;height: 100%;">
    <tr>
        <td width=50% style="vertical-align:top">
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
            <iframe id="ifr" name="ifr" style="display:none;"></iframe>
        </td>
        <td>
            <iframe style="width: 960px; height: 640px;"  scrolling="no" src="<?php echo meuLink(str_replace(substr($row['files'], strrpos($row['files'], '/') + 1),"", $row['files'])); ?>">
            </iframe>
        </td>
    </tr>
</table>