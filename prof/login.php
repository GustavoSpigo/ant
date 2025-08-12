<?php
  require("../conn.php");
  if ( ! session_id() ) @ session_start();
    $login = preg_replace('/[^A-Za-z0-9\-()<>= "\/]/', '', $_POST['login']);
    $senha = preg_replace('/[^A-Za-z0-9\-()<>= "\/]/', '', $_POST['senha']);
    $result = mysql_query("SELECT * FROM professor WHERE login = '$login' AND senha = md5('$senha')");
    $num_rows = mysql_num_rows($result);
    if($num_rows==1){
      $_SESSION['prof'] = $_POST['login'];
      $row = mysql_fetch_array($result, MYSQL_ASSOC);
      $_SESSION['idProfessor'] = $row['idProfessor'];
?>
<script>
    parent.location = '<?php echo meuLink("prof") ; ?>';
</script>
<?php }else{
      echo "Wrong";
      //die("RA n&atilde;o encontrado <br><a href='" . meuLink('') . "'>Voltar</a>");
    }
?><pre>
    <?php echo "<strong>POST</strong>\n"; print_r($_POST); ?>
</pre>
<pre>
    <?php echo "<strong>SESSION</strong>\n"; print_r($_SESSION); ?>
</pre>