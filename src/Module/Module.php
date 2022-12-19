<?php

namespace Concrete\Package\ActiveCookieConsentThirdParty\Module;

use Concrete\Core\Block\Block;
use Concrete\Core\Captcha\Library as SystemCaptchaLibrary;
use Concrete\Core\Filesystem\Element;
use Concrete\Core\Foundation\ClassAliasList;
use Concrete\Core\Routing\RouteListInterface;
use Concrete\Core\Support\Facade\Route;
use Concrete\Package\ActiveCookieConsent\Module\Module as AbstractModule;
use Concrete\Package\ActiveCookieConsentThirdParty\Event\Subscriber;
use Concrete\Package\ActiveCookieConsentThirdParty\Route\RouteList;

class Module extends AbstractModule
{
    /**
     * {@inheritdoc}
     *
     * @see AbstractModule::pkgHandle()
     */
    public static function pkgHandle(): string
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
        $aliasList = ClassAliasList::getInstance();
        $aliasList->registerMultiple(self::getClassAliases());

        $app = self::app();
        if (!$app['helper/concrete/dashboard']->canRead()) {
            $app['director']->addSubscriber($app->build(Subscriber::class));
        }

        AssetManager::register();

        // Register Route Lists
        if (is_array($routeListClasses = static::getRoutesClasses()) && $routeListClasses !== []) {
            $router = Route::getFacadeRoot();
            foreach ($routeListClasses as $routeListClass) {
                if (is_subclass_of($routeListClass, RouteListInterface::class)) {
                    $router->loadRouteList($app->build($routeListClass));
                } else {
                    throw new \RuntimeException(t('%s:%s - `%s` should be an instance of `%s`', static::class, 'getRoutesClasses', (string) $routeListClass, RouteListInterface::class));
                }
            }
        }
    }

    /**
     * @inheritdoc
     *
     * @see AbstractModule::getRoutesClasses()
     */
    protected static function getRoutesClasses(): array
    {
        return [
            RouteList::class,
        ];
    }

    /**
     * @param \Concrete\Core\Entity\Site\SiteTree $siteTree
     *
     * @return Element
     */
    public static function getDashboardOptionsElement($siteTree): Element
    {
        return new Element('dashboard/options', self::pkgHandle(), ['siteTree' => $siteTree]);
    }

    /**
     * @param \Concrete\Core\Entity\Site\SiteTree $siteTree
     *
     * @return Element
     */
    public static function getOptoutOptionsElement($siteTree): Element
    {
        return new Element('third_party_optout/options', self::pkgHandle(), ['siteTree' => $siteTree]);
    }

    private static function getSupportBlockList(): array
    {
        $blockHandles = ['youtube', 'growthcurve_vimeo_video', 'google_map'];
        $scl = SystemCaptchaLibrary::getActive();
        if ($scl->getSystemCaptchaLibraryHandle() === 'recaptchaV3' || $scl->getSystemCaptchaLibraryHandle() === 'recaptcha') {
            $blockHandles = array_merge($blockHandles, ['express_form', 'form']);
        }
        return $blockHandles;
    }

    public static function supports(Block $block): bool
    {
        return in_array(
            $block->getBlockTypeHandle(),
            static::getSupportBlockList(),
            true
        );
    }
}