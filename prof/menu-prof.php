<div class="header"><?php
if(isset($_SESSION['prof'])){      ?>
  <div id="ident"><span>Olá Prof. <?php
  $result = mysql_query("SELECT * FROM professor WHERE login = '$_SESSION[prof]'");
  $row = mysql_fetch_array($result, MYSQL_ASSOC);
  echo "$row[titulacao] $row[nome]&nbsp;&nbsp;&nbsp;&nbsp;</span>";
  echo " <a href='" . meuLink("sair") . "'><i class='material-icons'>close</i></a></div>"; 
  mysql_free_result($result);
}

$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";    
?>
  <div>
    <ul class="mui-tabs__bar menu">
      <li class="<?php echo ($actual_link==meuLink("prof/"))?"mui--is-active":""; ?>">
        <a href="<?php echo meuLink("prof/") ; ?>">
          <i class="material-icons">home</i>
        </a>
      </li>
      <li class="<?php echo ($actual_link==meuLink("prof/trabalhos"))?"mui--is-active":""; ?>">
          <a href="<?php echo meuLink("prof/trabalhos") ; ?>">
            <i class="material-icons">fiber_new</i>
          </a>
      </li>
      <li class="<?php echo ($actual_link==meuLink("prof/explorar"))?"mui--is-active":""; ?>">
          <a href="<?php echo meuLink("prof/explorar") ; ?>">
            <i class="material-icons">folder</i>
          </a>
      </li>
      <li class="<?php echo ($actual_link==meuLink("prof/alunos"))?"mui--is-active":""; ?>">
          <a href="<?php echo meuLink("prof/alunos") ; ?>">
            <i class="material-icons">person</i>
          </a>
      </li>
      <li class="<?php echo ($actual_link==meuLink("prof/provaSQL"))?"mui--is-active":""; ?>">
          <a href="<?php echo meuLink("prof/provaSQL") ; ?>">
            <i class="material-icons">playlist_add_check</i>
          </a>
      </li>
      <li class="<?php echo ($actual_link==meuLink("prof/config"))?"mui--is-active":""; ?>">
          <a href="<?php echo meuLink("prof/config") ; ?>">
            <i class="material-icons">settings</i>
          </a>
      </li>
    </ul>
  </div>
</div>