<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\LanguageSwitcherWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\LanguageSwitcherWidget\Dependency\Client\LanguageSwitcherWidgetToLocaleClientInterface;

class LanguageSwitcherWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\LanguageSwitcherWidget\Dependency\Client\LanguageSwitcherWidgetToUrlStorageClientInterface
     */
    public function getUrlStorageClient()
    {
        return $this->getProvidedDependency(LanguageSwitcherWidgetDependencyProvider::CLIENT_URL_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\LanguageSwitcherWidget\Dependency\Client\LanguageSwitcherWidgetToLocaleClientInterface
     */
    public function getLocaleClient(): LanguageSwitcherWidgetToLocaleClientInterface
    {
        return $this->getProvidedDependency(LanguageSwitcherWidgetDependencyProvider::CLIENT_LOCALE);
    }
}
