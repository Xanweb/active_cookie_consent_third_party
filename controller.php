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
    protected $pkgVersion = '1.4.0';
    protected $packageDependencies = ['active_cookie_consent' => '1.3.0'];

    public function getPackageDescription(): string
    {
        return t('Add Support for ThirdParty Cookie Control');
    }

    public function getPackageName(): string
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

        $config = $this->getConfig();
        $config->save('youtube.enabled', 1);
        $config->save('gmap.enabled', 1);

        return $pkg;
    }

    public function upgrade()
    {
        parent::upgrade();

        $this->restoreOverriddenBlocks();
    }

    public function uninstall()
    {
        $this->restoreOverriddenBlocks();

        parent::uninstall();
    }

    /**
     * {@inheritdoc}
     */
    public function getPackageAutoloaderRegistries(): array
    {
        if (!class_exists(Module::class)) {
            return ['src' => 'Concrete\Package\ActiveCookieConsentThirdParty'];
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getDrivers(): array
    {
        return [];
    }

    /**
     * Restore Overridden Blocks.
     */
    private function restoreOverriddenBlocks(): void
    {
        foreach (['youtube', 'google_map'] as $btHandle) {
            $bt = BlockType::getByHandle($btHandle);
            $bt->setPackageID(0);
            $bt->refresh();
        }
    }
}
