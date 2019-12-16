<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopRouter\Creator;

use Silex\Application;
use Spryker\Shared\Application\Communication\ControllerServiceBuilder;
use Spryker\Shared\Kernel\Communication\BundleControllerActionInterface;
use Spryker\Yves\Kernel\BundleControllerAction;
use Spryker\Yves\Kernel\ClassResolver\Controller\ControllerResolver;
use Spryker\Yves\Kernel\Controller\BundleControllerActionRouteNameResolver;
use SprykerShop\Yves\ShopRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface;

/**
 * @deprecated Use `spryker-shop/storage-router` instead.
 */
class ResourceCreatorHandler implements ResourceCreatorHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\ShopRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface[]
     */
    protected $resourceCreatorPlugins;

    /**
     * @var \Silex\Application
     */
    protected $application;

    /**
     * @param \SprykerShop\Yves\ShopRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface[] $resourceCreatorPlugins
     * @param \Silex\Application $application
     */
    public function __construct(array $resourceCreatorPlugins, Application $application)
    {
        $this->resourceCreatorPlugins = $resourceCreatorPlugins;
        $this->application = $application;
    }

    /**
     * @param string $type
     * @param array $data
     *
     * @return array|null
     */
    public function create($type, array $data)
    {
        foreach ($this->resourceCreatorPlugins as $resourceCreator) {
            if ($type === $resourceCreator->getType()) {
                return $this->createResource($resourceCreator, $data);
            }
        }

        return null;
    }

    /**
     * @param \SprykerShop\Yves\ShopRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface $resourceCreator
     * @param array $data
     *
     * @return array
     */
    protected function createResource(ResourceCreatorPluginInterface $resourceCreator, array $data)
    {
        $bundleControllerAction = new BundleControllerAction($resourceCreator->getModuleName(), $resourceCreator->getControllerName(), $resourceCreator->getActionName());
        $routeResolver = new BundleControllerActionRouteNameResolver($bundleControllerAction);

        $service = $this->createServiceForController($this->application, $bundleControllerAction, $routeResolver);

        $resourceCreatorResult = $resourceCreator->mergeResourceData($data);
        $resourceCreatorResult['_controller'] = $service;
        $resourceCreatorResult['_route'] = $routeResolver->resolve();

        return $resourceCreatorResult;
    }

    /**
     * @param \Silex\Application $application
     * @param \Spryker\Shared\Kernel\Communication\BundleControllerActionInterface $bundleControllerAction
     * @param \Spryker\Yves\Kernel\Controller\BundleControllerActionRouteNameResolver $routeNameResolver
     *
     * @return string
     */
    protected function createServiceForController(
        Application $application,
        BundleControllerActionInterface $bundleControllerAction,
        BundleControllerActionRouteNameResolver $routeNameResolver
    ) {
        $controllerResolver = new ControllerResolver();
        $service = (new ControllerServiceBuilder())->createServiceForController(
            $application,
            $bundleControllerAction,
            $controllerResolver,
            $routeNameResolver
        );

        return $service;
    }
}
