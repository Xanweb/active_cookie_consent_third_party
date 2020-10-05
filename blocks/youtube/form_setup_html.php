<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>
<?php
if (empty($vWidth)) {
    $vWidth = 640;
}
if (empty($vHeight)) {
    $vHeight = 360;
}

if (empty($sizing)) {
    $sizing = '16:9';
}
?>

<?php
echo Core::make('helper/concrete/ui')->tabs([
    ['video', t('Video'), true],
    ['settings', t('Settings')],
]);
?>

<div class="ccm-tab-content" id="ccm-tab-content-video">
    <div class="form-group">
        <label class="control-label"><?php echo t('YouTube URL'); ?></label>
        <?php echo $form->text('videoURL', $videoURL ?? '', ['required' => 'required']); ?>
    </div>
    <div class="form-group">
        <label class="control-label"><?=t('Size'); ?></label>
        <div class="radio">
            <label>
                <?php echo $form->radio('sizing', '16:9', $sizing); ?>
                <?php echo t('16:9 (widescreen)'); ?>
            </label>
        </div>
        <div class="radio">
            <label>
                <?php echo $form->radio('sizing', '4:3', $sizing); ?>
                <?php echo t('4:3'); ?>
            </label>
        </div>
        <div class="radio">
            <label>
                <?php echo $form->radio('sizing', 'fixed', $sizing); ?>
                <?php echo t('Fixed Size (non-responsive)'); ?>
            </label>
        </div>
    </div>
    <div id="fixedsizes" class="<?php echo $sizing === 'fixed' ? '' : 'hidden'; ?>">
        <div class="form-group">
            <label class="control-label"><?php echo t('Width'); ?></label>
            <div class="input-group">
                <input type="text" class="form-control" id="YouTubeVideoWidth" name="vWidth" value="<?php echo $vWidth; ?>" />
                <div class="input-group-addon"><?php echo t('px'); ?></div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label"><?php echo t('Height'); ?></label>
            <div class="input-group">
                <input type="text" class="form-control" id="YouTubeVideoHeight" name="vHeight" value="<?php echo $vHeight; ?>" />
                <div class="input-group-addon"><?php echo t('px'); ?></div>
            </div>
        </div>
    </div>
</div>
<div class="ccm-tab-content" id="ccm-tab-content-settings">
    <fieldset>
        <legend><?php echo t('Appearance'); ?></legend>
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <?php echo $form->checkbox('showinfo', 1, ($showinfo ?? true)); ?>
                            <?php echo t('Show video information'); ?>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <?php echo $form->checkbox('controls', 1, ($controls ?? true)); ?>
                            <?php echo t('Show controls'); ?>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <?php
                            $disabledattr = [];
                            if (isset($color) && $color === 'white') {
                                $disabledattr['disabled'] = 'disabled';
                            }
                            echo $form->checkbox('modestbranding', 1, $modestbranding ?? true, $disabledattr); ?>
                            <?php echo t('Hide YouTube Logo'); ?>
                        </label>
                    </div>
                </div>
            </div>

            <div class="col-xs-6">
                <div class="form-group controls-only <?php echo isset($controls) && $controls == 0 ? 'hidden' : ''; ?>">
                    <?php  echo $form->label('color', t('Progress Bar Color')); ?>
                    <?php  echo $form->select('color', ['red' => t('Red'), 'white' => t('White')], $color ?? null); ?>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend><?php echo t('Playback Options'); ?></legend>
        <div class="form-group">
            <div class="checkbox">
                <label>
                    <?php echo $form->checkbox('rel', 1, !empty($rel)); ?>
                    <?php echo t('Show related videos when playback ends'); ?>
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <?php echo $form->checkbox('iv_load_policy', 1, isset($iv_load_policy) && $iv_load_policy === 3); ?>
                    <?php echo t('Hide annotations by default'); ?>
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <?php echo $form->checkbox('autoplay', 1, !empty($autoplay)); ?>
                    <?php echo t('Automatically play'); ?>
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <?php echo $form->checkbox('loopEnd', 1, !empty($loopEnd)); ?>
                    <?php echo t('Loop video'); ?>
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <?php echo $form->checkbox('showCaptions', 1, !empty($showCaptions)); ?>
                    <?php echo t('Show captions'); ?>
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <?php echo $form->checkbox('startTimeEnabled', 1, !empty($startTimeEnabled)); ?>
                    <?php echo t('Start video at:'); ?>
                </label>
            </div>
            <div class="form-group">
                <?php echo $form->text('startTime', $startTime ?? null); ?>
            </div>

        </div>
    </fieldset>
</div>

<script>
    $(document).ready(function() {
        $('#sizing1, #sizing2, #sizing3').change(function(){
            if ($('#sizing3').is(':checked')) {
                $('#fixedsizes').removeClass('hidden');
            } else {
                $('#fixedsizes').addClass('hidden');
            }
        });

        $('#controls').change(function(){
            if ($('#controls').is(':checked')) {
                $('.controls-only').removeClass('hidden');
            } else {
                $('.controls-only').addClass('hidden');
            }
        });

        $('#color').change(function(){
            if ($(this).val() === 'white') {
                $('#modestbranding').prop('disabled','disabled').prop('checked',false);
            } else {
                $('#modestbranding').removeProp('disabled');
            }
        });

        $('#ccm-form-submit-button').click(function(){
            if (!$('#videoURL').val()) {
                $('[data-tab="video"').click();
            }
        });
    });
</script>
