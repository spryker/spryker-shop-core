<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\CompanyUserInvitationPage\CompanyUserInvitationPageFactory getFactory()
 */
class AbstractModuleController extends AbstractController
{
    public const COMPANY_USER_REQUIRED = true;

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        if (static::COMPANY_USER_REQUIRED) {
            $customerTransfer = $this->getCustomer();

            if (!$customerTransfer || !$customerTransfer->getCompanyUserTransfer()) {
                throw new NotFoundHttpException("Only company users are allowed to access this page");
            }
        }
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomer(): ?CustomerTransfer
    {
        return $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();
    }

    /**
     * @param string $route
     * @param string $message
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToRouteWithSuccessMessage(string $route, string $message): RedirectResponse
    {
        $this->addSuccessMessage($message);

        return $this->redirectToRoute($route);
    }

    /**
     * @param string $route
     * @param string $message
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToRouteWithInfoMessage(string $route, string $message): RedirectResponse
    {
        $this->addInfoMessage($message);

        return $this->redirectToRoute($route);
    }

    /**
     * @param string $route
     * @param string $message
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToRouteWithErrorMessage(string $route, string $message): RedirectResponse
    {
        $this->addErrorMessage($message);

        return $this->redirectToRoute($route);
    }

    /**
     * @param string $route
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToRoute(string $route): RedirectResponse
    {
        return $this->redirectResponseInternal($route);
    }
}
