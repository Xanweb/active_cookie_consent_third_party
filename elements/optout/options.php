<?php defined('C5_EXECUTE') or die('Access Denied.');
 /**
  * @var HtmlObject\Input $thirdPartyCheckboxField
  * @var bool $youtubeEnabled
  * @var bool $gmapEnabled
  */
 if (!$youtubeEnabled && !$gmapEnabled) {
     return;
 }
?>

<div class="card h-100">
    <div class="card-header">
        <div class="checkbox">
            <label><?= $thirdPartyCheckboxField; ?><strong><?= $title; ?></strong></label>
        </div>
    </div>
    <div class="card-body">
        <p><?= $description; ?></p>
        <ul>
            <?php if ($youtubeEnabled): ?>
                <li><?= t('YouTube Video'); ?></li>
            <?php endif; ?>
            <?php if ($gmapEnabled): ?>
                <li><?= t('Google Map'); ?></li>
            <?php endif; ?>
        </ul
    </div>
</div>
