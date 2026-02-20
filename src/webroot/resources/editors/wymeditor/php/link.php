<?php

require_once('wym.base.php');

define('THUMB_SIZE',72);

$Router = new Router(_SBC_ROOT_);

$index = Filter::get($_GET, 'index', '0');

$files = array();

if (is_dir(SB_DOWNLOADS_DIR)) {
    $files = FileSystem::list_files(SB_DOWNLOADS_DIR, 1);
}

$pages = get_pages();

$pagelist = null;
foreach ($pages as $p) {
    $link = str_replace(FULL_URL, '', $Router->GetLink($p->id));
    $pagelist .= "<option value=\"{$link}\">{$p->name}</option>\n";
}

$filelist = null;
for ($i=0; $i<count($files); $i++) {
    $file = str_replace(SB_DOWNLOADS_DIR, '', $files[$i]);
    $filelist .= "<option value=\"data/downloads/{$file}\">{$file}</option>\n";
}

?>
<div class="modal_wrapper">
<form id="urlform" method="get" action="javascript:return void(0);">
  <div class="inputdiv">
      <h3 style="margin-bottom: 4px;"><?php __('WYM.LINK.PAGE'); ?></h3>
      <select name="internalpage" id="internalpage">
          <option value=""><?php __('WYM.LINK.INTERNAL_PAGE'); ?></option>
          <?php echo $pagelist; ?>
      </select>
  </div>
  <div class="inputdiv">
      <h3><?php __('WYM.LINK.DOWNLOAD'); ?></h3>
      <select name="filedownload" id="filedownload">
          <option value=""><?php __('WYM.LINK.FILES'); ?></option>
          <?php echo $filelist; ?>
      </select>
  </div>
  <div class="inputdiv">
      <h3><?php __('WYM.LINK.URL'); ?></h3>
      <input type="text" 
             name="externallink" 
             value="" 
             class="inputfield" 
             id="externallink"
             />
  </div>
  <div class="inputdiv">
      <h3><?php __('WYM.LINK.TEXT'); ?></h3>
      <input type="text" 
             name="linktitle" 
             value="" 
             class="inputfield" 
             id="linktitle"
             />
  </div>
  <div class="inputdivlast">
  <input type="button" 
         class="sb-button ui-state-default ui-corner-all"
         name="save" 
         value="Ok" 
         onclick="SBC.restoreSelection(<?php echo $index; ?>); SBC.InsertLink(<?php echo $index; ?>); SBC.hideOverlay(<?php echo $index; ?>);"
         />
  <input type="button" 
         class="sb-button ui-state-default ui-corner-all"
         name="cancel" 
         value="Cancel" 
         onclick="SBC.restoreSelection(<?php echo $index; ?>); SBC.hideOverlay(<?php echo $index; ?>);"
         />
  </div>
</form>
</div>
