<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Customer\CustomerConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerShop\Shared\CustomerPage\CustomerPageConstants;

class CustomerPageConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Zed\Customer\CustomerConfig::MIN_LENGTH_CUSTOMER_PASSWORD
     *
     * @var int
     */
    protected const MIN_LENGTH_CUSTOMER_PASSWORD = 1;

    /**
     * @var bool
     */
    protected const IS_ORDER_HISTORY_SEARCH_ENABLED = false;

    /**
     * @var bool
     */
    protected const IS_REMEMBER_ME_ENABLED = true;

    /**
     * @var int
     */
    protected const DEFAULT_ORDER_HISTORY_PER_PAGE = 10;

    /**
     * @var string
     */
    protected const DEFAULT_ORDER_HISTORY_SORT_FIELD = 'created_at';

    /**
     * @var string
     */
    protected const DEFAULT_ORDER_HISTORY_SORT_DIRECTION = 'DESC';

    /**
     * @uses \Spryker\Shared\Sales\SalesConfig::ORDER_SEARCH_TYPES
     *
     * @var array<int, string>
     */
    protected const ORDER_SEARCH_TYPES = [
        'all',
        'orderReference',
        'itemName',
        'itemSku',
    ];

    /**
     * @uses \Spryker\Zed\Customer\CustomerConfig::MAX_LENGTH_CUSTOMER_PASSWORD
     *
     * @var int
     */
    protected const MAX_LENGTH_CUSTOMER_PASSWORD = 72;

    /**
     * Specification:
     * - Regular expression to validate Customer First Name field.
     *
     * @api
     *
     * @var string
     */
    public const PATTERN_FIRST_NAME = '/^[^:\/<>]+$/';

    /**
     * Specification:
     * - Regular expression to validate Customer Last Name field.
     *
     * @api
     *
     * @var string
     */
    public const PATTERN_LAST_NAME = '/^[^:\/<>]+$/';

    /**
     * @uses \Spryker\Client\SecurityBlockerStorefrontCustomer\SecurityBlockerStorefrontCustomerConfig::SECURITY_BLOCKER_CUSTOMER_ENTITY_TYPE
     *
     * @var string
     */
    protected const CUSTOMER_SECURITY_BLOCKER_ENTITY_TYPE = 'customer';

    /**
     * @var bool
     */
    protected const CUSTOMER_SECURITY_BLOCKER_ENABLED = false;

    /**
     * Specification:
     * - Checks if the security blocker is enabled.
     * - It is disabled by default.
     *
     * @api
     *
     * @return bool
     */
    public function isCustomerSecurityBlockerEnabled(): bool
    {
        return static::CUSTOMER_SECURITY_BLOCKER_ENABLED;
    }

    /**
     * Specification:
     * - Returns the entity identifier that is used to block the password resets.
     *
     * @api
     *
     * @return string
     */
    public function getCustomerSecurityBlockerEntityType(): string
    {
        return static::CUSTOMER_SECURITY_BLOCKER_ENTITY_TYPE;
    }

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
     * @api
     *
     * @return string
     */
    public function getRememberMeSecret(): string
    {
        return $this->get(CustomerPageConstants::CUSTOMER_REMEMBER_ME_SECRET);
    }

    /**
     * @api
     *
     * @return int
     */
    public function getRememberMeLifetime(): int
    {
        return $this->get(CustomerPageConstants::CUSTOMER_REMEMBER_ME_LIFETIME);
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
        return static::IS_ORDER_HISTORY_SEARCH_ENABLED;
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
     * @return array<string>
     */
    public function getOrderSearchTypes(): array
    {
        return static::ORDER_SEARCH_TYPES;
    }

    /**
     * @api
     *
     * @uses \Spryker\Shared\Customer\CustomerConfig::isDoubleOptInEnabled()
     *
     * @return bool
     */
    public function isDoubleOptInEnabled(): bool
    {
        return false;
    }

    /**
     * Specification:
     * - Controls if the locale stub is added to the /login_check path.
     * - False means the /login_check path does not have locale.
     *
     * @api
     *
     * @deprecated Will be removed without replacement. In the future, the locale-specific URL will be used.
     *
     * @return bool
     */
    public function isLocaleInLoginCheckPath(): bool
    {
        return false;
    }

    /**
     * Specification:
     * - Returns true if the remember me functionality is enabled for the log in form.
     *
     * @api
     *
     * @return bool
     */
    public function isRememberMeEnabled(): bool
    {
        return static::IS_REMEMBER_ME_ENABLED;
    }
}
