<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Twig;

use Spryker\Shared\Kernel\Communication\Application;

class TwigRenderer implements TwigRendererInterface
{
    /**
     * @var \SprykerShop\Yves\ShopApplication\Twig\RoutingHelperInterface
     */
    protected $routingHelper;

    /**
     * @param \SprykerShop\Yves\ShopApplication\Twig\RoutingHelperInterface $routingHelper
     */
    public function __construct(RoutingHelperInterface $routingHelper)
    {
        $this->routingHelper = $routingHelper;
    }

    /**
     * Renders the template for the current controller/action
     *
     * @param \Spryker\Shared\Kernel\Communication\Application $application
     * @param array<string, mixed> $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function render(Application $application, array $parameters = [])
    {
        /** @var \Symfony\Component\HttpFoundation\RequestStack $requestStack */
        $requestStack = $application['request_stack'];
        $request = $requestStack->getCurrentRequest();
        $controller = $request->attributes->get('_controller');

        if (!is_string($controller) || !$controller) {
            return null;
        }

        $route = $this->getRoute($parameters, $controller);

        return $application->render('@' . $route . '.twig', $parameters);
    }

    /**
     * @param array<string, mixed> $parameters
     * @param string $controller
     *
     * @return string
     */
    protected function getRoute(array $parameters, $controller)
    {
        if (isset($parameters['alternativeRoute'])) {
            return (string)$parameters['alternativeRoute'];
        }

        return $this->routingHelper->getRouteFromDestination($controller);
    }
}
