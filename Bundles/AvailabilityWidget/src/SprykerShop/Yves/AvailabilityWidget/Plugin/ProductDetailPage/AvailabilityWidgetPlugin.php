<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\StorageProductTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\AvailabilityWidget\AvailabilityWidgetPluginInterface;

class AvailabilityWidgetPlugin extends AbstractWidgetPlugin implements AvailabilityWidgetPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\StorageProductTransfer $storageProductTransfer
     *
     * @return void
     */
    public function initialize(StorageProductTransfer $storageProductTransfer): void
    {
        $this->addParameter('product', $storageProductTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@AvailabilityWidget/_product-detail-page/availability.twig';
    }

}
