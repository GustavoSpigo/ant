<?php
function retornaIconesPadrao($html, $files){
	foreach(explode(";", $files) as $cadaTxt){
        if($cadaTxt){
            $html .= "<a href='" . meuLink('') . substr($cadaTxt,1) . "'>";
            $html .= "<div class=\"icon-container\"><table>
                            <tr><td>" . getIcon(getTipo($cadaTxt)) . "</td></tr>
                            <tr><td>" . explode("/",$cadaTxt)[count(explode("/",$cadaTxt))-1] . "</td></tr>
                    </table></div>
                    </a>";
            
        }
    }
    
    return $html;
}

function retornaIcones($files, $tipoTrabalho){
    $html = "";
    if(file_exists("preview-" . $tipoTrabalho . ".php")){
        include("preview-" . $tipoTrabalho . ".php");
        include("preview-container.php");
    }else{
        $html .= retornaIconesPadrao($html, $files);
    }
    
    return $html;
}


?>