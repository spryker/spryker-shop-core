<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartShareWidget\ResourceShare;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ResourceShareDataTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToCustomerClientInterface;

class ResourceShareRequestBuilder implements ResourceShareRequestBuilderInterface
{
    /**
     * @uses \Spryker\Shared\PersistentCartShare\PersistentCartShareConfig::RESOURCE_TYPE_QUOTE
     */
    protected const RESOURCE_TYPE_QUOTE = 'quote';
    /**
     * @uses \Spryker\Shared\PersistentCartShare\PersistentCartShareConfig::SHARE_OPTION_KEY_PREVIEW
     */
    protected const SHARE_OPTION_KEY_PREVIEW = 'PREVIEW';

    /**
     * @var \SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToCustomerClientInterface $customerClient
     */
    public function __construct(PersistentCartShareWidgetToCustomerClientInterface $customerClient)
    {
        $this->customerClient = $customerClient;
    }

    /**
     * @param int $idQuote
     * @param string $shareOption
     *
     * @return \Generated\Shared\Transfer\ResourceShareRequestTransfer
     */
    public function buildResourceShareRequest(int $idQuote, string $shareOption): ResourceShareRequestTransfer
    {
        $customerTransfer = $this->customerClient->getCustomer();
        $resourceShareDataTransfer = $this->createResolvedByShareOptionResourceShareDataTransfer($idQuote, $shareOption, $customerTransfer);

        $resourceShareTransfer = (new ResourceShareTransfer())
            ->setResourceType(static::RESOURCE_TYPE_QUOTE)
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setResourceShareData($resourceShareDataTransfer);

        $resourceShareRequestTransfer = new ResourceShareRequestTransfer();
        $resourceShareRequestTransfer->setResourceShare($resourceShareTransfer);

        return $resourceShareRequestTransfer;
    }

    /**
     * @param int $idQuote
     * @param string $shareOption
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareDataTransfer
     */
    protected function createResolvedByShareOptionResourceShareDataTransfer(int $idQuote, string $shareOption, CustomerTransfer $customerTransfer): ResourceShareDataTransfer
    {
        if ($shareOption === static::SHARE_OPTION_KEY_PREVIEW) {
            return $this->createCartPreviewResourceShareDataTransfer($idQuote);
        }

        return $this->createCartShareResourceShareDataTransfer($idQuote, $shareOption, $customerTransfer);
    }

    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\ResourceShareDataTransfer
     */
    protected function createCartPreviewResourceShareDataTransfer(int $idQuote): ResourceShareDataTransfer
    {
        return (new ResourceShareDataTransfer())
            ->setIdQuote($idQuote)
            ->setShareOption(static::SHARE_OPTION_KEY_PREVIEW);
    }

    /**
     * @param int $idQuote
     * @param string $shareOption
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareDataTransfer
     */
    protected function createCartShareResourceShareDataTransfer(int $idQuote, string $shareOption, CustomerTransfer $customerTransfer): ResourceShareDataTransfer
    {
        $customerTransfer->requireCompanyUserTransfer();
        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();

        return (new ResourceShareDataTransfer())
            ->setIdQuote($idQuote)
            ->setShareOption($shareOption)
            ->setOwnerCompanyUserId($companyUserTransfer->getIdCompanyUser())
            ->setOwnerCompanyBusinessUnitId($companyUserTransfer->getFkCompanyBusinessUnit());
    }
}
