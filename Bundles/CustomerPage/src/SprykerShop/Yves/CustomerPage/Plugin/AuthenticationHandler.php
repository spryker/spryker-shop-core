<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class AuthenticationHandler extends AbstractPlugin
{
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
        $this->getSecurityContext()->setToken($token);

        $this->getFactory()
            ->getCustomerClient()
            ->setCustomer($customerTransfer);
    }

    /**
     * @return \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    protected function getSecurityContext()
    {
        $application = $this->getFactory()->getApplication();

        return $application['security.token_storage'];
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
