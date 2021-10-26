<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountWidget;

use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToCalculationClientBridge;
use SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToQuoteClientBridge;

class DiscountWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_CALCULATION = 'CLIENT_CALCULATION';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const PLUGIN_APPLICATION = 'PLUGIN_APPLICATION';

    /**
     * @var string
     */
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';

    /**
     * @uses \Spryker\Yves\Messenger\Plugin\Application\FlashMessengerApplicationPlugin::SERVICE_FLASH_MESSENGER
     *
     * @var string
     */
    public const SERVICE_FLASH_MESSENGER = 'flash_messenger';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCalculationClient($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addServiceFlashMessenger($container);
        $container = $this->addApplication($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCalculationClient(Container $container): Container
    {
        $container->set(static::CLIENT_CALCULATION, function (Container $container) {
            return new DiscountWidgetToCalculationClientBridge($container->getLocator()->calculation()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuoteClient(Container $container): Container
    {
        $container->set(static::CLIENT_QUOTE, function (Container $container) {
            return new DiscountWidgetToQuoteClientBridge($container->getLocator()->quote()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addServiceFlashMessenger(Container $container): Container
    {
        $container->set(static::SERVICE_FLASH_MESSENGER, function (ContainerInterface $container) {
            return $container->getApplicationService(static::SERVICE_FLASH_MESSENGER);
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addApplication(Container $container): Container
    {
        $container->set(static::PLUGIN_APPLICATION, function () {
            $pimplePlugin = new Pimple();

            return $pimplePlugin->getApplication();
        });

        return $container;
    }
}
