<div class="header">
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
    </ul>
  </div>
</div>