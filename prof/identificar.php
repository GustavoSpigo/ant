<?php
session_destroy();
?><div class="mui-panel panel-login">
<form method="POST" target="ifrLogin" action="<?php echo meuLink('prof/login.php'); ?>">
    <div class="mui--text-headline" style="text-align: center;">Quem é você?</div>
    <div class="mui-divider" style="margin-left: -20px;margin-right: -20px;"></div>
    <br>
    <center>
        <div class="mui-textfield">
            <input type="text" name="login" style="text-align: center;">
            <label>Login</label>
        </div>
        <div class="mui-textfield">
            <input type="password" name="senha" style="text-align: center;">
            <label>Senha</label>
        </div>
        <input type="submit" class="mui-btn mui-btn--raised" value="Entrar"/>
    </center>
</form>
<iframe src="" name="ifrLogin" frameborder="0" style="display:none"></iframe>
</div>