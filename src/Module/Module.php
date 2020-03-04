<?php
namespace Concrete\Package\ActiveCookieConsentThirdParty\Module;

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
     * @param \Concrete\Core\Entity\Site\SiteTree $siteTree
     *
     * @return Element
     */
    public static function getDashboardOptionsElement($siteTree)
    {
        return new Element('dashboard/options', self::pkgHandle(), ['siteTree' => $siteTree]);
    }

    /**
     * @param \Concrete\Core\Entity\Site\SiteTree $siteTree
     *
     * @return Element
     */
    public static function getOptoutOptionsElement($siteTree)
    {
        return new Element('optout/options', self::pkgHandle(), ['siteTree' => $siteTree]);
    }
}
