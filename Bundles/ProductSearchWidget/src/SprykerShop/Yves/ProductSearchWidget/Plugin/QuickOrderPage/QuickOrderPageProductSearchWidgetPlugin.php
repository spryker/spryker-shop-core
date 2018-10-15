<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Plugin\QuickOrderPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\QuickOrderPage\Dependency\Plugin\ProductSearchWidget\QuickOrderPageProductSearchWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductSearchWidget\ProductSearchWidgetConfig getConfig()
 */
class QuickOrderPageProductSearchWidgetPlugin extends AbstractWidgetPlugin implements QuickOrderPageProductSearchWidgetPluginInterface
{
    /**
     * @param string $index
     * @param string $searchFieldName
     * @param string|null $searchFieldValue
     * @param int|null $searchResultLimit
     *
     * @return void
     */
    public function initialize(string $index, string $searchFieldName, ?string $searchFieldValue, ?int $searchResultLimit = null): void
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
        return '@ProductSearchWidget/views/product-concrete-search-form/product-concrete-search-form.twig';
    }
}
