<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\BarcodeWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\BarcodeWidget\Dependency\Service\BarcodeToBarcodeServiceInterface;

class BarcodeWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\BarcodeWidget\Dependency\Service\BarcodeToBarcodeServiceInterface
     */
    public function getBarcodeService(): BarcodeToBarcodeServiceInterface
    {
        return $this->getProvidedDependency(BarcodeWidgetDependencyProvider::SERVICE_BARCODE);
    }
}
