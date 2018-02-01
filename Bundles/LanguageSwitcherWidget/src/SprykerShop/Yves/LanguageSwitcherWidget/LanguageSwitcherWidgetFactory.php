<?php

namespace SprykerShop\Yves\LanguageSwitcherWidget;

use Spryker\Yves\Kernel\AbstractFactory;

class LanguageSwitcherWidgetFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Kernel\Store
     *
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getStore()
    {
        return $this->getProvidedDependency(LanguageSwitcherWidgetDependencyProvider::STORE);
    }

    /**
     * @return \SprykerShop\Yves\LanguageSwitcherWidget\Dependency\Client\LanguageSwitcherWidgetToUrlStorageClientInterface
     *
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getUrlStorageClient()
    {
        return $this->getProvidedDependency(LanguageSwitcherWidgetDependencyProvider::CLIENT_URL_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\LanguageSwitcherWidget\Dependency\Service\LanguageSwitcherWidgetToSynchronizationServiceInterface
     *
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getSynchronizationService()
    {
        return $this->getProvidedDependency(LanguageSwitcherWidgetDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
