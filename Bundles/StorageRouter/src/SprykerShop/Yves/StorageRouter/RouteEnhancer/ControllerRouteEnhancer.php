<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\StorageRouter\RouteEnhancer;

use Spryker\Yves\Kernel\BundleControllerAction;
use Spryker\Yves\Kernel\ClassResolver\Controller\ControllerResolver;
use Spryker\Yves\Kernel\Controller\BundleControllerActionRouteNameResolver;
use SprykerShop\Yves\StorageRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface;
use Symfony\Cmf\Component\Routing\Enhancer\RouteEnhancerInterface;
use Symfony\Component\HttpFoundation\Request;

class ControllerRouteEnhancer implements RouteEnhancerInterface
{
    /**
     * @var string
     */
    protected const ATTRIBUTE_PATH_INFO = 'pathinfo';

    /**
     * @var string
     */
    protected const ATTRIBUTE_DATA = 'data';

    /**
     * @var array<\SprykerShop\Yves\StorageRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface>
     */
    protected $resourceCreatorPlugins;

    /**
     * @param array<\SprykerShop\Yves\StorageRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface> $resourceCreatorPlugins
     */
    public function __construct(array $resourceCreatorPlugins)
    {
        $this->resourceCreatorPlugins = $resourceCreatorPlugins;
    }

    /**
     * @param array<string, mixed> $defaults
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    public function enhance(array $defaults, Request $request): array
    {
        foreach ($this->resourceCreatorPlugins as $resourceCreator) {
            if ($defaults['type'] === $resourceCreator->getType()) {
                $resource = $this->createResource($resourceCreator, $defaults[static::ATTRIBUTE_DATA]);
                $resource[static::ATTRIBUTE_PATH_INFO] = $defaults[static::ATTRIBUTE_PATH_INFO];

                return $resource;
            }
        }

        return $defaults;
    }

    /**
     * @param \SprykerShop\Yves\StorageRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface $resourceCreator
     * @param array<string, mixed> $data
     *
     * @return array
     */
    protected function createResource(ResourceCreatorPluginInterface $resourceCreator, array $data)
    {
        $bundleControllerAction = new BundleControllerAction($resourceCreator->getModuleName(), $resourceCreator->getControllerName(), $resourceCreator->getActionName());
        $routeResolver = new BundleControllerActionRouteNameResolver($bundleControllerAction);

        $controllerResolver = new ControllerResolver();
        $controller = $controllerResolver->resolve($bundleControllerAction);
        $actionName = $resourceCreator->getActionName();
        if (strrpos($actionName, 'Action') === false) {
            $actionName .= 'Action';
        }

        $resourceCreatorResult = $resourceCreator->mergeResourceData($data);
        $resourceCreatorResult['_controller'] = [$controller, $actionName];
        $resourceCreatorResult['_route'] = $routeResolver->resolve();

        return $resourceCreatorResult;
    }
}
