<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantOpeningHoursWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\MerchantOpeningHoursWidget\Dependency\Client\MerchantOpeningHoursWidgetToMerchantOpeningHoursStorageClientBridge;

class MerchantOpeningHoursWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_MERCHANT_OPENING_HOURS_STORAGE = 'CLIENT_MERCHANT_OPENING_HOURS_STORAGE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        parent::provideDependencies($container);

        $container = $this->addMerchantOpeningHoursStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMerchantOpeningHoursStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_MERCHANT_OPENING_HOURS_STORAGE, function (Container $container) {
            return new MerchantOpeningHoursWidgetToMerchantOpeningHoursStorageClientBridge(
                $container->getLocator()->merchantOpeningHoursStorage()->client()
            );
        });

        return $container;
    }
}
