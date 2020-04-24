<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Controller;

use Spryker\Client\CompanyUserInvitation\Plugin\ManageCompanyUserInvitationPermissionPlugin;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController as SprykerAbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\CompanyUserInvitationPage\CompanyUserInvitationPageFactory getFactory()
 */
class AbstractController extends SprykerAbstractController
{
    use PermissionAwareTrait;

    public const COMPANY_USER_WITH_PERMISSIONS_REQUIRED = true;

    /**
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $companyUserTransfer;

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (static::COMPANY_USER_WITH_PERMISSIONS_REQUIRED) {
            if (!$customerTransfer || !$customerTransfer->getCompanyUserTransfer()) {
                throw new NotFoundHttpException('Only company users are allowed to access this page');
            }

            if (!$this->can(ManageCompanyUserInvitationPermissionPlugin::KEY, $customerTransfer->getCompanyUserTransfer()->getIdCompanyUser())) {
                throw new NotFoundHttpException("You don't have the permission to access this page");
            }

            $this->companyUserTransfer = $customerTransfer->getCompanyUserTransfer();
        }
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
