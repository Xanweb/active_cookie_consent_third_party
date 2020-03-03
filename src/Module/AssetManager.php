<?php
namespace Concrete\Package\ActiveCookieConsentThirdParty\Module;

use Concrete\Core\Asset\AssetList;
use Xanweb\Foundation\Traits\StaticApplicationTrait;

class AssetManager
{
    use StaticApplicationTrait;

    public static function register()
    {
        $pkg = Module::pkg();
        $al = AssetList::getInstance();
        $al->registerMultiple([
            'block/youtube' => [
                ['css', 'css/youtube.css', ['minify' => true], $pkg],
                ['javascript', 'js/dp-youtube.js', ['minify' => false], $pkg],
            ],
            'block/google-map' => [
                ['css', 'css/google-map.css', ['minify' => true], $pkg],
                ['javascript', 'js/dp-google-map.js', ['minify' => false], $pkg],
            ],
        ]);

        $al->registerGroupMultiple([
            'block/youtube' => [
                [
                    ['vendor-javascript', 'js-cookie'],
                    ['javascript', 'dp/base'],
                    ['javascript', 'block/youtube'],
                    ['css', 'block/youtube'],
                ],
            ],
            'block/google-map' => [
                [
                    ['vendor-javascript', 'js-cookie'],
                    ['javascript', 'dp/base'],
                    ['javascript', 'block/google-map'],
                    ['css', 'block/google-map'],
                ],
            ],
        ]);
    }
}
