<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Shared\Customer\Code\Messages;
use SprykerShop\Yves\CustomerPage\Form\RestorePasswordForm;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerPageControllerProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class PasswordController extends AbstractCustomerController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function forgottenPasswordAction(Request $request)
    {
        $viewData = $this->executeForgottenPasswordAction($request);

        return $this->view($viewData, [], '@customerPage/views/password-forgotten/password-forgotten.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeForgottenPasswordAction(Request $request): array
    {
        $form = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->getForgottenPasswordForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customerTransfer = new CustomerTransfer();
            $customerTransfer->fromArray($form->getData());

            $customerResponseTransfer = $this->sendPasswordRestoreMail($customerTransfer);
            $this->processResponseErrors($customerResponseTransfer);

            if ($customerResponseTransfer->getIsSuccess()) {
                $this->addSuccessMessage(Messages::CUSTOMER_PASSWORD_RECOVERY_MAIL_SENT);
            }
        }

        $data = [
            'form' => $form->createView(),
        ];

        return $data;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function restorePasswordAction(Request $request)
    {
        $response = $this->executeRestorePasswordAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@customerPage/views/password-forgotten-recovery/password-forgotten-recovery.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeRestorePasswordAction(Request $request)
    {
        if ($this->isLoggedInCustomer()) {
            $this->addErrorMessage('customer.reset.password.error.already.loggedIn');

            return $this->redirectResponseInternal('home');
        }

        $form = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->getFormRestorePassword()
            ->setData([
                RestorePasswordForm::FIELD_RESTORE_PASSWORD_KEY => $request->query->get('token'),
            ])
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customerTransfer = new CustomerTransfer();
            $customerTransfer->fromArray($form->getData());

            $customerResponseTransfer = $this->getFactory()
                ->getCustomerClient()
                ->restorePassword($customerTransfer);

            if ($customerResponseTransfer->getIsSuccess()) {
                $this->getFactory()
                    ->getCustomerClient()
                    ->logout();

                $this->addSuccessMessage(Messages::CUSTOMER_PASSWORD_CHANGED);

                return $this->redirectResponseInternal(CustomerPageControllerProvider::ROUTE_LOGIN);
            }

            $this->processResponseErrors($customerResponseTransfer);
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    protected function sendPasswordRestoreMail(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->getCustomerClient()
            ->sendPasswordRestoreMail($customerTransfer);
    }
}
