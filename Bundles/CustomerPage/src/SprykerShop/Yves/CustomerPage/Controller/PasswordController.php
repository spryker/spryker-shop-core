<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
use Spryker\Shared\Customer\Code\Messages;
use SprykerShop\Shared\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\CustomerPage\Form\RestorePasswordForm;
use SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageConfig getConfig()
 */
class PasswordController extends AbstractCustomerController
{
    /**
     * @var string
     */
    protected const BLOCKER_IDENTIFIER = 'password-reset';

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
     * @return void
     */
    protected function incrementPasswordResetBlocker(Request $request): void
    {
        $config = $this->getFactory()->getConfig();
        if (!$config->isCustomerSecurityBlockerEnabled()) {
            return;
        }

        $securityCheckAuthContextTransfer = (new SecurityCheckAuthContextTransfer())
            ->setIp($request->getClientIp())
            ->setAccount(static::BLOCKER_IDENTIFIER)
            ->setType($config->getCustomerSecurityBlockerEntityType());

        $this
            ->getFactory()
            ->getSecurityBlockerClient()
            ->incrementLoginAttemptCount($securityCheckAuthContextTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isPasswordResetBlocked(Request $request): bool
    {
        $config = $this->getFactory()->getConfig();
        if (!$config->isCustomerSecurityBlockerEnabled()) {
            return false;
        }

        $securityCheckAuthContextTransfer = (new SecurityCheckAuthContextTransfer())
            ->setIp($request->getClientIp())
            ->setAccount(static::BLOCKER_IDENTIFIER)
            ->setType($config->getCustomerSecurityBlockerEntityType());

        return $this
            ->getFactory()
            ->getSecurityBlockerClient()
            ->isAccountBlocked($securityCheckAuthContextTransfer)
            ->getIsBlocked();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    protected function executeForgottenPasswordAction(Request $request): array
    {
        $form = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->getForgottenPasswordForm()
            ->handleRequest($request);

        if ($this->isPasswordResetBlocked($request)) {
            return [
                'form' => $form->createView(),
            ];
        }

        if ($form->isSubmitted()) {
            $this->incrementPasswordResetBlocker($request);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $customerTransfer = new CustomerTransfer();
            $customerTransfer->fromArray($form->getData());

            $customerResponseTransfer = $this->sendPasswordRestoreMail($customerTransfer);
            $this->processResponseErrors($customerResponseTransfer);

            if ($customerResponseTransfer->getIsSuccess()) {
                $this->addSuccessMessage(Messages::CUSTOMER_PASSWORD_RECOVERY_MAIL_SENT);
            }

            $this->getFactory()->createAuditLogger()->addPasswordResetRequestedAuditLog();
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    protected function executeRestorePasswordAction(Request $request): RedirectResponse|array
    {
        if ($request->query->get(CustomerPageConfig::URL_PARAM_LOCALE)) {
            return $this->redirectWithLocale(
                CustomerPageRouteProviderPlugin::ROUTE_NAME_PASSWORD_RESTORE,
                (string)$request->query->get(CustomerPageConfig::URL_PARAM_LOCALE),
                ['token' => $request->query->get('token')],
            );
        }

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

                $this->getFactory()->createAuditLogger()->addPasswordUpdatedAfterResetAuditLog($customerResponseTransfer);

                return $this->redirectResponseInternal(CustomerPageRouteProviderPlugin::ROUTE_NAME_LOGIN);
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
