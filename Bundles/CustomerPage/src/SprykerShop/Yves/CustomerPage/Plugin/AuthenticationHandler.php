<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class AuthenticationHandler extends AbstractPlugin
{
    protected const MESSAGE_CUSTOMER_REGISTRATION_SUCCESS = 'customer.registration.success';
    protected const MESSAGE_CUSTOMER_REGISTRATION_EMAIL_CONFIRMATION = 'customer.registration.email_confirmation';

    /**
     * @var \SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\PreRegistrationCustomerTransferExpanderPluginInterface[]
     */
    protected $preRegistrationCustomerTransferExpanderPlugins;

    public function __construct()
    {
        $this->preRegistrationCustomerTransferExpanderPlugins = $this->getFactory()
            ->getPreRegistrationCustomerTransferExpanderPlugins();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function registerCustomer(CustomerTransfer $customerTransfer)
    {
        $this->executePreRegistrationCustomerTransferExpanderPlugins($customerTransfer);

        $customerResponseTransfer = $this
            ->getFactory()
            ->getCustomerClient()
            ->registerCustomer($customerTransfer);

        if ($customerResponseTransfer->getIsSuccess()) {
            $isDoubleOptInEnabled = $this->getFactory()->getCustomerClient()->isDoubleOptInEnabled();
            if ($isDoubleOptInEnabled) {
                $customerResponseTransfer->setSuccessMessage(static::MESSAGE_CUSTOMER_REGISTRATION_EMAIL_CONFIRMATION);
                $customerResponseTransfer->setSuccessRedirectRoute(CustomerPageRouteProviderPlugin::ROUTE_LOGIN);

                return $customerResponseTransfer;
            }

            $customerResponseTransfer->setSuccessMessage(static::MESSAGE_CUSTOMER_REGISTRATION_SUCCESS);
            $customerResponseTransfer->setSuccessRedirectRoute(CustomerPageRouteProviderPlugin::ROUTE_LOGIN);

            $this->loginAfterSuccessfulRegistration($customerResponseTransfer->getCustomerTransfer());
        }

        return $customerResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function loginAfterSuccessfulRegistration(CustomerTransfer $customerTransfer)
    {
        $token = $this->getFactory()->createUsernamePasswordToken($customerTransfer);

        $this->getFactory()
            ->createCustomerAuthenticator()
            ->authenticateCustomer($customerTransfer, $token);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function executePreRegistrationCustomerTransferExpanderPlugins(CustomerTransfer $customerTransfer): void
    {
        foreach ($this->preRegistrationCustomerTransferExpanderPlugins as $preRegistrationCustomerTransferExpanderPlugin) {
            $preRegistrationCustomerTransferExpanderPlugin->expand($customerTransfer);
        }
    }
}
