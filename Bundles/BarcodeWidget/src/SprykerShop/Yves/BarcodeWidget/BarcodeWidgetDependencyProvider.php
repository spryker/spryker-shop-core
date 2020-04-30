<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\BarcodeWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\BarcodeWidget\Dependency\Service\BarcodeToBarcodeServiceBridge;

class BarcodeWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_BARCODE = 'SERVICE_BARCODE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addBarcodeService($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addBarcodeService(Container $container): Container
    {
        $container->set(static::SERVICE_BARCODE, function (Container $container) {
            return new BarcodeToBarcodeServiceBridge(
                $container->getLocator()->barcode()->service()
            );
        });

        return $container;
    }
}
