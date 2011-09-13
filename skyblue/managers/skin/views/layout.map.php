<?php 

/**
* @version      2.0 2008-12-12 19:47:43 $
* @package      SkyBlueCanvas
* @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
* @license      GNU/GPL, see COPYING.txt
* SkyBlueCanvas is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYING.txt for copyright notices and details.
*/

defined('SKYBLUE') or die(basename(__FILE__));

$old_layouts = $this->getVar('old_layouts');
$new_layouts = $this->getVar('new_layouts');
$skin_name   = $this->getVar('skin_name');
?>
<div class="msg-warning" style="margin-top: 0px;">
    <h2>Attention:</h2>
    <p>
    The skin you are trying to activate uses different layout names from your current skin. 
    The layouts in the left-hand column do not exist in the new skin. Use the selectors 
    in the right-hand column to indicate which page layouts from the new skin to use instead.
    </p>
</div>
<form method="post" action="<?php echo SKINNER_URL; ?>&com=skin" id="mgrform">
<input type="hidden" name="name" value="<?php echo $skin_name; ?>" />
<p style="text-align: right;">
    <input type="submit" name="action" value="Cancel" class="button" /> 
    <input type="submit" name="action" value="Update" class="button" />
</p>
<table class="linkstable" cellpadding="0" cellspacing="0">
<tr>
    <th width="50%">Old Layout Name</th>
    <th width="50%">Replace With (New Layout Name)</th>
</tr>
<?php for ($i=0; $i<count($old_layouts); $i++) : ?>
<tr>
    <td>
        <?php echo $old_layouts[$i]; ?>
        <input type="hidden" name="old_layouts[]" value="<?php echo basename($old_layouts[$i]); ?>" />
    </td>
    <td>
        <select name="new_layouts[]">
            <?php for ($x=0; $x<count($new_layouts); $x++) : ?>
            <option value="<?php echo basename($new_layouts[$x]); ?>">
                <?php echo basename($new_layouts[$x]); ?>
            </option>
            <?php endfor; ?>
        </select>
    </td>
</tr>
<?php endfor; ?>
</table>
<p>
    <input type="submit" name="submit" value="<?php __('BTN.SAVE', 'Save'); ?>" onclick="set_action(this, 'save');" class="wymupdate button" /> 
    <input type="submit" name="submit" value="<?php __('BTN.CANCEL', 'Cancel'); ?>" onclick="set_action(this, 'cancel');" class="wymupdate button" />
    <input type="hidden" name="action" value="" id="action" />
</p>
</form>