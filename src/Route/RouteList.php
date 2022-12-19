<?php

namespace Concrete\Package\ActiveCookieConsentThirdParty\Route;

use Concrete\Core\Routing\RouteListInterface;
use Concrete\Core\Routing\Router;
use Concrete\Package\ActiveCookieConsentThirdParty\Controller\Frontend\VideoThumbnail;

class RouteList implements RouteListInterface
{
    public function loadRoutes(Router $router): void
    {
        $router->get('/xw/acc/background/{type}/{id}', VideoThumbnail::class . '::getThumbnail');
    }
}