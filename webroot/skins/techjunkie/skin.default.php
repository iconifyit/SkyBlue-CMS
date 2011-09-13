<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="[[site.site_lang]]">
    <head>
        <title><?php the_page_title(); ?></title>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" /> 
        <!--#fragment(name=meta&view=dublincore)-->
        [[page.metadata]]
        <!--#fragment(name=head)-->
        <!--#plugin.preloader-->
        [[page.head_elements]]
    </head>
    <body id="[[page.css_id]]" class="[[page.css_class]]">
        <div id="wrap">
            <!--#fragment(name=header)-->
            <div id="menu">
                <!--#fragment(name=menu_tree&view=view&menu=1&style_id=mainmenu&link_current=1)-->
            </div>                    
            <div id="content-wrap">
                <div id="main">
                    <!--#fragment(name=page)-->
                </div>
                <div id="sidebar">
                    <!--#fragment(name=sidebar)-->
                </div>        
            </div>
            <div id="footer-wrap">
                <!--#fragment(name=footer)-->
            </div>
        </div>   
    </body>
</html>