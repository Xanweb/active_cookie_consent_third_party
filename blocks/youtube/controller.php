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
}
