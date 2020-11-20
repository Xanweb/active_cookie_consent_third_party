<?php

namespace Concrete\Package\ActiveCookieConsentThirdParty\Module;

use Concrete\Core\Asset\AssetList;

class AssetManager
{
    public static function register()
    {
        $pkg = Module::pkg();
        $al = AssetList::getInstance();
        $al->registerMultiple([
            'block/youtube' => [
                ['css', 'css/youtube.css', ['minify' => false], $pkg],
                ['javascript', 'js/youtube.js', ['minify' => false], $pkg],
            ],
            'block/google-map' => [
                ['css', 'css/google-map.css', ['minify' => false], $pkg],
                ['javascript', 'js/google-map.js', ['minify' => false], $pkg],
            ],
            'block/gorwthcurve-vimeo-video' => [
              ['css', 'css/gorwthcurve-vimeo-video.css', ['minify' => false], $pkg],
              ['javascript', 'js/gorwthcurve-vimeo-video.js', ['minify' => false], $pkg],
            ],
        ]);

        $al->registerGroupMultiple([
            'block/youtube' => [
                [
                    ['javascript', 'acc/base'],
                    ['javascript', 'block/youtube'],
                    ['css', 'block/youtube'],
                ],
            ],
            'block/google-map' => [
                [
                    ['javascript', 'acc/base'],
                    ['javascript', 'block/google-map'],
                    ['css', 'block/google-map'],
                ],
            ],
            'block/gorwthcurve-vimeo-video' => [
               [
                   ['javascript', 'acc/base'],
                   ['javascript', 'block/gorwthcurve-vimeo-video'],
                   ['css', 'block/gorwthcurve-vimeo-video'],
               ],
            ],
        ]);
    }
}
