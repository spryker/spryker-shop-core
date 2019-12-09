<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Widget;

use Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductSearchWidget\ProductSearchWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ProductSearchWidget\ProductSearchWidgetConfig getConfig()
 */
class ProductConcreteSearchGridWidget extends AbstractWidget
{
    protected const PARAMETER_PRODUCTS = 'products';

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer|null $productConcreteCriteriaFilterTransfer
     */
    public function __construct(?ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer = null)
    {
        if (!$productConcreteCriteriaFilterTransfer) {
            $productConcreteCriteriaFilterTransfer = new ProductConcreteCriteriaFilterTransfer();
        }

        $this->addProductsParameter($productConcreteCriteriaFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductConcreteSearchGridWidget';
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductSearchWidget/views/product-concrete-search-list/product-concrete-search-list.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer
     *
     * @return void
     */
    protected function addProductsParameter(ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer): void
    {
        $productViewTransfers = $this->getFactory()
            ->createProductConcreteReader()
            ->searchProductConcretesByFullText($productConcreteCriteriaFilterTransfer);

        $this->addParameter(static::PARAMETER_PRODUCTS, $productViewTransfers);
    }
}
