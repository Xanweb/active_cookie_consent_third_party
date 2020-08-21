<?php

namespace Concrete\Package\ActiveCookieConsentThirdParty\Controller\Element\Dashboard;

use Concrete\Core\Controller\ElementController;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Package\ActiveCookieConsent\Dashboard\Element\SavableInterface;
use Concrete\Package\ActiveCookieConsentThirdParty\Module\Module;

class Options extends ElementController implements SavableInterface
{
    protected $helpers = ['form'];

    /**
     * @var \Concrete\Core\Entity\Site\SiteTree
     */
    protected $siteTree;

    /**
     * Options constructor.
     *
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
        return 'dashboard/options';
    }

    public function view()
    {
        $config = Module::getConfig();
        $app = $this->app;
        $this->set('cookieDisclaimer', $config->get("{$this->siteTree->getSiteTreeID()}.cookieDisclaimer", function () use ($app) {
            $loc = $app->make('Concrete\Core\Localization\Localization');
            $loc->setContextLocale('SITE_SECTION', $this->siteTree->getLocale()->getLocale());

            return $loc->withContext('SITE_SECTION', function () {
                return Module::getFileConfig()->get('cookie_disclaimer.third_party');
            });
        }));
    }

    /**
     * {@inheritdoc}
     */
    public function saveFromRequest(): ErrorList
    {
        $e = $this->app->make('error');
        $thirdPartyData = $this->request->request->get('thirdParty', []);

        $config = Module::getConfig();
        $config->save("{$this->siteTree->getSiteTreeID()}.cookieDisclaimer.title", array_get($thirdPartyData, 'cookieDisclaimer.title', ''));
        $config->save("{$this->siteTree->getSiteTreeID()}.cookieDisclaimer.description", array_get($thirdPartyData, 'cookieDisclaimer.description', ''));

        return $e;
    }
}
