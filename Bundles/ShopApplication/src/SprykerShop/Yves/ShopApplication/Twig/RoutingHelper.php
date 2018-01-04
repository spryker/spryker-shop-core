<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Twig;

use LogicException;
use Silex\Application;
use Spryker\Shared\Kernel\Store;
use SprykerShop\Yves\ShopApplication\Dependency\Service\ShopApplicationToUtilTextServiceInterface;

class RoutingHelper implements RoutingHelperInterface
{
    /**
     * @var \Silex\Application
     */
    protected $app;

    /**
     * @var Store
     */
    protected $store;

    /**
     * @var ShopApplicationToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param \Silex\Application $app
     * @param Store $store
     * @param ShopApplicationToUtilTextServiceInterface $utilTextService
     */
    public function __construct(Application $app, Store $store, ShopApplicationToUtilTextServiceInterface $utilTextService)
    {
        $this->app = $app;
        $this->store = $store;
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param string $destination
     *
     * @return string
     */
    public function getRouteFromDestination($destination)
    {
        if (strpos($destination, '::') !== false) {
            list($controllerNamespaceName, $actionName) = explode('::', $destination);
        } elseif (strpos($destination, ':') !== false) {
            list($serviceName, $actionName) = explode(':', $destination);
            $controllerNamespaceName = get_class($this->app[$serviceName]);
        } else {
            throw new LogicException('Cannot parse destination');
        }
        list($namespace, $application, $module, $layer, $controllerName) = explode('\\', $controllerNamespaceName);

        $module = str_replace($this->store->getStoreName(), '', $module);

        $controller = $this->utilTextService->camelCaseToSeparator(str_replace('Controller', '', $controllerName));
        $action = $this->utilTextService->camelCaseToSeparator((str_replace('Action', '', $actionName)));

        return $module . '/' . $controller . '/' . $action;
    }
}
