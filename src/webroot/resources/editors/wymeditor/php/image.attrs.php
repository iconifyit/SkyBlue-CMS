<?php

require_once('wym.base.php');

$index = Filter::get($_GET, 'index', '0');
$id    = Filter::get($_GET, 'id', null);
$alt   = urldecode(Filter::get($_GET, 'alt', null));
$title = urldecode(Filter::get($_GET, 'title', null));
$class = Filter::get($_GET, 'class', null);

?>
<form id="urlform" method="get" action="javascript:return void(0);">
  <div class="inputdiv">
      <h3><?php __('WYM.IMG_ATTRS.ALT'); ?></h3>
      <input type="text" 
             name="alt-text" 
             value="<?php echo $alt; ?>" 
             class="inputfield" 
             id="alt-text"
             />
  </div>
  <div class="inputdiv">
      <h3><?php __('WYM.IMG_ATTRS.TITLE'); ?></h3>
      <input type="text" 
             name="title-text" 
             value="<?php echo $title; ?>" 
             class="inputfield" 
             id="title-text"
             />
  </div>
  <div class="inputdiv">
      <h3><?php __('WYM.IMG_ATTRS.CLASS'); ?></h3>
      <input type="text" 
             name="css-class" 
             value="<?php echo $class; ?>" 
             class="inputfield" 
             id="css-class"
             />
  </div>
  <div class="inputdivlast">
  <input type="button" 
         class="sb-button ui-state-default ui-corner-all" 
         name="save" 
         value="Ok" 
         onclick="SBC.AddImageAttrs(<?php echo $index; ?>, '<?php echo $id; ?>'); SBC.hideOverlay(<?php echo $index; ?>);"
         />
  <input type="button" 
         class="sb-button ui-state-default ui-corner-all"
         name="cancel" 
         value="Cancel" 
         onclick="SBC.hideOverlay(<?php echo $index; ?>);"
         />
  </div>
</form>
