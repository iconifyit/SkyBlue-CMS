<?php

require_once('wym.base.php');

$index = Filter::get($_GET, 'index', '0');
if (strlen($index) > 3) die;
?>
<div>
<h2><?php __('WYM.PASTE.HEADER'); ?></h2>
<form id="pasteform" method="get" action="javascript:return void(0);">
  <div>
      <textarea name="text" id="paste_text" rows="12"></textarea>
  </div>
  <div class="inputdivlast">
  <input type="button" 
         class="sb-button ui-state-default ui-corner-all"
         name="save" 
         value="Ok" 
         onclick="SBC.InsertPaste(<?php echo $index; ?>); SBC.hideOverlay(<?php echo $index; ?>);"
         />
  <input type="button" 
         class="sb-button ui-state-default ui-corner-all" 
         name="cancel" 
         value="Cancel" 
         onclick="SBC.hideOverlay(<?php echo $index; ?>);"
         />
  </div>
</form>
</div>