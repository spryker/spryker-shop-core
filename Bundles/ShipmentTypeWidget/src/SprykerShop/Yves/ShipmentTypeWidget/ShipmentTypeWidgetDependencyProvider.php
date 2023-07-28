<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentTypeWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ShipmentTypeWidget\Dependency\Client\ShipmentTypeWidgetToShipmentTypeStorageClientBridge;

/**
 * @method \SprykerShop\Yves\ShipmentTypeWidget\ShipmentTypeWidgetConfig getConfig()
 */
class ShipmentTypeWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_SHIPMENT_TYPE_STORAGE = 'CLIENT_SHIPMENT_TYPE_STORAGE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addShipmentTypeStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShipmentTypeStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_SHIPMENT_TYPE_STORAGE, function (Container $container) {
            return new ShipmentTypeWidgetToShipmentTypeStorageClientBridge($container->getLocator()->shipmentTypeStorage()->client());
        });

        return $container;
    }
}
