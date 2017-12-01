<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopTranslator;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class ShopTranslatorDependencyProvider extends AbstractBundleDependencyProvider
{

    const CLIENT_GLOSSARY_STORAGE = 'CLIENT_GLOSSARY_STORAGE';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideDependencies(Container $container)
    {
        $this->addGlossaryStorageClient($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addGlossaryStorageClient(Container $container): Container
    {
        $container[self::CLIENT_GLOSSARY_STORAGE] = function (Container $container) {
            return $container->getLocator()->glossaryStorage()->client();
        };

        return $container;
    }

}
