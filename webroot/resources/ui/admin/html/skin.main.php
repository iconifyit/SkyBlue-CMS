<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo Config::get('site_lang', 'en'); ?>">
    <head>
        <title>{page:title}</title>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <link rel="stylesheet" type="text/css" href="resources/ui/admin/css/ssm.master.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo SB_UI_DIR; ?>elements/css/elements.css" />
    </head>
    <body id="admin">
        <div id="container">
            <div id="header">
                <ul id="top-nav">
                    <li><?php echo Filter::get($data, 'logout', '', 0); ?></li>
                    <li><?php echo Filter::get($data, 'inbox', '', 0); ?></li>
                </ul>
            </div>
            <div id ="main">
                <div id="dashboard_main">
                    <ul id="main_dashboard_buttons">
                        <li id="btn-dash-page" class="main-dash-button">
                            <a href="{ADMIN.BASE_PAGE}?section=pages&com=page"><span class="term">[TERM:PAGES]</span></a>
                        </li>
                        <li id="btn-dash-pictures" class="main-dash-button">
                            <a href="{ADMIN.BASE_PAGE}?section=pictures&com=media"><span class="term">[TERM:FILES]</span></a>
                        </li>
                        <li id="btn-dash-collections" class="main-dash-button">
                            <a href="{ADMIN.BASE_PAGE}?section=collections&com=dashboard"><span class="term">[TERM:COLLECTIONS]</span></a>
                        </li>
                        <li id="btn-dash-templates" class="main-dash-button">
                            <a href="{ADMIN.BASE_PAGE}?section=templates&com=dashboard"><span class="term">[TERM:TEMPLATES]</span></a>
                        </li>
                        <li id="btn-dash-settings" class="main-dash-button">
                            <a href="{ADMIN.BASE_PAGE}?section=settings&com=dashboard"><span class="term">[TERM:SETTINGS]</span></a>
                        </li>
                    </ul>
                    <div id="page_info" class="msg-info">
                        <p>[TERM:TOOLTIP.PAGES]</p>
                    </div>
                    <div id="pictures_info" class="msg-info">
                        <p>[TERM:TOOLTIP.MEDIA]</p>
                    </div>
                    <div id="collections_info" class="msg-info">
                        <p>[TERM:TOOLTIP.COLLECTIONS]</p>
                    </div>
                    <div id="templates_info" class="msg-info">
                        <p>[TERM:TOOLTIP.SKINS]</p>
                    </div>
                    <div id="settings_info" class="msg-info">
                        <p>[TERM:TOOLTIP.SETTINGS]</p>
                    </div>
                </div>
                <div id="result">
                    <!--{result}-->
                </div>
                <div id="welcome">
                    <?php echo Filter::get($data, 'body', '', 0); ?>
                </div>
            </div>
            <div id="footer">
                <div id="copyright">{skyblue:name} {skyblue:version}</div>
                <div id="performance"><!--#performance--></div>
                <div style="clear: both;"></div>
            </div>
        </div>
        <script type="text/javascript" src="languages/<?php echo Config::get('site_lang', 'en'); ?>/terms.js"></script>
        <script type="text/javascript" src="<?php echo SB_UI_DIR; ?>js/jquery.js"></script>
        <script type="text/javascript" src="<?php echo SB_UI_DIR; ?>js/jquery.dropshadow.js"></script>
        <script type="text/javascript" src="<?php echo SB_UI_DIR; ?>js/imagehover.js"></script>
        <script type="text/javascript" src="<?php echo SB_UI_DIR; ?>js/scripts.js"></script>
        <!--#html.editor.links-->
        <!--[if IE 6]>
            <link rel="stylesheet" type="text/css" href="resources/ui/admin/css/ie6.css" />
        <![endif]-->
        <!--[if IE 7]>
            <link rel="stylesheet" type="text/css" href="resources/ui/admin/css/ie7.css" />
        <![endif]-->
        <script type="text/javascript">
            $(function() {
                $(".main-dash-button").hover( 
                    function() {
                        $("#" + get_info_id($(this))).show();
                    }, 
                    function() {
                        $("#" + get_info_id($(this))).hide();
                    }
                );
                function get_info_id(el) {
                    var buttonId = $(el).attr("id");
                    return buttonId.replace("btn-dash-", "") + "_info";
                };
            });
        </script>
        <!--analytics-->
    </body>
</html>
<!-- skin.main.html -->