<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CartNoteWidget\Dependency\Client\CartNoteWidgetToCartNoteClientBridge;
use SprykerShop\Yves\CartNoteWidget\Dependency\Client\CartNoteWidgetToGlossaryStorageClientBridge;

class CartNoteWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CART_NOTE = 'CLIENT_CART_NOTE';
    public const CLIENT_GLOSSARY = 'CLIENT_GLOSSARY';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCartNoteClient($container);
        $container = $this->addGlossaryClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartNoteClient(Container $container): Container
    {
        $container->set(static::CLIENT_CART_NOTE, function (Container $container) {
            return new CartNoteWidgetToCartNoteClientBridge($container->getLocator()->cartNote()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addGlossaryClient(Container $container): Container
    {
        $container->set(static::CLIENT_GLOSSARY, function (Container $container) {
            return new CartNoteWidgetToGlossaryStorageClientBridge($container->getLocator()->glossaryStorage()->client());
        });

        return $container;
    }
}
