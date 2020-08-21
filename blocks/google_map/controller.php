<?php

namespace Concrete\Package\ActiveCookieConsentThirdParty\Block\GoogleMap;

use Concrete\Block\GoogleMap\Controller as CoreController;

class Controller extends CoreController
{
    public function registerViewAssets($outputContent = '')
    {
        parent::registerViewAssets($outputContent);

        $this->requireAsset('block/google-map');
    }

    public function view()
    {
        $this->set('c', $this->getCollectionObject());
        if ($this->app['helper/concrete/dashboard']->canRead()) {
            $this->set('activeGoogleMap', true);
        } else {
            $this->set('activeGoogleMap', false);
        }
    }
}
