<div class="mui-panel panel-content">
	<div class="mui--text-headline" style="text-align: center;">Correção de Prova</div>
    <div class="mui-divider" style="margin-left: -20px;margin-right: -20px;"></div>
    <br>
<?php
if(count(explode("/",$_GET['action'])) == 2)
{ 
    include "provaSQL.prova.php";
} 
elseif(count(explode("/",$_GET['action'])) == 3)
{
    include "provaSQL.corrigir.php";
}
else 
{
    if(isset($_POST['acao']))
    {
        if($_POST['acao']=="CriarEntrega")
        {
            $sql = "INSERT INTO trabalho(semestre, 
                                            nome, 
                                            descricao, 
                                            idMateria, 
                                            tipoTrabalho, 
                                            prazo, 
                                            maximoAlunos) 
                                    VALUES ((SELECT semestreAtual FROM configuracoes), 
                                            '$_POST[descricao]', 
                                            '$_POST[descricao]', 
                                            $_POST[idMateria],  
                                            8, 
                                            NOW(), 
                                            99);";

            mysql_query($sql);
            $idTrabalho = mysql_insert_id();
            mysql_query("UPDATE prova SET idTrabalho = $idTrabalho WHERE idProva = $_POST[idProva]");
        }
        
        
    }
    ?>
        <div class="mui--text-headline">Lista de provas</div>
        <table class="mui-table mui-table--bordered">
        <thead>
            <tr>
                <th>Cód. Prova</th>
                <th>Nome</th>
                <th>Entrega vinculada</th>
                <th  style="text-align: center;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $result = mysql_query("SELECT * FROM prova order by 1");
                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { 
            ?>
                <tr>
                    <td><?php echo $row['idProva']; ?></td>
                    <td><?php echo $row['descricao']; ?></td>
                    <td><?php
                        if($row['idTrabalho']==0){ ?>
                        <button class="mui-btn mui-btn--small mui-btn--flat mui-btn--danger material-icons"
                                onclick="document.getElementById('vinc<?php echo $row['idProva']; ?>').style.display=''">assignment_late</button>
                        <div id="vinc<?php echo $row['idProva']; ?>" style="display:none">
                        <form method="POST">
                            <input type="hidden" name="idProva" value="<?php echo $row['idProva']; ?>">
                            <input type="hidden" name="descricao" value="<?php echo $row['descricao']; ?>">
                            <input type="hidden" name="acao" value="CriarEntrega">
                            <div class="mui-select"><select name="idMateria">
                                <option value="4">Persistência em Banco de Dados</option>
                                <option value="5">Laboratório de Banco de Dados (Manhã)</option>
                                <option value="6">Laboratório de Banco de Dados (Tarde)</option>
                            </select></div>
                            <button class="mui-btn mui-btn--raised mui-btn--primary">Criar</button>
                        </form>
                        </div>
                    <?php }else{ ?>
                        <i class="material-icons" style="height: 30.6px;padding: 0 16px;margin: 6px 0;vertical-align: middle;" title="<?php echo  $row['idTrabalho']; ?>">assignment_turned_in</i>
                    <?php } ?>
                    </td>
                    <td style="text-align: center;">
                    <a class="mui-btn mui-btn--small mui-btn--flat mui-btn--danger material-icons" href="provaSQL/<?php echo $row['idProva']; ?>">
                        assignment_late
                    </a>    
                    </td>
                </tr>
            <?php        
                }
            ?>
            
        </tbody>
        </table>
    </div>
<?php
}
?>