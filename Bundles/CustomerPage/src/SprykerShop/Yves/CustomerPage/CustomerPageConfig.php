<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Customer\CustomerConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class CustomerPageConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Zed\Customer\CustomerConfig::MIN_LENGTH_CUSTOMER_PASSWORD
     */
    protected const MIN_LENGTH_CUSTOMER_PASSWORD = 1;

    protected const IS_ORDER_SEARCH_ENABLED = false;

    protected const DEFAULT_ORDER_HISTORY_PER_PAGE = 10;
    protected const DEFAULT_ORDER_HISTORY_SORT_FIELD = 'created_at';
    protected const DEFAULT_ORDER_HISTORY_SORT_DIRECTION = 'DESC';

    protected const ORDER_SEARCH_PER_PAGE = 10;

    /**
     * @uses \Spryker\Zed\Customer\CustomerConfig::MAX_LENGTH_CUSTOMER_PASSWORD
     */
    protected const MAX_LENGTH_CUSTOMER_PASSWORD = 72;

    /**
     * @api
     *
     * @return string
     */
    public function getYvesHost()
    {
        return $this->get(ApplicationConstants::HOST_YVES);
    }

    /**
     * @api
     *
     * @return int
     */
    public function getCustomerPasswordMinLength(): int
    {
        return static::MIN_LENGTH_CUSTOMER_PASSWORD;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getCustomerPasswordMaxLength(): int
    {
        return static::MAX_LENGTH_CUSTOMER_PASSWORD;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getAnonymousPattern(): string
    {
        return $this->get(CustomerConstants::CUSTOMER_ANONYMOUS_PATTERN);
    }

    /**
     * URL to redirect to in case of authentication failure if login form is placed on non-login page (e.g. header or register page).
     * URL could be relative or absolute with domain defined in CustomerPageConfig::getYvesHost().
     * If null it will use referer URL.
     * If referer URL is not available, it will redirect to home page.
     *
     * @api
     *
     * @return string|null
     */
    public function loginFailureRedirectUrl(): ?string
    {
        return null;
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isOrderSearchEnabled(): bool
    {
        return static::IS_ORDER_SEARCH_ENABLED;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getDefaultOrderHistoryPerPage(): int
    {
        return static::DEFAULT_ORDER_HISTORY_PER_PAGE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultOrderHistorySortField(): string
    {
        return static::DEFAULT_ORDER_HISTORY_SORT_FIELD;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultOrderHistorySortDirection(): string
    {
        return static::DEFAULT_ORDER_HISTORY_SORT_DIRECTION;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getOrderSearchPerPage(): int
    {
        return static::ORDER_SEARCH_PER_PAGE;
    }
}
