<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Twig;

use LogicException;
use Silex\Application;
use SprykerShop\Yves\ShopApplication\Dependency\Service\ShopApplicationToUtilTextServiceInterface;

class RoutingHelper implements RoutingHelperInterface
{
    /**
     * @var \Silex\Application
     */
    protected $app;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @var \SprykerShop\Yves\ShopApplication\Dependency\Service\ShopApplicationToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param \Silex\Application $app
     * @param \SprykerShop\Yves\ShopApplication\Dependency\Service\ShopApplicationToUtilTextServiceInterface $utilTextService
     */
    public function __construct(Application $app, ShopApplicationToUtilTextServiceInterface $utilTextService)
    {
        $this->app = $app;
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param string $destination
     *
     * @throws \LogicException
     *
     * @return string
     */
    public function getRouteFromDestination($destination)
    {
        if (strpos($destination, '::') !== false) {
            [$controllerNamespaceName, $actionName] = explode('::', $destination);
        } elseif (strpos($destination, ':') !== false) {
            [$serviceName, $actionName] = explode(':', $destination);
            $controllerNamespaceName = get_class($this->app[$serviceName]);
        } else {
            throw new LogicException('Cannot parse destination');
        }
        [$namespace, $application, $module, $layer, $controllerName] = explode('\\', $controllerNamespaceName);

        $module = str_replace(APPLICATION_CODE_BUCKET, '', $module);

        $controller = $this->utilTextService->camelCaseToSeparator(str_replace('Controller', '', $controllerName));
        $action = $this->utilTextService->camelCaseToSeparator((str_replace('Action', '', $actionName)));

        return $module . '/' . $controller . '/' . $action;
    }
}
