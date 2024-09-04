<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Search\SearchConfig getSharedConfig()
 */
class CatalogPageConfig extends AbstractBundleConfig
{
    /**
     * @deprecated Will be removed without replacement. Use {@link \Spryker\Client\Catalog\Plugin\Config\CatalogSearchConfigBuilder::DEFAULT_ITEMS_PER_PAGE} to set the default number of catalog items per page.
     *
     * @uses \Spryker\Client\Catalog\Plugin\Config\CatalogSearchConfigBuilder::DEFAULT_ITEMS_PER_PAGE;
     *
     * @var int
     */
    protected const DEFAULT_ITEMS_PER_PAGE = 12;

    /**
     * @uses \Spryker\Client\Catalog\Plugin\Config\CatalogSearchConfigBuilder::PARAMETER_NAME_PAGE;
     *
     * @var string
     */
    protected const PARAMETER_NAME_PAGE = 'page';

    /**
     * @uses \Spryker\Client\Catalog\Plugin\Config\CatalogSearchConfigBuilder::PARAMETER_NAME_ITEMS_PER_PAGE;
     *
     * @var string
     */
    protected const PARAMETER_NAME_ITEMS_PER_PAGE = 'ipp';

    /**
     * @var int
     */
    protected const CATALOG_PAGE_LIMIT = 10000;

    /**
     * @var bool
     */
    protected const IS_MINI_CART_ASYNC_MODE_ENABLED = false;

    /**
     * Specification:
     * - Choose, how handel blacklisted category.
     * - If return value is true - category will be shown, but gray out.
     * - If return value is false - category will be hidden from customer.
     *
     * @api
     *
     * @return bool
     */
    public function isEmptyCategoryFilterValueVisible(): bool
    {
        return true;
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement. Use Search service settings to change this behavior.
     *
     * In case of SearchElasticSearch usage it can be changed by using dynamic index setting API.
     * By default `index.max_result_window` is 10000.
     *
     * PUT /{my-index}/_settings
     * {
     *   "index" : {
     *     "max_result_window": 50000
     *   }
     * }
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/indices-update-settings.html
     *
     * @return int
     */
    public function getCatalogPageLimit(): int
    {
        return static::CATALOG_PAGE_LIMIT;
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement. Use {@link \Spryker\Client\Catalog\Plugin\Config\CatalogSearchConfigBuilder::DEFAULT_ITEMS_PER_PAGE} to set the default number of catalog items per page.
     *
     * @return int
     */
    public function getDefaultItemsPerPage(): int
    {
        return static::DEFAULT_ITEMS_PER_PAGE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getParameterItemsPerPage(): string
    {
        return static::PARAMETER_NAME_ITEMS_PER_PAGE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getParameterNamePage(): string
    {
        return static::PARAMETER_NAME_PAGE;
    }

    /**
     * Specification:
     * - Enables/disables displaying of range filters without applicable products.
     * - If return value is true - empty range filters will be shown.
     * - If return value is false - empty range filters will be be hidden from customer.
     *
     * @api
     *
     * @return bool
     */
    public function isVisibleEmptyRangeFilters(): bool
    {
        return true;
    }

    /**
     * Specification:
     * - Enables async rendering of mini cart during AJAX add to cart action.
     *
     * @api
     *
     * @return bool
     */
    public function isMiniCartAsyncModeEnabled(): bool
    {
        return static::IS_MINI_CART_ASYNC_MODE_ENABLED;
    }
}
