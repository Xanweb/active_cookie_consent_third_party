<?php defined('C5_EXECUTE') or die('Access Denied.');
/**
 * @var array $youtube
 * @var array $gmap
 * @var array $cookieDisclaimer
 * @var Concrete\Core\Form\Service\Form $form
 */
?>
<i class="fa fa-info-circle launch-tooltip pull-right text-warning" title=""
   data-original-title="<?= t("This works only with the Youtube Block and Google Map Block, and it won't work if you add a youtube video or Google maps within an other Block (HTML Block for Eg.)."); ?>"></i>
<div class="form-group">
    <?= $form->label('thirdParty[cookieDisclaimer][title]', t('Title')); ?>
    <?= $form->text('thirdParty[cookieDisclaimer][title]', $cookieDisclaimer['title']); ?>
</div>
<div class="form-group">
    <?= $form->label('thirdParty[cookieDisclaimer][description]', t('Description')); ?>
    <?= $form->textarea('thirdParty[cookieDisclaimer][description]', $cookieDisclaimer['description'], [
        'rows' => 7, 'style' => 'min-width:100%;max-width:100%;',
    ]); ?>
</div>
