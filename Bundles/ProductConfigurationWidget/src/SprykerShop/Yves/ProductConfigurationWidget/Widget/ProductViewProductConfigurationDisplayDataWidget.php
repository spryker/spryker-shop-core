<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWidget\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductConfigurationWidget\ProductConfigurationWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ProductConfigurationWidget\ProductConfigurationWidgetConfig getConfig()
 */
class ProductViewProductConfigurationDisplayDataWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     */
    public function __construct(ProductViewTransfer $productViewTransfer)
    {
        $productConfigurationInstanceTransfer = $productViewTransfer->getProductConfigurationInstance();

        if (!$productConfigurationInstanceTransfer) {
            return;
        }

        $data = $this->getFactory()->createProductConfigurationDataReader()
            ->getProductConfigurationInstanceTemplateData($productConfigurationInstanceTransfer);

        $this->addParameter('template', $data);
        $this->addParameter('isComplete', $productConfigurationInstanceTransfer->getIsComplete());
        $this->addParameter('rawData', $productConfigurationInstanceTransfer->getDisplayData());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductViewProductConfigurationDisplayDataWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductConfigurationWidget/views/product-configuration/product-view-product-configuration.twig';
    }
}
