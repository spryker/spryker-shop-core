<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
        return $this->view([], [], '@CustomerPage/views/profile-delete/profile-delete.twig');
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
