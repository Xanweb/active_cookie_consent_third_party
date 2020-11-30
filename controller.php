<?php

namespace Concrete\Package\ActiveCookieConsentThirdParty;

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Database\EntityManager\Provider\ProviderInterface;
use Concrete\Core\Package\Package;
use Concrete\Package\ActiveCookieConsentThirdParty\Module\Module;

class Controller extends Package implements ProviderInterface
{
    protected $pkgHandle = 'active_cookie_consent_third_party';
    protected $appVersionRequired = '8.5.1';
    protected $pkgVersion = '1.2.4';
    protected $pkgAutoloaderRegistries = [
        'src' => 'Concrete\Package\ActiveCookieConsentThirdParty',
    ];

    protected $packageDependencies = ['active_cookie_consent' => '1.1'];

    private $blocksOverride = ['youtube', 'google_map'];

    public function getPackageDescription()
    {
        return t('Add Support for ThirdParty Cookie Control');
    }

    public function getPackageName()
    {
        return t('Third Party Cookie Consent');
    }

    public function on_start()
    {
        Module::boot();
    }

    public function install()
    {
        $pkg = parent::install();

        $this->overrideBlocksByPackage($pkg);

        $config = $this->getConfig();
        $config->save('youtube.enabled', 1);
        $config->save('gmap.enabled', 1);

        return $pkg;
    }

    public function uninstall()
    {
        $this->restoreOverriddenBlocks();

        parent::uninstall();
    }

    /**
     * {@inheritdoc}
     */
    public function getDrivers()
    {
        return [];
    }

    /**
     * Override Block By Package.
     *
     * @param \Concrete\Core\Entity\Package $pkg
     */
    private function overrideBlocksByPackage($pkg)
    {
        foreach ($this->blocksOverride as $btHandle) {
            $blockYoutube = BlockType::getByHandle($btHandle);
            $blockYoutube->setPackageID($pkg->getPackageID());
            $blockYoutube->refresh();
        }
    }

    /**
     * Restore Overridden Blocks.
     */
    private function restoreOverriddenBlocks()
    {
        foreach ($this->blocksOverride as $btHandle) {
            $blockYoutube = BlockType::getByHandle($btHandle);
            $blockYoutube->setPackageID(0);
            $blockYoutube->refresh();
        }
    }
}
