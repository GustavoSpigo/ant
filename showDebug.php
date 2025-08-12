<div id="iconDebug" style="position:absolute; top: 0px; left:0px;">
<i class="material-icons" style="color: #fff;font-size: 40px;">bug_report</i>
<div id="meuDebug" style="position:absolute; top: 30px; left:30px;min-width:600px">
<table width="100%" style="background:#fff;">
    <tr>
      <td colspan="2" style="border:1px solid black;"><?php  echo "<pre>".print_r($row,true)."</pre>";  ?></td>
    </tr>
    <tr>
        <td width="50%" style="border:1px solid black;">
        <pre><?php echo "<strong>GET</strong>\n"; print_r($_GET); ?></pre>
        </td>
        <td width="50%" style="border:1px solid black;">
        <pre><?php echo "<strong>POST</strong>\n"; print_r($_POST); ?></pre>
        </td>
    </tr>
    <tr>
        <td width="50%" style="border:1px solid black;">
        <pre><?php echo "<strong>SESSION</strong>\n"; print_r($_SESSION); ?></pre>
        </td>
        <td width="50%" style="border:1px solid black;">
        <pre><?php echo "<strong>FILES</strong>\n"; print_r($_FILES); ?></pre>
        </td>
    </tr>
  <?php if(isset($toDebug)){ ?> 
    <tr>
        <td width="100%" colspan=2 style="border:1px solid black;">
        <pre><?php echo "<strong>$toDebug</strong>\n"; print_r($toDebug); ?></pre>
        </td>
    </tr>
  <?php } ?> 
  </table>
  </div></div> 