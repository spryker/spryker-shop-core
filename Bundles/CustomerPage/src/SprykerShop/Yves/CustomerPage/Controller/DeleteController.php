<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Controller;

use SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin;
use Symfony\Component\HttpFoundation\Request;

class DeleteController extends AbstractCustomerController
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_DELETE_ERROR = 'customer.account.delete.error';

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
            '@CustomerPage/views/profile-delete/profile-delete.twig',
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
            $this->addErrorMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_DELETE_ERROR);

            return $this->redirectResponseInternal(CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_DELETE);
        }

        $loggedInCustomerTransfer = $this->getLoggedInCustomerTransfer();

        $this->getFactory()
            ->getCustomerClient()
            ->anonymizeCustomer($loggedInCustomerTransfer);

        return $this->redirectResponseInternal(CustomerPageRouteProviderPlugin::ROUTE_NAME_LOGOUT);
    }
}
