<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopTranslator;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ShopTranslator\Dependency\Client\ShopTranslatorToGlossaryClientBridge;

class ShopTranslatorDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_GLOSSARY = 'CLIENT_GLOSSARY';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $this->addGlossaryClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addGlossaryClient(Container $container): Container
    {
        $container[self::CLIENT_GLOSSARY] = function (Container $container) {
            return new ShopTranslatorToGlossaryClientBridge($container->getLocator()->glossary()->client());
        };

        return $container;
    }
}
