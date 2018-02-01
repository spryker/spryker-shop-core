<?php

namespace SprykerShop\Yves\LanguageSwitcherWidget\Dependency\Service;

interface LanguageSwitcherWidgetToSynchronizationServiceInterface
{
    /**
     * @param string $resourceName
     *
     * @return \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface
     */
    public function getStorageKeyBuilder($resourceName);
}