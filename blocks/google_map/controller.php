<?php

namespace Concrete\Package\ActiveCookieConsentThirdParty\Block\GoogleMap;

use Concrete\Block\GoogleMap\Controller as CoreController;
use Concrete\Package\ActiveCookieConsent\Service\Entity\CookieDisclaimerSetting as CookieSettingSVC;

class Controller extends CoreController
{
    public function registerViewAssets($outputContent = '')
    {
        parent::registerViewAssets($outputContent);

        $this->requireAsset('block/google-map');
    }

    public function view()
    {
        $c =  $this->getCollectionObject();
        $siteTree = $c->getSiteTreeObject();
        $cookieSettingSVC = $this->app->make(CookieSettingSVC::class);
        $cookieSetting = is_object($siteTree) ? $cookieSettingSVC->getOrCreateByTree($siteTree) : null;
        $this->set('popupMessage', $cookieSetting->getPopupMessageDisplay());
        $this->set('c', $c);
        if ($this->app['helper/concrete/dashboard']->canRead()) {
            $this->set('activeGoogleMap', true);
        } else {
            $this->set('activeGoogleMap', false);
        }
    }
}
