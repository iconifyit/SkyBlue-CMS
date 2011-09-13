<?php defined('SKYBLUE') or die('Bad file request');
    
    LinksFragment::init(Filter::get($params, 'name'));
    $items  = LinksFragment::getData();
    $groups = LinksFragment::getGroups(array_map(
        'trim', 
        explode(',', Filter::get($params, 'gid'))
    ));
    $tag     = Filter::get($params, 'tag', 'h2');
    $heading = Filter::get($params, 'heading', __('GLOBAL.LINKS', 'Links', 1));
    $class   = Filter::get($params, 'class', 'links');
?>
<<?php echo $tag; ?> class="<?php echo $class; ?>"><?php echo $heading; ?></<?php echo $tag; ?>>
<?php foreach ($groups as $group) : ?>
    <?php if ($data = LinksFragment::getItems($group, $items)) : ?>
    <h2 class="linksgroup"><?php echo $group->getName(); ?></h2>
    <ul class="links">
        <?php if (count($data)) : ?>
            <?php foreach ($data as $obj) : ?>
                <li><a href="<?php echo $obj->getUrl(); ?>"<?php LinksFragment::getRelationship($obj); ?>><?php echo $obj->getName(); ?></a></li>
            <?php endforeach; ?>
        <?php else: ?>
            <li><?php __('GLOBAL.NO_ITEMS', 'No items to display'); ?></li>
        <?php endif; ?>
    </ul>
    <?php endif; ?>
<?php endforeach; ?>