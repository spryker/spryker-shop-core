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
    protected const NAME = 'ProductConcreteSearchGridWidget';
    protected const TEMPLATE = '@ProductSearchWidget/views/product-concrete-search-grid/product-concrete-search-grid.twig';
    protected const PARAM_PRODUCTS = 'products';

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer|null $productConcreteCriteriaFilterTransfer
     */
    public function __construct(?ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer = null)
    {
        if (!$productConcreteCriteriaFilterTransfer) {
            $productConcreteCriteriaFilterTransfer = new ProductConcreteCriteriaFilterTransfer();
        }

        $productViewTransfers = $this->getFactory()
            ->createProductConcreteReader()
            ->searchProductConcretesByFullText($productConcreteCriteriaFilterTransfer);

        $this->addParameter(static::PARAM_PRODUCTS, $productViewTransfers);
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
        return static::NAME;
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
        return static::TEMPLATE;
    }
}
