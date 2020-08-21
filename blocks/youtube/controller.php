<?php

namespace Concrete\Package\ActiveCookieConsentThirdParty\Block\Youtube;

use Concrete\Block\Youtube\Controller as CoreController;

class Controller extends CoreController
{
    public function registerViewAssets($outputContent = '')
    {
        $this->requireAsset('block/youtube');
    }

    public function view()
    {
        parent::view();

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
