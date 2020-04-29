<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ReturnBarcodeWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ReturnBarcodeWidget\Dependency\Service\ReturnBarcodeToBarcodeServiceInterface;

class ReturnBarcodeWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ReturnBarcodeWidget\Dependency\Service\ReturnBarcodeToBarcodeServiceInterface
     */
    public function getBarcodeService(): ReturnBarcodeToBarcodeServiceInterface
    {
        return $this->getProvidedDependency(ReturnBarcodeWidgetDependencyProvider::SERVICE_BARCODE);
    }
}
