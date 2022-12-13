<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SessionCustomerValidationPage\Updater;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SessionEntityRequestTransfer;
use SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client\SessionCustomerValidationPageToSessionClientInterface;
use SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageConfig;
use SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionSaverPluginInterface;

class CustomerSessionUpdater implements CustomerSessionUpdaterInterface
{
    /**
     * @var \SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client\SessionCustomerValidationPageToSessionClientInterface
     */
    protected SessionCustomerValidationPageToSessionClientInterface $sessionClient;

    /**
     * @var \SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionSaverPluginInterface
     */
    protected CustomerSessionSaverPluginInterface $customerSessionSaverPlugin;

    /**
     * @var \SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageConfig
     */
    protected SessionCustomerValidationPageConfig $sessionCustomerValidationPageConfig;

    /**
     * @param \SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client\SessionCustomerValidationPageToSessionClientInterface $sessionClient
     * @param \SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionSaverPluginInterface $customerSessionSaverPlugin
     * @param \SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageConfig $sessionCustomerValidationPageConfig
     */
    public function __construct(
        SessionCustomerValidationPageToSessionClientInterface $sessionClient,
        CustomerSessionSaverPluginInterface $customerSessionSaverPlugin,
        SessionCustomerValidationPageConfig $sessionCustomerValidationPageConfig
    ) {
        $this->sessionClient = $sessionClient;
        $this->customerSessionSaverPlugin = $customerSessionSaverPlugin;
        $this->sessionCustomerValidationPageConfig = $sessionCustomerValidationPageConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function update(CustomerTransfer $customerTransfer): void
    {
        if (!$customerTransfer->getIdCustomer()) {
            return;
        }

        $this->customerSessionSaverPlugin->saveSession(
            (new SessionEntityRequestTransfer())
                ->setIdEntity($customerTransfer->getIdCustomerOrFail())
                ->setIdSession($this->sessionClient->getId())
                ->setEntityType($this->sessionCustomerValidationPageConfig->getSessionEntityType()),
        );
    }
}
