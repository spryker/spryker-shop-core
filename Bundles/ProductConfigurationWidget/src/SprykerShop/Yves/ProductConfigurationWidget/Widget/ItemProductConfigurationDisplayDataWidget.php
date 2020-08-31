<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWidget\Widget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductConfigurationWidget\ProductConfigurationWidgetFactory getFactory()
 */
class ItemProductConfigurationDisplayDataWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     */
    public function __construct(ItemTransfer $itemTransfer)
    {
        $productConfigurationInstanceTransfer = $itemTransfer->getProductConfigurationInstance();

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
        return 'ItemProductConfigurationDisplayDataWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductConfigurationWidget/views/product-configuration/item-product-configuration.twig';
    }
}
