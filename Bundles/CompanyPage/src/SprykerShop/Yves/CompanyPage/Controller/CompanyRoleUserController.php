<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use ArrayObject;
use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class CompanyRoleUserController extends AbstractCompanyController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|array
     */
    public function manageAction(Request $request)
    {
        $viewData = $this->executeManageAction($request);

        return $this->view($viewData, [], '@CompanyPage/views/role-user/role-user.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeManageAction(Request $request): array
    {
        return [
            'idCompanyRole' => $request->query->getInt('id'),
            'companyUserCollection' => $this->getCompanyUserList($request),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function assignAction(Request $request): RedirectResponse
    {
        $idCompanyRole = $request->query->getInt('id-company-role');
        $idCompanyUser = $request->query->getInt('id-company-user');

        $companyRoles = $this->getFactory()
            ->getCompanyRoleClient()
            ->getCompanyRoleCollection(
                (new CompanyRoleCriteriaFilterTransfer())->setIdCompanyUser($idCompanyUser),
            )
            ->getRoles();

        $companyRoleTransfer = (new CompanyRoleTransfer())->setIdCompanyRole($idCompanyRole);
        $companyRoles->append($companyRoleTransfer);

        $this->saveCompanyUser($idCompanyUser, $companyRoles);

        return $this->redirectResponseInternal(
            CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ROLE_USER_MANAGE,
            ['id' => $idCompanyRole],
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function unassignAction(Request $request): RedirectResponse
    {
        $idCompanyRole = $request->query->getInt('id-company-role');
        $idCompanyUser = $request->query->getInt('id-company-user');

        $companyRoles = new ArrayObject();
        $companyRoleList = $this->getFactory()
            ->getCompanyRoleClient()
            ->getCompanyRoleCollection(
                (new CompanyRoleCriteriaFilterTransfer())->setIdCompanyUser($idCompanyUser),
            )
            ->getRoles();

        foreach ($companyRoleList as $companyRoleTransfer) {
            if ($companyRoleTransfer->getIdCompanyRole() !== $idCompanyRole) {
                $companyRoles->append($companyRoleTransfer);
            }
        }

        $this->saveCompanyUser($idCompanyUser, $companyRoles);

        return $this->redirectResponseInternal(
            CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ROLE_USER_MANAGE,
            ['id' => $idCompanyRole],
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function getCompanyUserList(Request $request): array
    {
        $idCompanyRole = $request->query->getInt('id');

        $companyUserList = $this->getFactory()
            ->getCompanyUserClient()
            ->getCompanyUserCollection(
                (new CompanyUserCriteriaFilterTransfer())
                    ->setIdCompany($this->findCurrentCompanyUserTransfer()->getFkCompany()),
            )
            ->getCompanyUsers();

        $companyRoleUserList = $this->getFactory()
            ->getCompanyRoleClient()
            ->getCompanyRoleById((new CompanyRoleTransfer())->setIdCompanyRole($idCompanyRole))
            ->getCompanyUserCollection()
            ->getCompanyUsers();

        $userList = [];

        foreach ($companyUserList as $companyUserTransfer) {
            $companyUserAsArray = $companyUserTransfer->toArray(true, true);
            $companyUserAsArray['idCompanyRole'] = null;

            foreach ($companyRoleUserList as $companyUserRoleTransfer) {
                if ($companyUserTransfer->getIdCompanyUser() === $companyUserRoleTransfer->getIdCompanyUser()) {
                    $companyUserAsArray['idCompanyRole'] = $idCompanyRole;
                }
            }

            $userList[] = $companyUserAsArray;
        }

        return $userList;
    }

    /**
     * @param int $idCompanyUser
     * @param \ArrayObject $companyRoles
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    protected function saveCompanyUser(int $idCompanyUser, ArrayObject $companyRoles): void
    {
        $companyUserTransfer = $this->getFactory()
            ->getCompanyUserClient()
            ->getCompanyUserById((new CompanyUserTransfer())->setIdCompanyUser($idCompanyUser));

        if (!$this->isCurrentCustomerRelatedToCompany($companyUserTransfer->getFkCompany())) {
            throw new NotFoundHttpException();
        }

        $companyRoleCollection = (new CompanyRoleCollectionTransfer())
            ->setRoles($companyRoles);

        $companyUserTransfer = (new CompanyUserTransfer())
            ->setIdCompanyUser($idCompanyUser)
            ->setCompanyRoleCollection($companyRoleCollection);

        $this->getFactory()
            ->getCompanyRoleClient()
            ->saveCompanyUser($companyUserTransfer);
    }
}
