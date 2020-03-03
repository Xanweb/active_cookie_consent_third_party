<?php
namespace Concrete\Package\ActiveCookieConsentThirdParty\Controller\Element\Optout;

use Concrete\Core\Controller\ElementController;
use Concrete\Package\ActiveCookieConsentThirdParty\Module\Module;
use HtmlObject\Input;

class Options extends ElementController
{
    protected $helpers = ['form'];

    public function __construct()
    {
        parent::__construct();

        $this->pkgHandle = Module::pkgHandle();
    }

    public function getElement()
    {
        return 'optout/options';
    }

    public function view()
    {
        $site = $this->app->make('site')->getSite();

        $config = Module::getConfig();
        $this->set('youtubeEnabled', $config->get("{$site->getSiteHandle()}.youtube.enabled", false));
        $this->set('gmapEnabled', $config->get("{$site->getSiteHandle()}.gmap.enabled", false));
        $this->set('title', $config->get("{$site->getSiteHandle()}.cookieDisclaimer.title", t('Third Party')));
        $this->set('description', $config->get("{$site->getSiteHandle()}.cookieDisclaimer.description", ''));

        $thirdPartyCheckboxField = new Input('checkbox');
        $thirdPartyCheckboxField->addClass(['custom-control-input', 'third-party-switcher']);
        if ($this->app['helper/concrete/dashboard']->canRead()) {
            $thirdPartyCheckboxField->addClass('launch-tooltip disabled');
            $thirdPartyCheckboxField->setAttributes([
                'data-toggle' => 'tooltip',
                'data-placement' => 'bottom',
                'data-original-title' => t('Third Party can be enabled only for non admins.'),
                'onClick' => 'return false;',
            ]);

            $this->requireAsset('javascript', 'bootstrap/tooltip');
            $this->requireAsset('css', 'bootstrap/tooltip');
        }

        $this->set('thirdPartyCheckboxField', $thirdPartyCheckboxField);
    }
}
