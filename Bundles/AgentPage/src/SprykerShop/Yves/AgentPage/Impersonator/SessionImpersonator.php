<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Impersonator;

use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToCustomerClientInterface;
use SprykerShop\Yves\CustomerPage\Security\Customer;

class SessionImpersonator implements SessionImpersonatorInterface
{
    /**
     * @var \SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToCustomerClientInterface
     */
    protected AgentPageToCustomerClientInterface $customerClient;

    /**
     * @var list<\SprykerShop\Yves\AgentPageExtension\Dependency\Plugin\SessionPostImpersonationPluginInterface>
     */
    protected array $sessionPostImpersonationPlugins;

    /**
     * @param \SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToCustomerClientInterface $customerClient
     * @param list<\SprykerShop\Yves\AgentPageExtension\Dependency\Plugin\SessionPostImpersonationPluginInterface> $sessionPostImpersonationPlugins
     */
    public function __construct(
        AgentPageToCustomerClientInterface $customerClient,
        array $sessionPostImpersonationPlugins
    ) {
        $this->customerClient = $customerClient;
        $this->sessionPostImpersonationPlugins = $sessionPostImpersonationPlugins;
    }

    /**
     * @param \SprykerShop\Yves\CustomerPage\Security\Customer $customer
     *
     * @return void
     */
    public function impersonate(Customer $customer): void
    {
        $customerTransfer = $customer->getCustomerTransfer();

        $this->customerClient->setCustomer($customerTransfer);
        $this->executeSessionPostImpersonationPlugins($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function executeSessionPostImpersonationPlugins(CustomerTransfer $customerTransfer): void
    {
        foreach ($this->sessionPostImpersonationPlugins as $sessionPostImpersonationPlugin) {
            $sessionPostImpersonationPlugin->execute($customerTransfer);
        }
    }
}
