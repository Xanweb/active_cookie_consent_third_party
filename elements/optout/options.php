<?php defined('C5_EXECUTE') or die('Access Denied.');
 /**
  * @var HtmlObject\Input $thirdPartyCheckboxField
  */
?>

<div class="card h-100">
    <div class="card-header">
        <div class="checkbox">
            <label><?= $thirdPartyCheckboxField; ?><strong><?= $title; ?></strong></label>
        </div>
    </div>
    <div class="card-body">
        <p><?= $description; ?></p>
    </div>
</div>
