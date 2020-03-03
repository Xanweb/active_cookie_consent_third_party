<?php
namespace Concrete\Package\ActiveCookieConsentThirdParty\Module;

use Concrete\Core\Entity\Site\Site;
use Concrete\Core\Filesystem\Element;
use Xanweb\Module\Module as AbstractModule;

class Module extends AbstractModule
{
    /**
     * {@inheritdoc}
     *
     * @see AbstractModule::pkgHandle()
     */
    public static function pkgHandle()
    {
        return 'active_cookie_consent_third_party';
    }

    /**
     * {@inheritdoc}
     *
     * @see AbstractModule::boot()
     */
    public static function boot()
    {
        parent::boot();

        AssetManager::register();
    }

    /**
     * @param Site $site active site for editing
     *
     * @return Element
     */
    public static function getDashboardOptionsElement(Site $site)
    {
        return new Element('dashboard/options', self::pkgHandle(), [$site]);
    }

    /**
     * @return Element
     */
    public static function getOptoutOptionsElement()
    {
        return new Element('optout/options', self::pkgHandle());
    }
}
