<?php defined('C5_EXECUTE') or die('Access Denied.');
/**
 * @var array $youtube
 * @var array $gmap
 * @var array $cookieDisclaimer
 * @var Concrete\Core\Form\Service\Form $form
 */
?>

<div class="alert alert-info">
    <?= t("This works only with the Youtube Block and Google Map Block, and it won't work if you add a youtube video or Google maps within an other Block (HTML Block for Eg.)."); ?>
</div>

<div class="row">
    <div class="col-xs-12 col-md-push-2 col-md-8 col-lg-push-3 col-lg-6">
        <table class="table table-primary table-striped table-bordered">
            <thead>
            <tr>
                <th><?= t('Third Party'); ?></th>
                <th style="width: 120px;"><?= t('Enabled ?'); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <span class="label label-primary"><?= t('Youtube'); ?></span>
                </td>
                <td>
                    <label style="display: block;text-align: center;">
                        <?= $form->checkbox('thirdParty[youtube][enabled]', 1, $youtube['enabled']); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label label-primary"><?= t('Google Maps'); ?></span>
                </td>
                <td>
                    <label style="display: block;text-align: center;">
                        <?= $form->checkbox('thirdParty[gmap][enabled]', 1, $gmap['enabled']); ?>
                    </label>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="panel panel-primary">
            <div class="panel-heading"><?= t('Cookie Disclaimer Option'); ?></div>
            <div class="panel-body">
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
            </div>
        </div>
    </div>
</div>
