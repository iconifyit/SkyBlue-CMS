<?php defined('SKYBLUE') or die('Bad file request');

/**
* @version      2.0 2009-06-20 21:41:00 $
* @package      SkyBlueCanvas
* @copyright    Copyright (C) 2005 - 2010 Scott Edwin Lewis. All rights reserved.
* @license      GNU/GPL, see COPYING.txt
* SkyBlueCanvas is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYING.txt for copyright notices and details.
*/

/**
* @author Scott Lewis
* @date   June 20, 2009
*/

$Error = $this->getData();

?>
<script type="text/javascript">
$(function() {
    $(".toggler").bind("click", function(e) {
        var id = $(this).attr("id");
        var pre = id.replace("toggler", "pre");
        $("#"+pre).toggle();
        $(this).text(
            $(this).text() == "<?php __('ERROR.SHOW', 'Inspect Item'); ?>" ? "<?php __('ERROR.HIDE', 'Hide Item'); ?>" : "<?php __('ERROR.SHOW', 'Inspect Item'); ?>"
        );
    });
});
</script>
<div class="jquery_tab">
    <div class="content">
        <h2><?php echo $Error->getLevel(); ?> (<?php __('ERROR.NUMBER', 'Error Number'); ?> <?php echo $Error->getNumber(); ?>)</h2>
        <table class="table_liquid">
            <tr>
                <th width="100"><?php __('ERROR.MESSAGE', 'Message'); ?></th>
                <td><?php echo $Error->getMessage();?></td>
            </tr>
             <tr>
                <th><?php __('ERROR.FILE', 'File'); ?></th>
                <td><?php echo basename($Error->getFile());?></td>
            </tr>
             <tr>
                <th><?php __('ERROR.LINE', 'Line'); ?></th>
                <td><?php echo $Error->getLine();?></td>
            </tr>
        </table>
        
        <h3><?php __('ERROR.BACKTRACE', 'Backtrace'); ?></h3>
        
        <?php $backtrace = $Error->getBacktrace(); ?>
        <?php $count = count($backtrace); ?>
        <ol class="backtrace-list">
            <?php for ($i=0; $i<$count; $i++) : ?>
            <?php 
                $class = Filter::get($backtrace[$i], 'class');
                $function = Filter::get($backtrace[$i], 'function');
                $file = Filter::get($backtrace[$i], 'file');
                $object = Filter::get($backtrace[$i], 'object');
                if (isset($backtrace[$i]['file'])) {
                    $backtrace[$i]['file'] = basename($backtrace[$i]['file']);
                }
            ?>
            <li>
                <table class="backtrace_table">
                    <tr>
                        <th width="100"><?php __('ERROR.CLASS', 'Class'); ?></th>
                        <td><?php echo empty($class) ? __('ERROR.NONE', 'None', 1) : $class ; ?></td>
                    </tr>
                    <tr>
                        <th width="100"><?php __('ERROR.FUNCTION', 'Function'); ?></th>
                        <td><?php echo $function ; ?></td>
                    </tr>
                    <tr>
                        <th width="100"><?php __('ERROR.FILE', 'File'); ?></th>
                        <td><?php echo basename($file); ?></td>
                    </tr>
                    <tr>
                        <th width="100"><?php __('ERROR.RAW', 'Raw Output'); ?></th>
                        <td class="object">
                            <a href="javascript:void(0);" class="button toggler" id="toggler-<?php echo $i; ?>"><?php __('ERROR.SHOW', 'Inspect Item'); ?></a>
                            <pre id="pre-<?php echo $i; ?>"><?php print_r($backtrace[$i]); ?></pre>
                        </td>
                    </tr>
                </table>
            </li>
            <?php endfor; ?>
        </ol>
    </div>
</div>