<?php

require_once('wym.base.php');

add_terms_file('configuration.ini');

$index = Filter::get($_GET, 'index', '0');

?>
<form id="urlform" method="get" action="javascript:return void(0);">
  <p><?php __('WYM.SITEVARS.ABOUT'); ?></p>
  <h2><?php __('WYM.SITEVARS.EXAMPLE'); ?></h2>
  <p><?php __('WYM.SITEVARS.EXPLAIN'); ?></p>
  <div class="inputdiv">
      <h3 style="margin-bottom: 4px;"><?php __('WYM.SITEVARS.SELECT'); ?></h3>
      <select name="sitevars" id="sitevars">
            <option value=""><?php __('WYM.SITEVARS.VARS'); ?></option>
            <option value="site.name"><?php __('CONFIG.SITE_NAME'); ?></option>
            <option value="site.url"><?php __('CONFIG.URL'); ?></option>
            <option value="site.map"><?php __('WYM.SITEVARS.SITEMAP'); ?></option>
            
            <option value="site.contact_name"><?php __('CONFIG.CONTACT_NAME'); ?></option>
            <option value="site.contact_title"><?php __('CONFIG.CONTACT_TITLE'); ?></option>
            <option value="site.contact_address"><?php __('WYM.SITEVARS.CONTACT_STREET'); ?></option>
            <option value="site.contact_city"><?php __('WYM.SITEVARS.CONTACT_CITY'); ?></option>
            <option value="site.contact_state"><?php __('WYM.SITEVARS.CONTACT_STATE'); ?></option>
            <option value="site.contact_zip"><?php __('WYM.SITEVARS.CONTACT_POSTAL'); ?></option>
            <option value="site.contact_email"><?php __('WYM.SITEVARS.CONTACT_EMAIL'); ?></option>
            <option value="site.contact_phone"><?php __('WYM.SITEVARS.CONTACT_PHONE'); ?></option>
            <option value="site.contact_fax"><?php __('WYM.SITEVARS.CONTACT_FAX'); ?></option>
            
            <option value="page.id"><?php __('WYM.SITEVARS.PAGE.ID'); ?></option>
            <option value="page.title"><?php __('WYM.SITEVARS.PAGE.TITLE'); ?></option>
            <option value="page.url"><?php __('WYM.SITEVARS.PAGE.URL'); ?></option>
            <option value="page.author"><?php __('WYM.SITEVARS.PAGE.AUTHOR', "Page Author"); ?></option>
            <option value="page.author_fullname"><?php __('WYM.SITEVARS.PAGE.AUTHOR_FULLNAME', "Page Author's Full Name"); ?></option>
            <option value="page.route"><?php __('WYM.SITEVARS.PAGE.ROUTE', "Page Route"); ?></option>
            <option value="page.modified('F d, Y h:i A')"><?php __('WYM.SITEVARS.PAGE.MODIFIED'); ?></option>
            
            <option value="page.parent.id"><?php __('WYM.SITEVARS.PAGE.PARENT.ID'); ?></option>
            <option value="page.parent.title"><?php __('WYM.SITEVARS.PAGE.PARENT.TITLE'); ?></option>
            <option value="page.link(parent)"><?php __('WYM.SITEVARS.PAGE.PARENT.LINK'); ?></option>
            
            <option value="page.link(children)"><?php __('WYM.SITEVARS.PAGE.CHILDREN'); ?></option>
            <option value="page.link(first)"><?php __('WYM.SITEVARS.PAGE.LINK.FIRST'); ?></option>
            <option value="page.link(last)"><?php __('WYM.SITEVARS.PAGE.LINK.LAST'); ?></option>
            <option value="page.link(next)"><?php __('WYM.SITEVARS.PAGE.LINK.NEXT'); ?></option>
            <option value="page.link(previous)"><?php __('WYM.SITEVARS.PAGE.LINK.PREV'); ?></option>
      </select>
  </div>
  <div class="inputdivlast">
  <input type="button" 
         class="sb-button ui-state-default ui-corner-all"
         name="save" 
         value="Ok" 
         onclick="SBC.InsertSiteVar(<?php echo $index; ?>); SBC.hideOverlay(<?php echo $index; ?>);"
         />
  <input type="button" 
         class="sb-button ui-state-default ui-corner-all"
         name="cancel" 
         value="Cancel" 
         onclick="SBC.hideOverlay(<?php echo $index; ?>);"
         />
  </div>
</form>