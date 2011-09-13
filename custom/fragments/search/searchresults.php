<?php defined('SKYBLUE') or die('Bad file request'); ?>
<?php if (isset($_GET['cx'])) : ?>
<div id="search-results">
    <div id="cse-search-results"></div>
    <script type="text/javascript">
        var googleSearchIframeName = "cse-search-results";
        var googleSearchFormName   = "cse-search-box";
        var googleSearchFrameWidth = 950;
        var googleSearchDomain     = "www.google.com";
        var googleSearchPath       = "/cse";
        var googleSearhFrameColor  = "#000";
    </script>
    <script type="text/javascript" src="http://www.google.com/afsonline/show_afs_search.js"></script>
</div>
<?php endif; ?>