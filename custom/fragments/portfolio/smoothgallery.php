<?php defined('SKYBLUE') or die('Bad file request');
    
    global $Core;
    
    $Core->RegisterEvent('OnRenderPage', 'smoothgallery_scripts');
    
    $groups = get_portfolio_groups();
    $group  = $Core->GetVar($_GET, 'cid', isset($groups[0]->id) ? $groups[0]->id : '');
    
    $items = array();
    if (trim($group) != "") {
        $items  = $Core->SelectObjsByKey($data, 'category', $group);
    }
?>
<div id="myGallery">
    <?php foreach ($data as $item) : ?>
    <div class="imageElement">
        <h3><?php echo $item->title; ?></h3>
        <p><?php echo $item->title; ?></p>
        <a href="#" title="open image" class="open"></a>
        <img src="<?php echo $item->artwork; ?>" class="full" />
        <img src="<?php echo $item->thumbnail; ?>" class="thumbnail" />
    </div>
    <?php endforeach; ?>
</div>