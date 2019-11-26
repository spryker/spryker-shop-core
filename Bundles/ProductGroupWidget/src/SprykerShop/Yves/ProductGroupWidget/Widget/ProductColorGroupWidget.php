<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductGroupWidget\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;

/**
 * @method \SprykerShop\Yves\ProductGroupWidget\ProductGroupWidgetFactory getFactory()
 */
class ProductColorGroupWidget extends ProductGroupWidget
{
    /**
     * @var array|\SprykerShop\Yves\ProductColorGroupWidgetExtension\Dependency\Plugin\ProductViewExpanderPluginInterface[]
     */
    protected $productViewExpanderPlugins;

    /**
     * @param int $idProductAbstract
     */
    public function __construct(int $idProductAbstract)
    {
        $this->productViewExpanderPlugins = $this->getFactory()->getProductViewExpanderPlugins();

        parent::__construct($idProductAbstract);
    }

    /**
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductColorGroupWidget';
    }

    /**
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductGroupWidget/views/product-group/product-group.twig';
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function getProductGroups(int $idProductAbstract): array
    {
        $productViewTransfers = parent::getProductGroups($idProductAbstract);

        return $this->getExpandedProductViewTransfers($productViewTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productViewTransfers
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function getExpandedProductViewTransfers(array $productViewTransfers): array
    {
        foreach ($productViewTransfers as $productViewTransfer) {
            $productViewTransfer = $this->getExpandedProductViewTransfer($productViewTransfer);
        }

        return $productViewTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function getExpandedProductViewTransfer(ProductViewTransfer $productViewTransfer): ProductViewTransfer
    {
        foreach ($this->productViewExpanderPlugins as $productViewExpanderPlugin) {
            $productViewTransfer = $productViewExpanderPlugin->expand($productViewTransfer);
        }

        return $productViewTransfer;
    }
}
