<?php

namespace SprykerShop\Yves\LanguageSwitcherWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Shared\Kernel\Store;
use SprykerShop\Yves\LanguageSwitcherWidget\Dependency\Client\LanguageSwitcherWidgetToUrlStorageClientBridge;
use SprykerShop\Yves\LanguageSwitcherWidget\Dependency\Service\LanguageSwitcherWidgetToSynchronizationServiceBridge;

class LanguageSwitcherWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    const STORE = 'STORE';
    const CLIENT_URL_STORAGE = 'CLIENT_URL_STORAGE';
    const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';


    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $this->addStore($container);
        $this->addUrlStorageClient($container);
        $this->addSynchronizationService($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStore(Container $container)
    {
        $container[static::STORE] = function () {
            return Store::getInstance();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addUrlStorageClient(Container $container)
    {
        $container[static::CLIENT_URL_STORAGE] = function (Container $container) {
            return new LanguageSwitcherWidgetToUrlStorageClientBridge($container->getLocator()->urlStorage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSynchronizationService(Container $container)
    {
        $container[static::SERVICE_SYNCHRONIZATION] = function (Container $container) {
            return new LanguageSwitcherWidgetToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        };

        return $container;
    }
}
