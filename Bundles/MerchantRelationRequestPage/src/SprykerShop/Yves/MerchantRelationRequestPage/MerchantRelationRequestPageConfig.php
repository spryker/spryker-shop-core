<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class MerchantRelationRequestPageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Defines parameter name for page number.
     *
     * @api
     *
     * @var string
     */
    public const PARAM_PAGE = 'page';

    /**
     * Specification:
     * - Defines default page number.
     *
     * @api
     *
     * @var int
     */
    public const DEFAULT_PAGE = 1;

    /**
     * Specification:
     * - Defines maximum items per page.
     *
     * @api
     *
     * @var int
     */
    public const DEFAULT_MAX_PER_PAGE = 10;

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_PENDING
     *
     * @var string
     */
    protected const STATUS_PENDING = 'pending';

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_REJECTED
     *
     * @var string
     */
    protected const STATUS_REJECTED = 'rejected';

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_CANCELED
     *
     * @var string
     */
    protected const STATUS_CANCELED = 'canceled';

    /**
     * Specification:
     * - Returns a list of possible statuses for merchant relation request.
     *
     * @api
     *
     * @return list<string>
     */
    public function getPossibleStatuses(): array
    {
        return [
            static::STATUS_PENDING,
            static::STATUS_REJECTED,
            static::STATUS_APPROVED,
            static::STATUS_CANCELED,
        ];
    }

    /**
     * Specification:
     * - Returns a list of request statuses that can be canceled.
     *
     * @api
     *
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::getCancelableRequestStatuses()
     *
     * @return list<string>
     */
    public function getCancelableRequestStatuses(): array
    {
        return [
            static::STATUS_PENDING,
        ];
    }

    /**
     * Specification:
     * - Defines if filtering by merchant is enabled for merchant relation request table.
     *
     * @api
     *
     * @return bool
     */
    public function isFilterByMerchantEnabledForMerchantRelationRequestTable(): bool
    {
        return true;
    }
}
