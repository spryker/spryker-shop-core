<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductWidget\Plugin\ProductRelationWidget;

use Generated\Shared\Transfer\StorageProductAbstractRelationTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductRelationWidget\Dependency\Plugin\ProductWidget\ProductWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductWidget\ProductWidgetFactory getFactory()
 */
class ProductWidgetPlugin extends AbstractWidgetPlugin implements ProductWidgetPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\StorageProductAbstractRelationTransfer $storageProductAbstractRelationTransfer
     *
     * @return void
     */
    public function initialize(StorageProductAbstractRelationTransfer $storageProductAbstractRelationTransfer): void
    {
        $this
            ->addParameter('idProductAbstract', $storageProductAbstractRelationTransfer->getIdProductAbstract())
            ->addParameter('productName', $storageProductAbstractRelationTransfer->getName())
            ->addParameter('imageUrl', $this->getImageUrl($storageProductAbstractRelationTransfer))
            ->addParameter('detailsUrl', $storageProductAbstractRelationTransfer->getUrl())
            ->addParameter('priceValue', $storageProductAbstractRelationTransfer->getPrice())
            ->addParameter('originalPrice', $this->getOriginalPrice($storageProductAbstractRelationTransfer))
            ->addWidgets($this->getFactory()->getProductRelationWidgetSubWidgets());
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
        return '@ProductWidget/_product-relation-widget/product.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\StorageProductAbstractRelationTransfer $storageProductAbstractRelationTransfer
     *
     * @return string|null
     */
    protected function getImageUrl(StorageProductAbstractRelationTransfer $storageProductAbstractRelationTransfer): ?string
    {
        $imageSets = $storageProductAbstractRelationTransfer->getImageSets();

        return $imageSets['default'][0]['externalUrlSmall'] ?? null;
    }

    /**
     * @param \Generated\Shared\Transfer\StorageProductAbstractRelationTransfer $storageProductAbstractRelationTransfer
     *
     * @return string|null
     */
    protected function getOriginalPrice(StorageProductAbstractRelationTransfer $storageProductAbstractRelationTransfer): ?string
    {
        $prices = $storageProductAbstractRelationTransfer->getPrices();

        return $prices['ORIGINAL'] ?? null;
    }

}
