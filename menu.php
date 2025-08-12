<style>
@import url('https://fonts.googleapis.com/css?family=Special+Elite');
.ant{
	font-family: 'Special Elite', cursive;
	text-align: center;
	line-height: 120px;
	color: white;
	font-size: 50px;
}
.ant img{
	border-style: none;
    height: 136px;
    position: relative;
    z-index: 3;
    vertical-align: bottom;
}
</style>
<div class="header">
<?php
global $PDO, $SEMESTRE, $DEBUG;
if(isset($_SESSION['ra'])){      ?>
    <div id="ident"><span>Olá <?php
    $result = $PDO->prepare("SELECT * FROM aluno WHERE ra = '$_SESSION[ra]'");
    $result->execute();
    $row = $result->fetch();
    echo "$row[nome]&nbsp;&nbsp;&nbsp;&nbsp;</span>";
    echo " <a href='" . meuLink("sair") . "'><i class='material-icons'>close</i></a></div>";

    $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $_SESSION['nome'] = $row['nome'];  
?>
<div>
<ul class="mui-tabs__bar menu">
  <li class="<?php echo ($actual_link==meuLink(""))?"mui--is-active":""; ?>">
    <a href="<?php echo meuLink("") ; ?>">
      <i class="material-icons">home</i>
    </a>
  </li>
  <li class="<?php echo ($actual_link==meuLink("entregues"))?"mui--is-active":""; ?>">
      <a href="<?php echo meuLink("entregues") ; ?>">
        <i class="material-icons">assessment</i>
      </a>
  </li>
  <li class="<?php echo ($actual_link==meuLink("historico"))?"mui--is-active":""; ?>">
      <a href="<?php echo meuLink("historico") ; ?>">
      <i class="material-icons">history</i>
      </a>
  </li>
  
</ul>
</div>
<div class="mui--text-display1 ant" style="text-align: left;
    margin-top: -50px;
    margin-left: 111px;">
	Ant
	<img src="<?php echo meuLink("icons/ant.png"); ?>" />
</div>
<?php  
}else{
?>
	<div class="mui--text-display1 ant">
		Ant
		<img src="<?php echo meuLink("icons/ant.png"); ?>" />
	</div>
<?php
}
?>
	
</div>