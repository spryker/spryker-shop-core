<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CustomerPage\Controller;

use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerPageControllerProvider;

class DeleteController extends AbstractCustomerController
{
    /**
     * @return void
     */
    public function indexAction()
    {
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmAction()
    {
        $loggedInCustomerTransfer = $this->getLoggedInCustomerTransfer();

        $this->getFactory()
            ->getCustomerClient()
            ->anonymizeCustomer($loggedInCustomerTransfer);

        return $this->redirectResponseInternal(CustomerPageControllerProvider::ROUTE_LOGOUT);
    }
}
