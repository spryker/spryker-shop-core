<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Controller;

use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerPageControllerProvider;
use Symfony\Component\HttpFoundation\Request;

class DeleteController extends AbstractCustomerController
{
    /**
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction()
    {
        $customerDeleteForm = $this->getFactory()
            ->createCustomerFormFactory()
            ->getCustomerDeleteForm();

        return $this->view(
            ['customerDeleteForm' => $customerDeleteForm->createView()],
            [],
            '@CustomerPage/views/profile-delete/profile-delete.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmAction(Request $request)
    {
        $customerDeleteForm = $this->getFactory()
            ->createCustomerFormFactory()
            ->getCustomerDeleteForm()
            ->handleRequest($request);

        if (!$customerDeleteForm->isSubmitted() || !$customerDeleteForm->isValid()) {
            $this->addErrorMessage('customer.account.delete.error');

            return $this->redirectResponseInternal(CustomerPageControllerProvider::ROUTE_CUSTOMER_DELETE);
        }

        $loggedInCustomerTransfer = $this->getLoggedInCustomerTransfer();

        $this->getFactory()
            ->getCustomerClient()
            ->anonymizeCustomer($loggedInCustomerTransfer);

        return $this->redirectResponseInternal(CustomerPageControllerProvider::ROUTE_LOGOUT);
    }
}
