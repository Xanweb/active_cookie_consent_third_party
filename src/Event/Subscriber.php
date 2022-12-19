<?php

namespace Concrete\Package\ActiveCookieConsentThirdParty\Event;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Asset\CssInlineAsset;
use Concrete\Core\Block\Block;
use Concrete\Core\Block\Events\BlockOutput;
use Concrete\Core\Http\ResponseAssetGroup;
use Concrete\Package\ActiveCookieConsent\Config\CookieDisclaimer;
use Concrete\Package\ActiveCookieConsent\Entity\ThirdPartySetting as ThirdPartySettingEntity;
use Concrete\Package\ActiveCookieConsent\Service\Entity\ThirdPartySetting as ThirdPartySettingSVC;
use Concrete\Package\ActiveCookieConsentThirdParty\Module\Module;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class Subscriber implements EventSubscriberInterface, ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * Inline styles to render for Third Party usage.
     *
     * @var string[]
     */
    private $inlineStyles = [];

    /**
     * @var ThirdPartySettingEntity[]
     */
    private $blocksSettings = [];

    /**
     * @var CssInlineAsset
     */
    private $cssInlineAsset;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        if (!app(CookieDisclaimer::class)->isEnabled()) {
            return [];
        }
        return [
            'on_block_load' => ['youtubeForceNoCookie'],
            'on_block_output' => ['thirdPartiesOptOut'],
            'on_before_render' => ['registerAssets'],
            'on_page_output' => ['onPageOutput', 10],
        ];
    }

    /**
     * @eventName on_block_load
     *
     * @param GenericEvent $event
     */
    public function youtubeForceNoCookie(GenericEvent $event): void
    {
        if ($event->getArgument('btHandle') !== 'youtube') {
            return;
        }

        $record = $event->getArgument('record');
        if (is_object($record)) {
            $record->noCookie = true;

            $event->setArgument('record', $record);
        }
    }

    /**
     * @eventName on_before_render
     *
     * @param GenericEvent $event
     */
    public function registerAssets(GenericEvent $event): void
    {
        $view = $event->getArgument('view');
        $view->requireAsset('acc/third-party');
    }

    /**
     * @eventName on_block_output
     *
     * @param BlockOutput $event
     */
    public function thirdPartiesOptOut(BlockOutput $event): void
    {
        $block = $event->getBlock();
        if ($block === null || !Module::supports($block)) {
            return;
        }

        // Wrap Content in ACC Container
        $event->setContents(
            '<div class="acc-third-party-wrapper acc-' . $block->getBlockTypeHandle() . ' acc-third-party-' . $block->getBlockID() . '">'
            . $event->getContents()
            . '</div>'
        );

        // Prevent iframe from loading content
        $event->setContents(preg_replace(
            '/<iframe(.*) src="(.*)"/',
            '<iframe$1 data-src="$2"',
            $event->getContents()
        ));

        $this->setupCustomizedBackground($block);
    }

    /**
     * @eventName on_page_output
     *
     * @param GenericEvent $event
     */
    public function onPageOutput(GenericEvent $event): void
    {
        $contents = $event->getArgument('contents');
        /* google Map OptOut */
        $contents = preg_replace(
            '#<script(.*) src="(.*)maps.googleapis.com/maps/api/js#',
            '<script$1 data-src="$2maps.googleapis.com/maps/api/js',
            $contents);

        $contents = preg_replace(
            '#<script(.*) src="(.*)www.google.com/recaptcha/api.js(.*)#',
            '<script$1 data-src="$2www.google.com/recaptcha/api.js$3',
            $contents
        );

        $contents = preg_replace(
            '#<div(.*) class="grecaptcha-box(.*)"(.*)>(.*)</div>#',
            '<div class="acc-third-party-wrapper acc-third_party_recaptcha"><div$1 class="grecaptcha-box$2" $3>$4</div></div>',
            $contents
        );
        $contents = preg_replace(
            '#<div(.*) class="g-recaptcha(.*)"(.*)>(.*)</div>#',
            '<div class="acc-third-party-wrapper acc-third_party_recaptcha"><div$1 class="g-recaptcha$2" $3>$4</div></div>',
            $contents
        );

        $contents = preg_replace_callback(
            '#<iframe(.*) src="([\S]*)"(.*)>(.*)</iframe>#',
            function ($matches) {
                if (strpos((string) $matches[2],'youtube.com') !== false || strpos($matches[2], 'youtube-nocookie.com') !== false) {
                    $id = last(explode('/', $matches[2]));
                    if (strpos($id, '?') !== false) {
                        list($id, $pops) = explode('?', $id);
                        if ($id === 'watch') {
                            foreach (explode('&', $pops) as $pop) {
                                if (str_starts_with($pop, 'v=')) {
                                    $id = substr($pop, 2);
                                }
                            }
                        }
                    }

                    $backgroundURL = \URL::to("/xw/acc/background/youtube/{$id}");
                    $this->inlineStyles["youtube-{$id}"] = "div.ccm-page .acc-third_party_youtube.acc-youtube_{$id}.acc-opt-out {background-image: url('{$backgroundURL}'); }";
                    return '<div class="acc-third-party-wrapper acc-third_party_youtube acc-youtube_'.$id.'"><iframe'.$matches[1].' data-src="'.$matches[2].'"'.$matches[3].'>'.$matches[4].'</iframe></div>';
                }

                if (strpos((string) $matches[2],'vimeo.com') !== false) {
                    $id = last(explode('/', $matches[2]));
                    if (strpos($id, '?') !== false) {
                        $id = array_first(explode('?', $id));
                    }

                    $backgroundURL = \URL::to("/xw/acc/background/vimeo/{$id}");
                    $this->inlineStyles["vimeo-{$id}"] = "div.ccm-page .acc-third_party_vimeo.acc-vimeo_{$id}.acc-opt-out {background-image: url('{$backgroundURL}'); }";
                    return '<div class="acc-third-party-wrapper acc-third_party_vimeo acc-vimeo_'.$id.'"><iframe'.$matches[1].' data-src="'.$matches[2].'"'.$matches[3].'>'.$matches[4].'</iframe></div>';
                }

                return $matches[0];
            },
            $contents);

        $contents = preg_replace_callback(
            '#<div(.*) data-third-party="([\S]*)"(.*)>(.*)</div>#',
            function ($matches) {
                return '<div class="acc-third-party-wrapper acc-third_party_' . $matches[2] . '"><div'.$matches[1] . $matches[3].'>'.$matches[4].'</div></div>';
            },
            $contents);

        if (!isset($this->cssInlineAsset)) {
            $this->cssInlineAsset = new CssInlineAsset();
        }

        $this->cssInlineAsset->setAssetURL(implode(PHP_EOL, $this->inlineStyles));

        $contents = str_replace('</head>', (string)$this->cssInlineAsset . ' </head>', $contents);

        $event->setArgument('contents', $contents);
    }

    protected function getThirdPartySettings(string $btHandle): ?ThirdPartySettingEntity
    {
        if (!array_key_exists($btHandle, $this->blocksSettings)) {
            $site = $this->app['site']->getSite();
            $thirdPartySettingSVC = $this->app->make(ThirdPartySettingSVC::class);
            $this->blocksSettings[$btHandle] = is_object($site) ? $thirdPartySettingSVC->getOrCreateByHandleAndSite($btHandle, $site) : null;
        }

        return $this->blocksSettings[$btHandle];
    }

    protected function setupCustomizedBackground(Block $block): void
    {
        // Check if it's already set
        if (isset($this->inlineStyles[$bID = $block->getBlockID()])) {
            return;
        }

        $btHandle = $block->getBlockTypeHandle();
        $settings = $this->getThirdPartySettings($btHandle);
        if ($settings !== null && $settings->isDefaultThumbnailUsed()) {
            if ($btHandle === 'youtube') {
                $videoURL = $block->getController()->get('videoURL');
                $id = last(explode('/', $videoURL));
                if (strpos($id, '?') !== false) {
                    list($id, $pops) = explode('?', $id);
                    if ($id === 'watch') {
                        foreach (explode('&', $pops) as $pop) {
                            if (str_starts_with($pop, 'v=')) {
                                $id = substr($pop, 2);
                            }
                        }
                    }
                }

                $thumbnailURL = \URL::to("/xw/acc/background/youtube/{$id}");
                $this->inlineStyles[$bID] = "div.ccm-page .acc-$btHandle.acc-third-party-$bID.acc-opt-out { background-image: url('$thumbnailURL'); }";
            } elseif (($btHandle === 'growthcurve_vimeo_video' || $btHandle === 'xw_vimeo') && !empty($blockVideoId = $block->getController()->get('vimeoVid'))) {
                $data = @file_get_contents("https://vimeo.com/api/v2/video/$blockVideoId.json");
                $data = @json_decode($data, true);
                if (is_array($data)) {
                    $thumbnailURL = \URL::to("/xw/acc/background/vimeo/{$blockVideoId}");
                    $style = "background-image: url('{$thumbnailURL}'); ";
                    if ($btHandle === 'xw_vimeo') {
                        $style .= (!($block->getController()->get('height') > 0) && isset($data[0]['height'])) ? "height: {$data[0]['height']}px; " : '';
                        $style .= (!($block->getController()->get('width') > 0) && isset($data[0]['width'])) ? "height: {$data[0]['width']}px; " : '';
                    } else {
                        $style .= (!($block->getController()->get('vvHeight') > 0) && isset($data[0]['height'])) ? "height: {$data[0]['height']}px; " : '';
                    }

                    if (!empty($style)) {
                        $this->inlineStyles[$bID] = "div.ccm-page .acc-$btHandle.acc-third-party-$bID.acc-opt-out { $style}";
                    }
                }
            }
        }
    }
}
