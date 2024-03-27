<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestWidget\Checker;

use Generated\Shared\Transfer\MerchantStorageCriteriaTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerShop\Yves\MerchantRelationRequestWidget\Dependency\Client\MerchantRelationRequestWidgetToMerchantStorageClientInterface;

class MerchantRelationRequestChecker implements MerchantRelationRequestCheckerInterface
{
    use PermissionAwareTrait;

    /**
     * @uses \Spryker\Client\MerchantRelationRequest\Plugin\Permission\CreateMerchantRelationRequestPermissionPlugin::KEY
     *
     * @var string
     */
    protected const PERMISSION_KEY_CREATE_MERCHANT_RELATION_REQUEST = 'CreateMerchantRelationRequestPermissionPlugin';

    /**
     * @var \SprykerShop\Yves\MerchantRelationRequestWidget\Dependency\Client\MerchantRelationRequestWidgetToMerchantStorageClientInterface
     */
    protected MerchantRelationRequestWidgetToMerchantStorageClientInterface $merchantStorageClient;

    /**
     * @param \SprykerShop\Yves\MerchantRelationRequestWidget\Dependency\Client\MerchantRelationRequestWidgetToMerchantStorageClientInterface $merchantStorageClient
     */
    public function __construct(
        MerchantRelationRequestWidgetToMerchantStorageClientInterface $merchantStorageClient
    ) {
        $this->merchantStorageClient = $merchantStorageClient;
    }

    /**
     * @param string $merchantReference
     *
     * @return bool
     */
    public function isMerchantApplicableForRequest(string $merchantReference): bool
    {
        if (!$this->can(static::PERMISSION_KEY_CREATE_MERCHANT_RELATION_REQUEST)) {
            return false;
        }

        $merchantStorageTransfers = $this->merchantStorageClient
            ->get((new MerchantStorageCriteriaTransfer())->addMerchantReference($merchantReference));

        $merchantStorageTransfer = array_shift($merchantStorageTransfers);

        return $merchantStorageTransfer && $merchantStorageTransfer->getIsOpenForRelationRequest();
    }
}
