<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Form\Handler;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToMerchantRelationRequestClientInterface;
use SprykerShop\Yves\MerchantRelationRequestPage\Reader\CompanyUserReaderInterface;

class MerchantRelationRequestHandler implements MerchantRelationRequestHandlerInterface
{
    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_PENDING
     *
     * @var string
     */
    protected const STATUS_PENDING = 'pending';

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_CANCELED
     *
     * @var string
     */
    protected const STATUS_CANCELED = 'canceled';

    /**
     * @var \SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToMerchantRelationRequestClientInterface
     */
    protected MerchantRelationRequestPageToMerchantRelationRequestClientInterface $merchantRelationRequestClient;

    /**
     * @var \SprykerShop\Yves\MerchantRelationRequestPage\Reader\CompanyUserReaderInterface
     */
    protected CompanyUserReaderInterface $companyUserReader;

    /**
     * @param \SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToMerchantRelationRequestClientInterface $merchantRelationRequestClient
     * @param \SprykerShop\Yves\MerchantRelationRequestPage\Reader\CompanyUserReaderInterface $companyUserReader
     */
    public function __construct(
        MerchantRelationRequestPageToMerchantRelationRequestClientInterface $merchantRelationRequestClient,
        CompanyUserReaderInterface $companyUserReader
    ) {
        $this->merchantRelationRequestClient = $merchantRelationRequestClient;
        $this->companyUserReader = $companyUserReader;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer
     */
    public function createMerchantRelationRequest(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): MerchantRelationRequestCollectionResponseTransfer {
        $merchantRelationRequestTransfer->setStatus(static::STATUS_PENDING);
        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantRelationRequest($merchantRelationRequestTransfer);

        return $this->merchantRelationRequestClient
            ->createMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);
    }

    /**
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer
     */
    public function cancelMerchantRelationRequest(string $uuid): MerchantRelationRequestCollectionResponseTransfer
    {
        $merchantRelationRequestTransfer = (new MerchantRelationRequestTransfer())
            ->setCompanyUser($this->companyUserReader->getCurrentCompanyUser())
            ->setStatus(static::STATUS_CANCELED)
            ->setUuid($uuid);

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addMerchantRelationRequest($merchantRelationRequestTransfer);

        return $this->merchantRelationRequestClient
            ->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);
    }
}
