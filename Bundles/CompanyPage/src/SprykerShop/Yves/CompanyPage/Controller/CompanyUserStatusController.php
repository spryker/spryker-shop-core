<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use SprykerShop\Yves\CompanyPage\Plugin\Provider\CompanyPageControllerProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class CompanyUserStatusController extends AbstractCompanyController
{
    protected const ID_COMPANY_USER_PARAMETER = 'id';

    protected const SUCCESS_MESSAGE_STATUS_ENABLE_COMPANY_USER = 'company.account.company_user.status.enable.success';
    protected const SUCCESS_MESSAGE_STATUS_DISABLE_COMPANY_USER = 'company.account.company_user.status.disable.success';

    protected const ERROR_MESSAGE_STATUS_ENABLE_COMPANY_USER = 'company.account.company_user.status.enable.error';
    protected const ERROR_MESSAGE_STATUS_ENABLE_YOURSELF = 'company.account.company_user.status.enable.error.cannot_enable_yourself';

    protected const ERROR_MESSAGE_STATUS_DISABLE_COMPANY_USER = 'company.account.company_user.status.disable.error';
    protected const ERROR_MESSAGE_STATUS_DISABLE_YOURSELF = 'company.account.company_user.status.disable.error.cannot_disable_yourself';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function enableAction(Request $request): RedirectResponse
    {
        $idCompanyUser = $request->query->getInt(static::ID_COMPANY_USER_PARAMETER);

        if ($this->isCurrentCompanyUser($idCompanyUser)) {
            $this->addErrorMessage(static::ERROR_MESSAGE_STATUS_ENABLE_YOURSELF);

            return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_USER);
        }

        $companyUserResponseTransfer = $this->enableCompanyUser($idCompanyUser);
        if (!$companyUserResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage(static::ERROR_MESSAGE_STATUS_ENABLE_COMPANY_USER);

            return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_USER);
        }

        $this->addSuccessMessage(static::SUCCESS_MESSAGE_STATUS_ENABLE_COMPANY_USER);

        return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_USER);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function disableAction(Request $request): RedirectResponse
    {
        $idCompanyUser = $request->query->getInt(static::ID_COMPANY_USER_PARAMETER);

        if ($this->isCurrentCompanyUser($idCompanyUser)) {
            $this->addErrorMessage(static::ERROR_MESSAGE_STATUS_DISABLE_YOURSELF);

            return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_USER);
        }

        $companyUserResponseTransfer = $this->disableCompanyUser($idCompanyUser);
        if (!$companyUserResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage(static::ERROR_MESSAGE_STATUS_DISABLE_COMPANY_USER);

            return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_USER);
        }

        $this->addSuccessMessage(static::SUCCESS_MESSAGE_STATUS_DISABLE_COMPANY_USER);

        return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_USER);
    }

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    protected function enableCompanyUser(int $idCompanyUser): CompanyUserResponseTransfer
    {
        $companyUserTransfer = (new CompanyUserTransfer())
            ->setIdCompanyUser($idCompanyUser);

        return $this->getFactory()
            ->getCompanyUserClient()
            ->enableCompanyUser($companyUserTransfer);
    }

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    protected function disableCompanyUser(int $idCompanyUser): CompanyUserResponseTransfer
    {
        $companyUserTransfer = (new CompanyUserTransfer())
            ->setIdCompanyUser($idCompanyUser);

        return $this->getFactory()
            ->getCompanyUserClient()
            ->disableCompanyUser($companyUserTransfer);
    }

    /**
     * @param int $idCompanyUser
     *
     * @return bool
     */
    protected function isCurrentCompanyUser(int $idCompanyUser): bool
    {
        $currentCompanyUserTransfer = $this->findCurrentCompanyUserTransfer();

        if ($currentCompanyUserTransfer) {
            $currentCompanyUserTransfer->requireIdCompanyUser();

            return $currentCompanyUserTransfer->getIdCompanyUser() === $idCompanyUser;
        }

        return false;
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    protected function findCurrentCompanyUserTransfer(): ?CompanyUserTransfer
    {
        $currentCustomerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        if (!$currentCustomerTransfer) {
            return null;
        }

        return $currentCustomerTransfer->getCompanyUserTransfer();
    }
}
