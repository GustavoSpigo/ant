<?php
if (isset($previewDireto)) {
    ?>
    <style>
        .header {
            display: none;
        }
    </style>
    <?php
    function tem($o, $s)
    {
        if ($handle = opendir($o)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == $s) {
                    return true;
                }
            }
            closedir($handle);
        }
        return false;
    }

    $result2 = mysql_query("SELECT * FROM entrega e 
                             INNER JOIN trabalho t ON t.idTrabalho = e.idTrabalho
                             WHERE t.idTrabalho = $trabalho AND idAluno = '$_SESSION[ra]'");
    $row = mysql_fetch_array($result2, MYSQL_ASSOC);


    $diretorioDestino = str_replace($_ENV["SCRIPT_FILENAME"], '', dirname($_SERVER['SCRIPT_FILENAME']))
        . str_replace(substr($row['files'], strrpos($row['files'], '/') + 1), "", $row['files']);

    if (!tem($diretorioDestino, 'html')) {
        $arquivoZIP = $diretorioDestino . substr($row['files'], strrpos($row['files'], '/') + 1);
        $arquivoZIP = str_replace(";", "", $arquivoZIP);
        //echo $arquivoZIP;
        $zip = new ZipArchive;
        if ($zip->open($arquivoZIP) === TRUE) {
            $zip->extractTo($diretorioDestino);
            $zip->close();
            echo 'Arquivo descompactado';
        } else {
            echo 'Falha ao descompactar o arquivo';
        }
    } else {
        ?>
        <br>
        <a href="javascript:location.reload();">Atualize a página</a>
        <script>
            window.location = "<?php echo meuLink(str_replace(substr($row['files'], strrpos($row['files'], '/') + 1), "", $row['files'])); ?>";
        </script>
        <?

    }
} else {
    ?><?php
    global $idTrabalho;
    ?>
    <a href="javascript:window.open('<?php echo meuLink("preview/$idTrabalho"); ?>','winname','directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=980,height=640');"
       ;>
        <div class="icon-container">
            <table>
                <tbody>
                <tr>
                    <td><img class="icon" src="<?php echo meuLink('icons/game.png') ?>"></td>
                </tr>
                <tr>
                    <td>Clique para jogar</td>
                </tr>
                </tbody>
            </table>
        </div>
    </a>
<?php
}
?>