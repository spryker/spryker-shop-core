<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerPageControllerProvider;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends AbstractCustomerController
{
    public const MESSAGE_PROFILE_CHANGE_SUCCESS = 'customer.profile.change.success';
    public const MESSAGE_PASSWORD_CHANGE_SUCCESS = 'customer.password.change.success';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $response = $this->executeIndexAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@CustomerPage/views/profile/profile.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(Request $request)
    {
        $profileForm = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->getProfileForm()
            ->handleRequest($request);

        if ($profileForm->isSubmitted() === false) {
            $loggedInCustomerTransfer = $this->getLoggedInCustomerTransfer();

            $customerTransfer = $this
                ->getFactory()
                ->getCustomerClient()
                ->getCustomerByEmail($loggedInCustomerTransfer);

            $profileForm->setData($customerTransfer->toArray());
        }

        if ($profileForm->isSubmitted() && $profileForm->isValid() && $this->processProfileUpdate($profileForm->getData()) === true) {
            return $this->redirectResponseInternal(CustomerPageControllerProvider::ROUTE_CUSTOMER_PROFILE);
        }

        $passwordForm = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->getPasswordForm()
            ->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid() && $this->processPasswordUpdate($passwordForm->getData()) === true) {
            return $this->redirectResponseInternal(CustomerPageControllerProvider::ROUTE_CUSTOMER_PROFILE);
        }

        return [
            'profileForm' => $profileForm->createView(),
            'passwordForm' => $passwordForm->createView(),
        ];
    }

    /**
     * @param array $customerData
     *
     * @return bool
     */
    protected function processProfileUpdate(array $customerData)
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->fromArray($customerData);
        $customerTransfer->setIdCustomer($this->getLoggedInCustomerTransfer()->getIdCustomer());

        $customerResponseTransfer = $this
            ->getFactory()
            ->getCustomerClient()
            ->updateCustomer($customerTransfer);

        if ($customerResponseTransfer->getIsSuccess()) {
            $this->updateLoggedInCustomerTransfer($customerResponseTransfer->getCustomerTransfer());

            $this->addSuccessMessage(self::MESSAGE_PROFILE_CHANGE_SUCCESS);

            return true;
        }

        $this->processResponseErrors($customerResponseTransfer);

        return false;
    }

    /**
     * @param array $customerData
     *
     * @return bool
     */
    protected function processPasswordUpdate(array $customerData)
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->fromArray($customerData);

        $customerTransfer->setIdCustomer($this->getLoggedInCustomerTransfer()->getIdCustomer());

        $customerResponseTransfer = $this
            ->getFactory()
            ->getCustomerClient()
            ->updateCustomerPassword($customerTransfer);

        if ($customerResponseTransfer->getIsSuccess()) {
            $customerTransfer = $customerResponseTransfer->getCustomerTransfer();
            $this->updateLoggedInCustomerTransfer($customerTransfer);
            $token = $this->getFactory()->createUsernamePasswordToken($customerTransfer);

            $this->getFactory()
                ->createCustomerAuthenticator()
                ->authenticateCustomer($customerTransfer, $token);

            $this->addSuccessMessage(self::MESSAGE_PASSWORD_CHANGE_SUCCESS);

            return true;
        }

        $this->processResponseErrors($customerResponseTransfer);

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function updateLoggedInCustomerTransfer(CustomerTransfer $customerTransfer)
    {
        $loggedInCustomerTransfer = $this->getLoggedInCustomerTransfer();
        $loggedInCustomerTransfer->fromArray($customerTransfer->modifiedToArray());
    }
}
