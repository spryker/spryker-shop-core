<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\StoreWidget\Plugin\ShopApplication;

use Exception;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \SprykerShop\Yves\StoreWidget\StoreWidgetConfig getConfig()
 * @method \SprykerShop\Yves\StoreWidget\StoreWidgetFactory getFactory()
 */
class StoreApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    /**
     * @var string
     */
    protected const STORE = 'store';

    /**
     * @var string
     */
    protected const SESSION_STORE = 'current_store';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container = $this->addStore($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addStore(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::STORE, function (ContainerInterface $container) {
            $storeName = $this->resolveStoreName($container);

            $this->getFactory()->getSessionClient()->set(static::SESSION_STORE, $storeName);

            return $storeName;
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function resolveStoreName(ContainerInterface $container): string
    {
        $storeName = $this->getStoreRequestUrlParameter();
        $storeNames = $this->getFactory()->getStoreStorageClient()->getStoreNames();
        if ($storeName) {
            if (in_array($storeName, $storeNames, true)) {
                return $storeName;
            }
        }

        $storeName = $this->getFactory()->getSessionClient()->get(static::SESSION_STORE);
        if ($storeName) {
            return $storeName;
        }

        if (defined('APPLICATION_STORE')) {
            return APPLICATION_STORE;
        }

        $defaultStoreName = current($storeNames);

        if (!$defaultStoreName) {
            throw new Exception('Cannot resolve store');
        }

        return $defaultStoreName;
    }

    /**
     * @return string|null
     */
    protected function getStoreRequestUrlParameter(): ?string
    {
        if (!$this->getFactory()->getRequest()) {
            return null;
        }

        /** @phpstan-var string|null */
        return $this->getFactory()->getRequest()->query->get('_store');
    }
}
