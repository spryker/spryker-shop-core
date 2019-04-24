<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartShareWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Kernel\Application;
use SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToCustomerClientInterface;
use SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToPersistentCartShareClientInterface;
use SprykerShop\Yves\PersistentCartShareWidget\Glossary\GlossaryHelper;
use SprykerShop\Yves\PersistentCartShareWidget\Glossary\GlossaryHelperInterface;
use SprykerShop\Yves\PersistentCartShareWidget\PersistentCartShare\PersistentCartShareHelper;
use SprykerShop\Yves\PersistentCartShareWidget\PersistentCartShare\PersistentCartShareHelperInterface;

class PersistentCartShareWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): PersistentCartShareWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(PersistentCartShareWidgetDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToPersistentCartShareClientInterface
     */
    public function getPersistentCartShareClient(): PersistentCartShareWidgetToPersistentCartShareClientInterface
    {
        return $this->getProvidedDependency(PersistentCartShareWidgetDependencyProvider::CLIENT_PERSISTENT_CART_SHARE);
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
     */
    public function getApplication(): Application
    {
        return $this->getProvidedDependency(PersistentCartShareWidgetDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \SprykerShop\Yves\PersistentCartShareWidget\Glossary\GlossaryHelperInterface
     */
    public function getGlossaryHelper(): GlossaryHelperInterface
    {
        return new GlossaryHelper();
    }

    /**
     * @return \SprykerShop\Yves\PersistentCartShareWidget\PersistentCartShare\PersistentCartShareHelperInterface
     */
    public function getPersistentCartShareHelper(): PersistentCartShareHelperInterface
    {
        return new PersistentCartShareHelper(
            $this->getPersistentCartShareClient(),
            $this->getGlossaryHelper(),
            $this->getApplication()
        );
    }
}
