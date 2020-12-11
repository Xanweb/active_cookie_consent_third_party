<?php

namespace Concrete\Package\ActiveCookieConsentThirdParty\Event;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Asset\CssInlineAsset;
use Concrete\Core\Block\Block;
use Concrete\Core\Block\Events\BlockOutput;
use Concrete\Core\File\File;
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\View\View;
use Concrete\Package\ActiveCookieConsent\Entity\CookieDisclaimerSetting as CookieDisclaimerSettingEntity;
use Concrete\Package\ActiveCookieConsent\Service\Entity\CookieDisclaimerSetting as CookieSettingSVC;
use Concrete\Package\ActiveCookieConsent\Module\Module as ActiveCookieConsentModule;
use Concrete\Package\ActiveCookieConsent\Service\Entity\ThirdPartySetting as ThirdPartySettingSVC;
use Concrete\Package\ActiveCookieConsent\Entity\ThirdPartySetting as ThirdPartySettingEntity;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class Subscriber implements EventSubscriberInterface, ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var CookieSettingSVC
     */
    private $cookieSetting;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        $app = Application::getFacadeApplication();
        if (!$app['helper/concrete/dashboard']->canRead()) {
            return [
                'on_block_load' => ['youtubeForceNoCookie'],
                'on_block_output' => [['youtubeOptOut'], ['vimeoOptOut'], ['gmapOptOut']],
            ];
        }

        return [];
    }

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

    public function youtubeOptOut(BlockOutput $event): void
    {
        $block = $event->getBlock();
        $blockTypeHandle = $block->getBlockTypeHandle();
        if ($blockTypeHandle !== 'youtube') {
            return;
        }


        $v = View::getInstance();
        $v->requireAsset('block/youtube');

        $event->setContents(str_replace(
            ['<iframe', 'src=', '</iframe>'],
            ['<div class="custom-player-wrapper custom-wrapper-' . $block->getBlockID() . '"><iframe data-popup-message="' . $this->getPopupMessageDisplay() . '" data-button-text="' . $this->getButtonText() . '" data-accept-function="' .$this->getAcceptThirdPartyFunction().'"', 'data-src=', '</iframe></div>'],
            $event->getContents()
        ));

        $this->addBlockHeaderStyle($block, $v);
    }

    public function vimeoOptOut(BlockOutput $event): void
    {
        $block = $event->getBlock();
        $blockTypeHandle = $block->getBlockTypeHandle();

        if ($blockTypeHandle !== 'growthcurve_vimeo_video') {
            return;
        }

        $v = View::getInstance();
        $v->requireAsset('block/gorwthcurve-vimeo-video');

        $event->setContents(str_replace(
            ['<iframe', 'src=', '</iframe>'],
            ['<div class="custom-player-wrapper custom-wrapper-' . $block->getBlockID() . '"><iframe data-popup-message="' . $this->getPopupMessageDisplay() . '" data-button-text="' . $this->getButtonText() . '" data-accept-function="' .$this->getAcceptThirdPartyFunction().'"', 'data-src=', '</iframe></div>'],
            $event->getContents()
        ));
        $this->addBlockHeaderStyle($block, $v);

    }

    public function gmapOptOut(BlockOutput $event): void
    {
        if ($event->getBlock()->getBlockTypeHandle() !== 'google_map') {
            return;
        }

        $v = View::getInstance();
        $v->requireAsset('block/google-map');

        $event->setContents(str_replace(
            '<div class="googleMapCanvas',
            '<div data-popup-message="' . $this->getPopupMessageDisplay() . '" data-button-text="' . $this->getButtonText() . '" data-accept-function="' .$this->getAcceptThirdPartyFunction().'" class="googleMapCanvas',
            $event->getContents()
        ));
    }

    protected function getButtonText(): string
    {
        return t('Please accept third party cookies');
    }

    protected function getPopupMessageDisplay(): string
    {
        $cookieSetting = $this->getCookieSettings();

        $msgDisplay = ($cookieSetting !== null)
            ? (string)$cookieSetting->getPopupMessageDisplay()
            : ActiveCookieConsentModule::getFileConfig()->get('popup_message', '');

        return h($msgDisplay);
    }

    protected function getAcceptThirdPartyFunction(): string
    {
        $cookieSetting = $this->getCookieSettings();

        $btnFunction = ($cookieSetting !== null)
            ? $cookieSetting->getBtnTPAcceptFunction()
            : 'accept_third_party';

        return $btnFunction;
    }

    protected function getCookieSettings(): ?CookieDisclaimerSettingEntity
    {
        if (!$this->cookieSetting) {
            $c = Page::getCurrentPage();
            if ($c !== null) {
                $siteTree = $c->getSiteTreeObject();
            } else {
                $siteTree = $this->app['site']->getSite()->getSiteTreeObject();
            }

            $cookieSettingSVC = $this->app->make(CookieSettingSVC::class);
            $this->cookieSetting = is_object($siteTree) ? $cookieSettingSVC->getOrCreateByTree($siteTree) : null;
        }

        return $this->cookieSetting;
    }

    protected function getThirdPartySettings($blockTypeHandle): ?ThirdPartySettingEntity
    {
        $c = Page::getCurrentPage();
        if ($c !== null) {
            $site = $c->getSite();
        } else {
            $site = $this->app['site']->getSite();
        }

        $thirdPartySettingSVC = $this->app->make(ThirdPartySettingSVC::class);
        $thirdPartySetting = is_object($site) ? $thirdPartySettingSVC->getOrCreateByHandleAndSite($blockTypeHandle, $site) : null;

        return $thirdPartySetting;
    }

    protected function addBlockHeaderStyle(Block $block, View $v)
    {
        $blockTypeHandle = $block->getBlockTypeHandle();
        $blockID = $block->getBlockID();
        $settings = $this->getThirdPartySettings($blockTypeHandle);
        if ($settings->isDefaultThumbnailUsed()) {
            $thumbnailURL = $this->getThumbnailFromAPI($block);
        } else {
            $thID = $settings->getCustomThumbnailfID();
            $thumbnailURL = ($thID != 0) ? File::getRelativePathFromID($settings->getCustomThumbnailfID()) : '';
        }

        $css = ".custom-wrapper-{$blockID} iframe {background-image: url('$thumbnailURL'); }";
        $inlineCssAsset = new CssInlineAsset();
        $inlineCssAsset->setAssetURL($css);
        $v->requireAsset($inlineCssAsset);

    }

    protected function getThumbnailFromAPI(Block $block)
    {
        $blockTypeHandle = $block->getBlockTypeHandle();
        $videoThumbnail = '';
        if ($blockTypeHandle == 'youtube') {
            $url = parse_url($block->getController()->videoURL);
            $pathParts = explode('/', rtrim($url['path'], '/'));
            parse_str($url['query'], $params);
            $blockVideoId = end($pathParts);

            if (isset($url['query'])) {
                parse_str($url['query'], $query);

                if (isset($query['list'])) {
                    $blockVideoId = '';
                } else {
                    $blockVideoId = isset($query['v']) ? $query['v'] : $blockVideoId;
                    $blockVideoId = strtok($blockVideoId, '?');
                }
            }
            if ($blockVideoId != '') {
                $videoThumbnail = "https://img.youtube.com/vi/{$blockVideoId}/maxresdefault.jpg";
            }
        } else if ($blockTypeHandle == 'growthcurve_vimeo_video') {
            $blockVideoId = $block->getController()->get('vimeoVid');
            if ($blockVideoId != '' && $blockVideoId != 0) {
                $data = file_get_contents("http://vimeo.com/api/v2/video/$blockVideoId.json");
                $data = json_decode($data);
                $videoThumbnail = $data[0]->thumbnail_large;
            }
        }

        return $videoThumbnail;
    }

}
