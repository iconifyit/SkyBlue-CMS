<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo Config::get('site_lang', 'en'); ?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="description" content="Flexy Admin Template" />
        <title><?php __('SBC.ADMIN', 'SkyBlueCanvas Admin'); ?></title>
        <!--skyblue_headers-->
        <!--#fragment(name=editor&view=styles&wrapper=no)-->
        <!--/skyblue_headers-->
    </head>
    <body>
        <?php skyblue_toolbar(); ?>
        <div id="top">
            <?php echo Filter::get($data, 'body', '', 0); ?>
            <div id="footer">
                <!--#fragment(name=console&view=footer)-->
            </div>
        </div>
        <!--#fragment(name=editor&view=scripts&wrapper=no)-->
        <?php get_head_elements(); ?>
    </body>
</html>