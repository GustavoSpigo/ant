<br><?php 
    $i=false;
    foreach ($materias as $key => $value) {
        ?>
        <div class="mui--text-title"><?php echo $key ?></div>
        <div class="mui-container-fluid">
        <?php
        foreach ($rows as $linha) {
            if($linha['nomeMateria']==$key){
                ?>
                <div class='mui-row' <?php echo ($i) ? 'style="background-color: #f1f1f1;"' : ''; $i=!$i;?>>
                    <div class='mui-col-md-4'>
                        <?php echo $linha['nomeTrabalho'] ?>
                    </div>
                    <div class='mui-col-md-4'>
                        <?php echo $linha['nota']==""? "Não avaliado" : $linha['nota'] ?>
                    </div>
                    <div class='mui-col-md-4'>
                        <?php echo $linha['comentarioProfessor'] ?>
                    </div>
                </div>
                <?php
            }
            
        }
        ?></div><br><?php
    } ?>