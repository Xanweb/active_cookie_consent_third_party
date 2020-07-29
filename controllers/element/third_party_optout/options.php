<?php
namespace Concrete\Package\ActiveCookieConsentThirdParty\Controller\Element\ThirdPartyOptout;

use Concrete\Core\Controller\ElementController;
use Concrete\Package\ActiveCookieConsentThirdParty\Module\Module;
use HtmlObject\Input;

class Options extends ElementController
{
    protected $helpers = ['form'];

    /**
     * @var \Concrete\Core\Entity\Site\SiteTree
     */
    protected $siteTree;

    /**
     * Options constructor.
     * @param \Concrete\Core\Entity\Site\SiteTree $siteTree
     */
    public function __construct($siteTree)
    {
        parent::__construct();

        $this->siteTree = $siteTree;
        $this->pkgHandle = Module::pkgHandle();
    }

    public function getElement()
    {
        return 'third_party_optout/options';
    }

    public function view()
    {
        $config = Module::getConfig();
        $this->set('title', $config->get("{$this->siteTree->getSiteTreeID()}.cookieDisclaimer.title", function () {
            return Module::getFileConfig()->get('cookie_disclaimer.third_party.title');
        }));

        $this->set('description', $config->get("{$this->siteTree->getSiteTreeID()}.cookieDisclaimer.description", function () {
            return Module::getFileConfig()->get('cookie_disclaimer.third_party.description');
        }));

        $thirdPartyCheckboxField = new Input('checkbox');
        $thirdPartyCheckboxField->addClass(['ios-toggler','ios-toggler--round-flat','ios-toggler--sm', 'third-party-switcher']);
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
