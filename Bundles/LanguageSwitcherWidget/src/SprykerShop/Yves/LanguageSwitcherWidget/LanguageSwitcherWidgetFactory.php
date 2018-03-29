<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\LanguageSwitcherWidget;

use Spryker\Yves\Kernel\AbstractFactory;

class LanguageSwitcherWidgetFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(LanguageSwitcherWidgetDependencyProvider::STORE);
    }

    /**
     * @return \SprykerShop\Yves\LanguageSwitcherWidget\Dependency\Client\LanguageSwitcherWidgetToUrlStorageClientInterface
     */
    public function getUrlStorageClient()
    {
        return $this->getProvidedDependency(LanguageSwitcherWidgetDependencyProvider::CLIENT_URL_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\LanguageSwitcherWidget\Dependency\Service\LanguageSwitcherWidgetToSynchronizationServiceInterface
     */
    public function getSynchronizationService()
    {
        return $this->getProvidedDependency(LanguageSwitcherWidgetDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
