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
     * @param string $searchFieldName
     * @param string|null $searchFieldValue
     * @param int|null $searchResultLimit
     */
    public function __construct(string $index, string $searchFieldName, ?string $searchFieldValue, ?int $searchResultLimit = null)
    {
        $this->addParameter('index', $index)
            ->addParameter('searchFieldName', $searchFieldName)
            ->addParameter('searchFieldValue', $searchFieldValue)
            ->addParameter('searchResultLimit', $searchResultLimit ?: $this->getConfig()->getSearchResultLimit());
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
