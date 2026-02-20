<?php

require_once('wym.base.php');

$index = Filter::get($_GET, 'index', '0');
if (strlen($index) > 3) die;

?>
<div>
<h2><?php __('WYM.TABLE.HEADER'); ?></h2>
<form id="tableform" method="get" action="javascript:return void(0);">
  <div class="inputdiv">
      <h3 style="margin-bottom: 4px;"><?php __('WYM.TABLE.ROWS'); ?></h3>
      <input type="text" 
             name="tablerows" 
             value="" 
             class="inputfield" 
             id="tablerows"
             />
  </div>
  <div class="inputdiv">
      <h3 style="margin-bottom: 4px;"><?php __('WYM.TABLE.COLS'); ?></h3>
      <input type="text" 
             name="tablecolumns" 
             value="" 
             class="inputfield" 
             id="tablecolumns"
             />
  </div>
  <div class="inputdiv">
      <h3 style="margin-bottom: 4px;"><?php __('WYM.TABLE.CAPTION'); ?></h3>
      <input type="text" 
             name="tablecaption" 
             value="" 
             class="inputfield" 
             id="tablecaption"
             />
  </div>
  <div class="inputdiv">
      <h3 style="margin-bottom: 4px;"><?php __('WYM.TABLE.CLASS'); ?></h3>
      <input type="text" 
             name="tableclass" 
             value="" 
             class="inputfield" 
             id="tableclass"
             />
  </div>
  <div class="inputdivlast">
  <input type="button" 
         class="sb-button ui-state-default ui-corner-all"
         name="save" 
         value="Ok" 
         onclick="SBC.InsertTable(<?php echo $index; ?>); SBC.hideOverlay(<?php echo $index; ?>);"
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