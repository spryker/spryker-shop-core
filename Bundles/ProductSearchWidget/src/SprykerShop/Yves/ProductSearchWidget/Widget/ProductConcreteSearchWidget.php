<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductSearchWidget\ProductSearchWidgetConfig getConfig()
 */
class ProductConcreteSearchWidget extends AbstractWidget
{
    protected const NAME = 'ProductConcreteSearchWidget';

    /**
     * @param string $index
     * @param string $skuFieldName
     * @param string|null $skuFieldValue
     * @param string|null $searchFieldValue
     * @param int|null $searchResultLimit
     */
    public function __construct(string $index, string $skuFieldName, ?string $skuFieldValue = null, ?string $searchFieldValue = null, ?int $searchResultLimit = null)
    {
        $this->addParameter('index', $index)
            ->addParameter('skuFieldName', $skuFieldName)
            ->addParameter('skuFieldValue', $skuFieldValue)
            ->addParameter('searchFieldValue', $searchFieldValue)
            ->addParameter('searchResultLimit', $searchResultLimit ?: $this->getConfig()->getSearchResultLimit());
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
        return '@ProductSearchWidget/views/product-concrete-search/product-concrete-search-form.twig';
    }
}
