<?php

namespace Concrete\Package\ActiveCookieConsentThirdParty\Event;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Block\Events\BlockOutput;
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\View\View;
use Concrete\Package\ActiveCookieConsent\Entity\CookieDisclaimerSetting as CookieDisclaimerSettingEntity;
use Concrete\Package\ActiveCookieConsent\Service\Entity\CookieDisclaimerSetting as CookieSettingSVC;
use Concrete\Package\ActiveCookieConsent\Module\Module as ActiveCookieConsentModule;
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
        if ($event->getBlock()->getBlockTypeHandle() !== 'youtube') {
            return;
        }

        $v = View::getInstance();
        $v->requireAsset('block/youtube');

        $event->setContents(str_replace(
            ['<iframe', 'src='],
            ['<iframe data-popup-message="' . $this->getPopupMessageDisplay() . '" data-button-text="' . $this->getButtonText() . '"', 'data-src='],
            $event->getContents()
        ));
    }

    public function vimeoOptOut(BlockOutput $event): void
    {
        if ($event->getBlock()->getBlockTypeHandle() !== 'growthcurve_vimeo_video') {
            return;
        }

        $v = View::getInstance();
        $v->requireAsset('block/gorwthcurve-vimeo-video');

        $event->setContents(str_replace(
            ['<iframe', 'src='],
            ['<iframe data-popup-message="' . $this->getPopupMessageDisplay() . '" data-button-text="' . $this->getButtonText() . '"', 'data-src='],
            $event->getContents()
        ));
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
            '<div data-popup-message="' . $this->getPopupMessageDisplay() . '" data-button-text="' . $this->getButtonText() . '" class="googleMapCanvas',
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
            ? (string) $cookieSetting->getPopupMessageDisplay()
            : ActiveCookieConsentModule::getFileConfig()->get('popup_message', '');

        return h($msgDisplay);
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
}
