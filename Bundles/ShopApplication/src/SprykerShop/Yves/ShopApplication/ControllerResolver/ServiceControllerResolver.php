<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\ControllerResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

/**
 * @deprecated Use `spryker/router` instead.
 */
class ServiceControllerResolver implements ControllerResolverInterface, ArgumentResolverInterface
{
    /**
     * @var \Symfony\Component\HttpKernel\Controller\ControllerResolverInterface
     */
    protected $controllerResolver;

    /**
     * @var \SprykerShop\Yves\ShopApplication\ControllerResolver\CallbackControllerResolverInterface
     */
    protected $callbackResolver;

    /**
     * @param \Symfony\Component\HttpKernel\Controller\ControllerResolverInterface $controllerResolver
     * @param \SprykerShop\Yves\ShopApplication\ControllerResolver\CallbackControllerResolverInterface $callbackResolver
     */
    public function __construct(ControllerResolverInterface $controllerResolver, CallbackControllerResolverInterface $callbackResolver)
    {
        $this->controllerResolver = $controllerResolver;
        $this->callbackResolver = $callbackResolver;
    }

    /**
     * @inheritDoc
     */
    public function getController(Request $request)
    {
        $controller = $request->attributes->get('_controller', null);

        if (!$this->callbackResolver->isValid($controller)) {
            return $this->controllerResolver->getController($request);
        }

        return $this->callbackResolver->convertCallback($controller);
    }

    /**
     * {@inheritDoc}
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param callable $controller
     *
     * @return array
     */
    public function getArguments(Request $request, $controller)
    {
        if (method_exists($this->controllerResolver, 'getArguments')) {
            return $this->controllerResolver->getArguments($request, $controller);
        }
        
        return [];
    }
}
