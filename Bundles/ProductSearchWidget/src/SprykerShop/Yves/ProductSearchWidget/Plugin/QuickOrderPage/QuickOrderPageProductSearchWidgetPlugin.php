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
     * @param string $skuInputName
     * @param string $idProductInputName
     * @param string|null $inputValue
     * @param int|null $searchResultsLimit
     *
     * @return void
     */
    public function initialize(string $index, string $skuInputName, string $idProductInputName, ?string $inputValue, ?int $searchResultsLimit = null): void
    {
        $this->addParameter('index', $index)
            ->addParameter('skuInputName', $skuInputName)
            ->addParameter('idProductInputName', $idProductInputName)
            ->addParameter('inputValue', $inputValue)
            ->addParameter('searchResultsLimit', $searchResultsLimit ?: $this->getConfig()->getSearchResultsDefaultLimit());
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
        return '@ProductSearchWidget/views/product-search-field/product-search-field.twig';
    }
}
