<?php

namespace Concrete\Package\ActiveCookieConsentThirdParty\Event;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Asset\CssInlineAsset;
use Concrete\Core\Block\Block;
use Concrete\Core\Block\Events\BlockOutput;
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
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'on_block_load' => ['youtubeForceNoCookie'],
            'on_block_output' => ['thirdPartiesOptOut'],
            'on_before_render' => ['registerAssets'],
            'on_page_output' => ['googleMapOptOut'],
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

        if ($this->inlineStyles !== []) {
            $inlineCssAsset = new CssInlineAsset();
            $inlineCssAsset->setAssetURL(implode(PHP_EOL, $this->inlineStyles));
            $view->requireAsset($inlineCssAsset);
        }
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
    public function googleMapOptOut(GenericEvent $event): void
    {
        $event->setArgument('contents', preg_replace(
            '#<script(.*) src="(.*)maps.googleapis.com/maps/api/js#',
            '<script$1 data-src="$2maps.googleapis.com/maps/api/js',
            $event->getArgument('contents')
        ));
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

        $settings = $this->getThirdPartySettings($block->getBlockTypeHandle());
        if ($settings !== null && $settings->isDefaultThumbnailUsed()
            && !empty($thumbnailURL = $this->getThumbnailFromAPI($block))) {
            $this->inlineStyles[$bID] = "div.ccm-page .acc-{$block->getBlockTypeHandle()}.acc-third-party-{$bID}.acc-opt-out { background-image: url('$thumbnailURL'); }";
        }
    }

    protected function getThumbnailFromAPI(Block $block): string
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
        } elseif ($btHandle === 'growthcurve_vimeo_video' && !empty($blockVideoId = $block->getController()->get('vimeoVid'))) {
            $data = @file_get_contents("//vimeo.com/api/v2/video/$blockVideoId.json");
            $data = @json_decode($data, true);
            $videoThumbnail = $data[0]['thumbnail_large'] ?? $data[0]['thumbnail_medium'] ?? $data[0]['thumbnail_small'] ?? '';
        }

        return $videoThumbnail;
    }
}
