<?php

namespace Concrete\Package\ActiveCookieConsentThirdParty\Event;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Asset\CssInlineAsset;
use Concrete\Core\Block\Block;
use Concrete\Core\Block\Events\BlockOutput;
use Concrete\Core\Http\ResponseAssetGroup;
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
        return [
            'on_block_load' => ['youtubeForceNoCookie'],
            'on_block_output' => ['thirdPartiesOptOut'],
            'on_before_render' => ['registerAssets'],
            'on_page_output' => ['onPageOutput'],
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
                $thumbnailURL = $this->getYoutubeThumbnailFromAPI($block);
                if (!empty($thumbnailURL)) {
                    $this->inlineStyles[$bID] = "div.ccm-page .acc-{$btHandle}.acc-third-party-{$bID}.acc-opt-out { background-image: url('{$thumbnailURL}'); }";
                }
            } elseif ($btHandle === 'growthcurve_vimeo_video' && !empty($blockVideoId = $block->getController()->get('vimeoVid'))) {
                $data = @file_get_contents("https://vimeo.com/api/v2/video/$blockVideoId.json");
                $data = @json_decode($data, true);
                if (is_array($data)) {
                    $thumbnailURL = $data[0]['thumbnail_large'] ?? $data[0]['thumbnail_medium'] ?? $data[0]['thumbnail_small'] ?? '';
                    $style = !empty($thumbnailURL) ? "background-image: url('{$thumbnailURL}'); " : '';
                    $style .= (!($block->getController()->get('vvHeight') > 0) && isset($data[0]['height'])) ? "height: {$data[0]['height']}px; " : '';
                    if (!empty($style)) {
                        $this->inlineStyles[$bID] = "div.ccm-page .acc-{$btHandle}.acc-third-party-{$bID}.acc-opt-out { {$style}}";
                    }
                }
            }

            if ($this->cssInlineAsset === null) {
                $r = ResponseAssetGroup::get();
                /** @noinspection PhpUnhandledExceptionInspection */
                $r->requireAsset($this->cssInlineAsset = new CssInlineAsset());
            }

            $this->cssInlineAsset->setAssetURL(implode(PHP_EOL, $this->inlineStyles));
        }
    }

    protected function getYoutubeThumbnailFromAPI(Block $block): string
    {
        $btHandle = $block->getBlockTypeHandle();
        $videoThumbnail = '';
        if ($btHandle === 'youtube' && !empty($videoURL = $block->getController()->videoURL)) {
            $url = parse_url($videoURL);
            $pathParts = explode('/', rtrim($url['path'], '/'));
            parse_str($url['query'], $params);
            $videoID = end($pathParts);

            if (isset($url['query'])) {
                parse_str($url['query'], $query);

                if (isset($query['list'])) {
                    $videoID = '';
                } else {
                    $videoID = $query['v'] ?? $videoID;
                    $videoID = strtok($videoID, '?');
                }
            }

            if (!empty($videoID)) {
                $videoThumbnail = "//img.youtube.com/vi/{$videoID}/hqdefault.jpg";
            }
        }

        return $videoThumbnail;
    }
}
