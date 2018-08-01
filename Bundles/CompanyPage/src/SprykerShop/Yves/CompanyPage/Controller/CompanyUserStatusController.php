<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerShop\Yves\CompanyPage\Plugin\Provider\CompanyPageControllerProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class CompanyUserStatusController extends AbstractController
{
    protected const ID_COMPANY_USER_PARAMETER = 'id';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function enableAction(Request $request): RedirectResponse
    {
        $idCompanyUser = $request->query->getInt(static::ID_COMPANY_USER_PARAMETER);
        $companyUserTransfer = (new CompanyUserTransfer())
            ->setIdCompanyUser($idCompanyUser);

        $this->getFactory()
            ->getCompanyUserClient()
            ->enableCompanyUser($companyUserTransfer);

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
        $companyUserTransfer = (new CompanyUserTransfer())
            ->setIdCompanyUser($idCompanyUser);

        $this->getFactory()
            ->getCompanyUserClient()
            ->disableCompanyUser($companyUserTransfer);

        return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_USER);
    }
}
