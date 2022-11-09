<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Twig;

use LogicException;
use SprykerShop\Yves\ShopApplication\Dependency\Service\ShopApplicationToUtilTextServiceInterface;

class RoutingHelper implements RoutingHelperInterface
{
    /**
     * @var \Spryker\Service\Container\ContainerInterface
     */
    protected $app;

    /**
     * @var \SprykerShop\Yves\ShopApplication\Dependency\Service\ShopApplicationToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Service\Container\ContainerInterface $app
     * @param \SprykerShop\Yves\ShopApplication\Dependency\Service\ShopApplicationToUtilTextServiceInterface $utilTextService
     */
    public function __construct($app, ShopApplicationToUtilTextServiceInterface $utilTextService)
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
            $controllerNamespaceName = get_class($this->app->get($serviceName));
        } else {
            throw new LogicException('Cannot parse destination');
        }
        [$namespace, $application, $module, $layer, $controllerName] = explode('\\', (string)$controllerNamespaceName);

        $module = $this->resolveModuleName($module);

        $controller = $this->utilTextService->camelCaseToSeparator(str_replace('Controller', '', $controllerName));
        $action = $this->utilTextService->camelCaseToSeparator((str_replace('Action', '', $actionName)));

        return $module . '/' . $controller . '/' . $action;
    }

    /**
     * @param string $moduleName
     *
     * @return string
     */
    protected function resolveModuleName(string $moduleName): string
    {
        $codeBucketIdentifierLength = mb_strlen(APPLICATION_CODE_BUCKET);
        $codeBucketSuffix = mb_substr($moduleName, -$codeBucketIdentifierLength);

        if ($codeBucketSuffix === APPLICATION_CODE_BUCKET) {
            $moduleName = mb_substr($moduleName, 0, -$codeBucketIdentifierLength);
        }

        return $moduleName;
    }
}
