<?php defined('SKYBLUE') or die('Bad file request');

/**
 * @version      2.0 2009-06-20 21:41:00 $
 * @package      SkyBlue CMS
 * @copyright    Copyright (C) 2005 - 2024 Scott Edwin Lewis. All rights reserved.
 * @license      GNU/GPL, see COPYING.txt
 * SkyBlue CMS is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 *
 * Updated to use Bootstrap 5 styling
 */

$data = $this->getData();

$pageCount = $this->getVar('pageCount');
$pageNum   = $this->getVar('pageNum');

$ActiveSkin = $this->getVar('activeSkin');
$ActiveSkin->thumbnail = ACTIVE_SKIN_DIR . "images/{$ActiveSkin->getName()}.jpg";
$data = Utils::deleteObject($data, $ActiveSkin->getId());

?>
<h1 class="h3 mb-3">
    <a href="admin.php?com=console" class="text-muted text-decoration-none"><?php __('COM.CONSOLE', 'Dashboard'); ?></a>
    <span class="text-muted">/</span>
    <?php __('COM.SKINS', 'Skins'); ?>
</h1>

<?php echo HtmlUtils::formatMessage($this->getMessage()); ?>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0"><?php __('SKIN.CURRENTLY_ACTIVE', 'Active Skin'); ?></h5>
    </div>
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-auto">
                <?php if (file_exists($ActiveSkin->thumbnail)) : ?>
                <img src="<?php echo $ActiveSkin->thumbnail; ?>"
                     class="img-thumbnail"
                     style="max-width: 200px;"
                     alt="<?php echo $ActiveSkin->getName(); ?>" />
                <?php endif; ?>
            </div>
            <div class="col">
                <h4><?php echo $ActiveSkin->getName(); ?></h4>
                <p class="text-muted mb-0"><?php __('SKIN.CURRENTLY_ACTIVE_DESC', 'This is the currently active skin for your site.'); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0"><?php __('SKIN.AVAILABLE', 'Available Skins'); ?></h5>
    </div>
    <div class="card-body">
        <?php if (!count($data)) : ?>
            <p class="text-muted"><?php __('GLOBAL.NO_ITEMS', 'No other skins available.'); ?></p>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th><?php __('GLOBAL.NAME', 'Name'); ?></th>
                            <th width="125"><?php __('GLOBAL.TASKS', 'Tasks'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $item) : ?>
                        <?php
                            $imageName = strtolower($item->getName());
                            $imageFilePath = SB_SKINS_DIR . "{$imageName}/images/{$imageName}.jpg";

                            $hasPreview = file_exists($imageFilePath);
                        ?>
                        <tr>
                            <td>
                                <?php if ($hasPreview) : ?>
                                    <span class="tooltip image_preview" title="<?php echo $imageFilePath; ?>"><?php echo $item->getName(); ?></span>
                                <?php else : ?>
                                    <?php echo $item->getName(); ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($item->getPublished() != 1) : ?>
                                    <a href="admin.php?com=skin&action=publish&id=<?php echo $item->getId(); ?>" class="btn btn-sm btn-outline-primary">
                                        <i data-feather="check-circle"></i> <?php __('BTN.ACTIVATE', 'Activate'); ?>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($pageCount > 1) : ?>
            <nav aria-label="Skin pagination">
                <ul class="pagination justify-content-end mb-0">
                    <?php for ($n = 1; $n <= $pageCount; $n++) : ?>
                        <li class="page-item <?php echo $pageNum == $n ? 'active' : ''; ?>">
                            <a class="page-link" href="admin.php?com=skin&action=list&pageNum=<?php echo $n; ?>"><?php echo $n; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
