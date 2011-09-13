<?php Utils::httpHeaderXml(); ?>
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">
    <?php $Pages = $this->getData(); ?>
    <?php foreach ($Pages as $Page) : ?>
        <?php if ($Page->getIs_error_page()) continue; ?>
        <url>
            <loc><?php echo Config::get('site_url') . $Page->getPermalink(); ?></loc>
            <lastmod><?php echo $Page->getModified(); ?></lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.6</priority>
        </url>
    <?php endforeach; ?>
</urlset>