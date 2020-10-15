<?php defined('C5_EXECUTE') or die('Access Denied.');
/**
 * @var \Concrete\Core\Page\Page
 * @var bool $activeGoogleMap
 */
$c = Page::getCurrentPage();
if ($c->isEditMode()) {
    $loc = Localization::getInstance();
    $loc->pushActiveContext(Localization::CONTEXT_UI); ?>
	<div class="ccm-edit-mode-disabled-item" style="width:<?= $width; ?>; height:<?= $height; ?>">
		<div style="padding: 80px 0 0 0"><?=t('Google Map disabled in edit mode.'); ?></div>
	</div>
    <?php
    $loc->popActiveContext();
} else {
    ?>
	<?php  if (strlen($title) > 0) {
        ?><h3><?=$title; ?></h3><?php
    } ?>
	<div class="googleMapCanvas"
         style="width:<?= $width; ?>; height:<?= $height; ?>"
         data-zoom="<?= $zoom; ?>"
         data-latitude="<?= $latitude; ?>"
         data-longitude="<?= $longitude; ?>"
         data-scrollwheel="<?= (bool) $scrollwheel ? 'true' : 'false'; ?>"
         data-draggable="<?= (bool) $scrollwheel ? 'true' : 'false'; ?>"
         data-active="<?= $activeGoogleMap; ?>"
         data-width="<?= $width; ?>"
         data-height="<?= $height; ?>"
         data-alt=""
         data-button-text="<?= t('Accept Third Party'); ?>"
         data-popup-message='<?=$popupMessage;?>'
    >
    </div>
<?php
} ?>