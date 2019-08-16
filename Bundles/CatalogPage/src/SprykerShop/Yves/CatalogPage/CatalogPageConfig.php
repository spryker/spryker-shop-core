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
     * @uses \Spryker\Client\Catalog\Plugin\Config\CatalogSearchConfigBuilder::DEFAULT_ITEMS_PER_PAGE;
     */
    protected const DEFAULT_ITEMS_PER_PAGE = 12;

    /**
     * @uses \Spryker\Client\Catalog\Plugin\Config\CatalogSearchConfigBuilder::PARAMETER_NAME_PAGE;
     */
    protected const PARAMETER_NAME_PAGE = 'page';

    /**
     * @uses \Spryker\Client\Catalog\Plugin\Config\CatalogSearchConfigBuilder::PARAMETER_NAME_ITEMS_PER_PAGE;
     */
    protected const PARAMETER_NAME_ITEMS_PER_PAGE = 'ipp';

    protected const CATALOG_PAGE_LIMIT = 10000;

    /**
     * Specification:
     * - Choose, how handel blacklisted category.
     * - If return value is true - category will be shown, but gray out.
     * - If return value is false - category will be hidden from customer.
     *
     * @return bool
     */
    public function isEmptyCategoryFilterValueVisible(): bool
    {
        return true;
    }

    /**
     * @return int
     */
    public function getCatalogPageLimit(): int
    {
        return static::CATALOG_PAGE_LIMIT;
    }

    /**
     * @return int
     */
    public function getDefaultItemsPerPage(): int
    {
        return static::DEFAULT_ITEMS_PER_PAGE;
    }

    /**
     * @return string
     */
    public function getParameterItemsPerPage(): string
    {
        return static::PARAMETER_NAME_ITEMS_PER_PAGE;
    }

    /**
     * @return string
     */
    public function getParameterNamePage(): string
    {
        return static::PARAMETER_NAME_PAGE;
    }
}
