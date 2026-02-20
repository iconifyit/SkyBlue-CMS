<?php defined('SKYBLUE') or die('Bad file request');

global $Core;

$items = array();
if (!count($items)) return;
?>
<?php foreach ($items as $item) : ?>
<item>
    <guid><?php echo $item->title; ?></guid>
    <pubDate><?php echo $item->date; ?></pubDate>
    <title><?php echo $item->title; ?></title>
    <link><?php echo Config::get('site_url'); ?></link>
    <description><![CDATA[<?php echo base64_decode($item->intro); ?>]]></description>
</item>
<?php endforeach; ?>