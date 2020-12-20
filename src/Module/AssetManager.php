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
            'acc/third-party' => [
                ['css', 'css/acc-third-party.css', ['minify' => false], $pkg],
                ['javascript', 'js/acc-third-party.js', ['minify' => false], $pkg],
            ],
        ]);

        $al->registerGroupMultiple([
            'acc/third-party' => [
                [
                    ['javascript', 'acc/config'],
                    ['javascript', 'acc/base'],
                    ['javascript', 'acc/third-party'],
                    ['css', 'acc/third-party'],
                ],
            ]
        ]);
    }
}
