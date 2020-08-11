<?php defined('C5_EXECUTE') or die('Access Denied.');

/**
 * @var HtmlObject\Input $thirdPartyCheckboxField
 * @var string $title
 * @var string $description
 */

?>

<div class="card h-100">
    <div class="card-header">
        <label class="ios-toggler-wrapper">
            <strong><?= $title; ?></strong>
            <?= $thirdPartyCheckboxField; ?>
            <span class="ios-toggler-ui"></span>
        </label>
    </div>
    <div class="card-body">
        <p><?= $description; ?></p>
    </div>
</div>
