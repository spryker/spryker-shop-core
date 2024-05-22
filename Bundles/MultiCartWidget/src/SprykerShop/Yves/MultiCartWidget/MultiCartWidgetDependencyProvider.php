<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\MultiCartWidget\Dependency\Client\MultiCartWidgetToMultiCartClientBridge;
use SprykerShop\Yves\MultiCartWidget\Dependency\Client\MultiCartWidgetToQuoteClientBridge;

class MultiCartWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @see \Spryker\Shared\Application\ApplicationConstants::FORM_FACTORY
     *
     * @var string
     */
    public const FORM_FACTORY = 'FORM_FACTORY';

    /**
     * @var string
     */
    public const CLIENT_MULTI_CART = 'CLIENT_MULTI_CART';

    /**
     * @var string
     */
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';

    /**
     * @var string
     */
    public const PLUGINS_VIEW_EXTEND = 'PLUGINS_VIEW_EXTEND';

    /**
     * @var string
     */
    public const TWIG_ENVIRONMENT = 'TWIG_ENVIRONMENT';

    /**
     * @uses \Spryker\Yves\Twig\Plugin\Application\TwigApplicationPlugin::SERVICE_TWIG
     *
     * @var string
     */
    protected const SERVICE_TWIG = 'twig';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container|array
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addMultiCartClient($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addViewExtendWidgetPlugins($container);
        $container = $this->addTwigService($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMultiCartClient($container)
    {
        $container->set(static::CLIENT_MULTI_CART, function (Container $container) {
            return new MultiCartWidgetToMultiCartClientBridge($container->getLocator()->multiCart()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuoteClient($container)
    {
        $container->set(static::CLIENT_QUOTE, function (Container $container) {
            return new MultiCartWidgetToQuoteClientBridge($container->getLocator()->quote()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addViewExtendWidgetPlugins(Container $container)
    {
        $container->set(static::PLUGINS_VIEW_EXTEND, function () {
            return $this->getViewExtendWidgetPlugins();
        });

        return $container;
    }

    /**
     * @return array
     */
    protected function getViewExtendWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addTwigService(Container $container): Container
    {
        $container->set(static::TWIG_ENVIRONMENT, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_TWIG);
        });

        return $container;
    }
}
