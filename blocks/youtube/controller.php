<?php

namespace Concrete\Package\ActiveCookieConsentThirdParty\Block\Youtube;

use Concrete\Block\Youtube\Controller as CoreController;
use Concrete\Package\ActiveCookieConsent\Service\Entity\CookieDisclaimerSetting as CookieSettingSVC;

class Controller extends CoreController
{
    public function registerViewAssets($outputContent = '')
    {
        $this->requireAsset('block/youtube');
    }

    public function view()
    {
        parent::view();
        $siteTree = $this->getCollectionObject()->getSiteTreeObject();
        $cookieSettingSVC = $this->app->make(CookieSettingSVC::class);
        $cookieSetting = is_object($siteTree) ? $cookieSettingSVC->getOrCreateByTree($siteTree) : null;
        $this->set('popupMessage',$cookieSetting->getPopupMessageDisplay());
        $this->set('activeIframe', $this->app['helper/concrete/dashboard']->canRead());
    }

    protected function load()
    {
        parent::load();

        if (is_object($this->record)) {
            $this->record->noCookie = $this->noCookie = true;
            $this->set('noCookie', true);
        }
    }
}
