<?php
namespace Concrete\Package\ActiveCookieConsentThirdParty\Controller\Element\Dashboard;

use Concrete\Core\Entity\Site\Site;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Controller\ElementController;
use Concrete\Package\ActiveCookieConsent\Dashboard\Element\SavableInterface;
use Concrete\Package\ActiveCookieConsentThirdParty\Module\Module;

class Options extends ElementController implements SavableInterface
{
    protected $helpers = ['form'];

    /**
     * @var Site
     */
    protected $site;

    public function __construct(Site $site)
    {
        parent::__construct();

        $this->site = $site;
        $this->pkgHandle = Module::pkgHandle();
    }

    public function getElement()
    {
        return 'dashboard/options';
    }

    public function view()
    {
        $config = Module::getConfig();

        $this->set('youtube', $config->get("{$this->site->getSiteHandle()}.youtube", ['enabled' => 0]));
        $this->set('gmap', $config->get("{$this->site->getSiteHandle()}.gmap", ['enabled' => 0]));
        $this->set('cookieDisclaimer', $config->get("{$this->site->getSiteHandle()}.cookieDisclaimer", ['title' => t('Third Party'), 'description' => '']));
    }

    /**
     * {@inheritdoc}
     */
    public function saveFromRequest(): ErrorList
    {
        $e = $this->app->make('error');
        $thirdPartyData = $this->request->request->get('thirdParty', []);

        $config = Module::getConfig();
        $config->save("{$this->site->getSiteHandle()}.youtube.enabled", array_get($thirdPartyData, 'youtube.enabled', 0));
        $config->save("{$this->site->getSiteHandle()}.gmap.enabled", array_get($thirdPartyData, 'gmap.enabled', 0));
        $config->save("{$this->site->getSiteHandle()}.cookieDisclaimer.title", array_get($thirdPartyData, 'cookieDisclaimer.title', ''));
        $config->save("{$this->site->getSiteHandle()}.cookieDisclaimer.description", array_get($thirdPartyData, 'cookieDisclaimer.description', ''));

        return $e;
    }
}
