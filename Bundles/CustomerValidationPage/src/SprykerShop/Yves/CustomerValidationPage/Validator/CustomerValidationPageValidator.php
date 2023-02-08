<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerValidationPage\Validator;

use DateTime;
use Generated\Shared\Transfer\InvalidatedCustomerTransfer;
use SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToSessionClientInterface;

class CustomerValidationPageValidator implements CustomerValidationPageValidatorInterface
{
    /**
     * @var \SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToSessionClientInterface
     */
    protected CustomerValidationPageToSessionClientInterface $sessionClient;

    /**
     * @param \SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToSessionClientInterface $sessionClient
     */
    public function __construct(
        CustomerValidationPageToSessionClientInterface $sessionClient
    ) {
        $this->sessionClient = $sessionClient;
    }

    /**
     * @param \Generated\Shared\Transfer\InvalidatedCustomerTransfer $invalidatedCustomerTransfer
     *
     * @return bool
     */
    public function isCustomerValid(InvalidatedCustomerTransfer $invalidatedCustomerTransfer): bool
    {
        if ($invalidatedCustomerTransfer->getAnonymizedAt() !== null) {
            return false;
        }

        $passwordUpdatedAt = $invalidatedCustomerTransfer->getPasswordUpdatedAt();
        if ($passwordUpdatedAt !== null && $this->isPasswordUpdated($passwordUpdatedAt) === true) {
            return false;
        }

        return true;
    }

    /**
     * @param string $passwordUpdatedAt
     *
     * @return bool
     */
    protected function isPasswordUpdated(string $passwordUpdatedAt): bool
    {
        $sessionCreatedAtDateTime = (new DateTime())->setTimestamp(
            $this->sessionClient->getMetadataBag()->getCreated(),
        );

        $passwordUpdatedAtDateTime = new DateTime($passwordUpdatedAt);

        return $sessionCreatedAtDateTime <= $passwordUpdatedAtDateTime;
    }
}
