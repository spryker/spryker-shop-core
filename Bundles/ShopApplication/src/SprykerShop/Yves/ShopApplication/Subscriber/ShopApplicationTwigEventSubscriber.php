<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Subscriber;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Yves\Kernel\View\ViewInterface;
use Spryker\Yves\Kernel\Widget\WidgetContainerInterface;
use Spryker\Yves\Kernel\Widget\WidgetContainerRegistryInterface;
use SprykerShop\Yves\ShopApplication\ShopApplicationConfig;
use SprykerShop\Yves\ShopApplication\Twig\RoutingHelperInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class ShopApplicationTwigEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var string
     */
    protected const SERVICE_TWIG = 'twig';

    /**
     * @var string
     */
    protected const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @var string
     */
    protected const TWIG_GLOBAL_VARIABLE_NAME_VIEW = '_view';

    /**
     * @var \Spryker\Service\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var \Spryker\Yves\Kernel\Widget\WidgetContainerRegistryInterface
     */
    protected $widgetContainerRegistry;

    /**
     * @var \SprykerShop\Yves\ShopApplication\Twig\RoutingHelperInterface
     */
    protected $routingHelper;

    /**
     * @var \SprykerShop\Yves\ShopApplication\ShopApplicationConfig
     */
    protected $shopApplicationConfig;

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     * @param \Spryker\Yves\Kernel\Widget\WidgetContainerRegistryInterface $widgetContainerRegistry
     * @param \SprykerShop\Yves\ShopApplication\Twig\RoutingHelperInterface $routingHelper
     * @param \SprykerShop\Yves\ShopApplication\ShopApplicationConfig $shopApplicationConfig
     */
    public function __construct(
        ContainerInterface $container,
        WidgetContainerRegistryInterface $widgetContainerRegistry,
        RoutingHelperInterface $routingHelper,
        ShopApplicationConfig $shopApplicationConfig
    ) {
        $this->container = $container;
        $this->widgetContainerRegistry = $widgetContainerRegistry;
        $this->routingHelper = $routingHelper;
        $this->shopApplicationConfig = $shopApplicationConfig;
    }

    /**
     * @return array<string, mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => 'onKernelView',
            KernelEvents::CONTROLLER_ARGUMENTS => 'onControllerResolved',
        ];
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent $event
     *
     * @return void
     */
    public function onControllerResolved(ControllerArgumentsEvent $event): void
    {
        $this->addGlobalView(null);
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ViewEvent $event
     *
     * @return void
     */
    public function onKernelView(ViewEvent $event): void
    {
        $result = $event->getControllerResult();
        $masterGlobalView = null;

        if ($result instanceof ViewInterface) {
            if (!$this->isMainRequest($event)) {
                $masterGlobalView = $this->getGlobalView();
            }

            $this->addGlobalView($result);
        }

        if ($result instanceof WidgetContainerInterface) {
            $this->addWidgetContainerRegister($result);
        }

        $event->setResponse($this->getResponse($result));
        $this->widgetContainerRegistry->removeLastAdded();

        if ($masterGlobalView) {
            $this->addGlobalView($masterGlobalView);
        }
    }

    /**
     * @param \Spryker\Yves\Kernel\View\ViewInterface|null $view
     *
     * @return void
     */
    protected function addGlobalView(?ViewInterface $view): void
    {
        $twig = $this->getTwig();
        $twig->addGlobal(static::TWIG_GLOBAL_VARIABLE_NAME_VIEW, $view);
    }

    /**
     * @return \Spryker\Yves\Kernel\View\ViewInterface|null
     */
    protected function getGlobalView(): ?ViewInterface
    {
        $twigGlobals = $this->getTwig()->getGlobals();

        if (!isset($twigGlobals[static::TWIG_GLOBAL_VARIABLE_NAME_VIEW])) {
            return null;
        }

        return $twigGlobals[static::TWIG_GLOBAL_VARIABLE_NAME_VIEW];
    }

    /**
     * @param mixed $result
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    protected function getResponse($result): ?Response
    {
        $parameters = $this->getViewParameters($result);
        $template = $this->getTemplateName($result, $parameters);

        if (!$template) {
            return null;
        }

        return $this->createResponse($template, $parameters);
    }

    /**
     * @param mixed $result
     * @param array<string, mixed> $parameters
     *
     * @return string|null
     */
    protected function getTemplateName($result, array $parameters): ?string
    {
        $template = null;
        if ($result instanceof ViewInterface) {
            $template = $result->getTemplate();
        }

        if (!$template) {
            $request = $this->getRequestStack()->getCurrentRequest();
            $template = $request->attributes->get('_template');

            if ($template) {
                return sprintf('@%s.twig', $template);
            }

            $controller = $request->attributes->get('_controller');

            if (!is_string($controller) || !$controller) {
                return null;
            }

            $template = '@' . $this->getRoute($parameters, $controller) . '.twig';
        }

        return $template;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    protected function getRequestStack(): RequestStack
    {
        return $this->container->get(static::SERVICE_REQUEST_STACK);
    }

    /**
     * @param array<string, mixed> $parameters
     * @param string $controller
     *
     * @return string
     */
    protected function getRoute(array $parameters, string $controller): string
    {
        if (isset($parameters['alternativeRoute'])) {
            return (string)$parameters['alternativeRoute'];
        }

        return $this->routingHelper->getRouteFromDestination($controller);
    }

    /**
     * @param string $template
     * @param array<string, mixed> $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function createResponse(string $template, array $parameters = []): Response
    {
        $response = new Response();
        $response->setContent($this->getTwig()->render($template, $parameters));

        return $response;
    }

    /**
     * @return \Twig\Environment
     */
    protected function getTwig(): Environment
    {
        return $this->container->get(static::SERVICE_TWIG);
    }

    /**
     * @param mixed $result
     *
     * @return array
     */
    protected function getViewParameters($result): array
    {
        if ($result instanceof ViewInterface && $this->shopApplicationConfig->useViewParametersToRenderTwig()) {
            return $result->getData();
        }

        return (array)$result;
    }

    /**
     * @param \Spryker\Yves\Kernel\Widget\WidgetContainerInterface $result
     *
     * @return void
     */
    protected function addWidgetContainerRegister(WidgetContainerInterface $result): void
    {
        $this->widgetContainerRegistry->add($result);
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ViewEvent $event
     *
     * @return bool
     */
    protected function isMainRequest(ViewEvent $event): bool
    {
        if (method_exists($event, 'isMasterRequest')) {
            return $event->isMasterRequest();
        }

        return $event->isMainRequest();
    }
}
