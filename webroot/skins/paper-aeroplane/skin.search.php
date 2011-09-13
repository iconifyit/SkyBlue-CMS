<!--#xfragment(name=segments&view=header)-->
<?php fragment(array('name' => 'segments', 'view' => 'header')); ?>

    <body class="blog-page noscript" id="top">  
        <div class="container_12" id="top_container">
            <a href="[[site.url]]" id="logo" class="grid_3"><span class="hide">Home</span></a>
            <?php 
                fragment(array(
                    'name' => 'menu', 
                    'view' => 'view3', 
                    'menu_id' => '1'
                )); 
            ?>
            <div class="grid_1">
                <a href="search.html" id="search-button"><span class="hide">Search</span></a>
                <a href="[[site.url]]rss" id="rss-button"><span class="hide">RSS</span></a>
            </div>
            <div class="clear"></div>
        </div>
        <!--/#top_container-->

        <div class="container_12" id="main-content">

            <div class="grid_12 no_margin_left">
                
                <!--blog-intro-->
                <div class="grid_8 blog-intro">
                    <div class="article-header">
                        <h2 class="cufon"><a href="">[[page.title]]</a></h2>
                        <h3 class="cufon">[[page.modified(m.d.Y)]] : <em>By [[page.author]]</em></h3>
                    </div>
                    <div class="clear"></div>
                    <?php fragment(array('name' => 'page')); ?>
                    <?php fragment(array('name' => 'search', 'view' => 'searchresults')); ?>
                </div>
                <div class="clear"></div>
                <!--/blog-intro-->
                
            </div><!--/right-column-->
            
        </div><!--/main-content-->
        
<?php fragment(array('name' => 'segments', 'view' => 'footer')); ?>