<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopRouter\Plugin\Router;

use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouterInterface;

/**
 * @deprecated Use {@link \Spryker\Yves\Router\Plugin\Router\YvesRouterPlugin} instead. Make sure that you don't have any ControllerProvider registered anymore before you remove this one.
 *
 * @method \SprykerShop\Yves\ShopRouter\ShopRouterFactory getFactory()
 */
class SilexRouter extends AbstractPlugin implements RouterInterface
{
    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    protected $sharedSilexRouter;

    public function __construct()
    {
        $this->sharedSilexRouter = $this->getFactory()->createSharedSilexRouter();
    }

    /**
     * @param \Symfony\Component\Routing\RequestContext $context
     *
     * @return void
     */
    public function setContext(RequestContext $context)
    {
        $this->sharedSilexRouter->setContext($context);
    }

    /**
     * @return \Symfony\Component\Routing\RequestContext
     */
    public function getContext(): RequestContext
    {
        return $this->sharedSilexRouter->getContext();
    }

    /**
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function getRouteCollection()
    {
        return $this->sharedSilexRouter->getRouteCollection();
    }

    /**
     * @param string $name
     * @param array<string, mixed> $parameters
     * @param int $referenceType
     *
     * @return string
     */
    public function generate(string $name, array $parameters = [], int $referenceType = self::ABSOLUTE_PATH): string
    {
        $generator = $this->getFactory()->createUrlGenerator($this->getRouteCollection(), $this->getContext());

        return $generator->generate($name, $parameters, $referenceType);
    }

    /**
     * @param string $pathinfo
     *
     * @return array
     */
    public function match(string $pathinfo): array
    {
        return $this->sharedSilexRouter->match($pathinfo);
    }
}
