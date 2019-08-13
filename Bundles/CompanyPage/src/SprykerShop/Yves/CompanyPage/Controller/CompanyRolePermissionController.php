<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use ArrayObject;
use Generated\Shared\Transfer\CompanyRolePermissionResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use SprykerShop\Yves\CompanyPage\Plugin\Provider\CompanyPageControllerProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class CompanyRolePermissionController extends AbstractCompanyController
{
    protected const MESSAGE_ERROR_PERMISSION_NOT_FOUND = 'company_page.company_role_permission.permission_not_found_error';
    protected const MESSAGE_ERROR_PERMISSION_SAVE_FAILED = 'company_page.company_role_permission.permission_save_error';
    protected const MESSAGE_SUCCESSFUL_PERMISSION_SAVED = 'company_page.company_role_permission.permission_save_success';
    protected const PARAMETER_ID_COMPANY_ROLE = 'id';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function assignAction(Request $request): RedirectResponse
    {
        $idCompanyRole = $request->query->getInt('id-company-role');
        $idPermission = $request->query->getInt('id-permission');

        $newPermission = new PermissionTransfer();
        $newPermission->setIdPermission($idPermission);

        $companyRolePermissions = $this->getCompanyRolePermissions($idCompanyRole);
        $companyRolePermissions[] = $newPermission;

        $this->saveCompanyRolePermissions($idCompanyRole, $companyRolePermissions);

        return $this->redirectResponseInternal(
            CompanyPageControllerProvider::ROUTE_COMPANY_ROLE_UPDATE,
            ['id' => $idCompanyRole]
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
        $idPermission = $request->query->getInt('id-permission');

        $permissions = new ArrayObject();
        $companyRolePermissions = $this->getCompanyRolePermissions($idCompanyRole);

        foreach ($companyRolePermissions as $companyRolePermission) {
            if ($companyRolePermission->getIdPermission() !== $idPermission) {
                $permissions->append($companyRolePermission);
            }
        }

        $this->saveCompanyRolePermissions($idCompanyRole, $permissions);

        return $this->redirectResponseInternal(
            CompanyPageControllerProvider::ROUTE_COMPANY_ROLE_UPDATE,
            ['id' => $idCompanyRole]
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function configureAction(Request $request)
    {
        $idCompanyRole = $request->query->getInt('id-company-role');
        $idPermission = $request->query->getInt('id-permission');

        $companyRoleTransfer = $this->getFactory()
            ->getCompanyRoleClient()
            ->getCompanyRoleById((new CompanyRoleTransfer())->setIdCompanyRole($idCompanyRole));

        if (!$this->isCurrentCustomerRelatedToCompany($companyRoleTransfer->getFkCompany())) {
            throw new NotFoundHttpException();
        }

        $form = $this->getFactory()
            ->createCompanyPageFormFactory()
            ->getCompanyRolePermissionType($idCompanyRole, $idPermission)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyRolePermissionResponse = $this->getFactory()
                ->getCompanyRoleClient()
                ->updateCompanyRolePermission($form->getData());

            $this->generateMessagesByCompanyRolePermissionResponse($companyRolePermissionResponse);
        }

        $data = [
            'form' => $form->createView(),
        ];

        return $this->view($data, [], '@CompanyPage/views/role-permission-configure/role-permission-configure.twig');
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRolePermissionResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function generateMessagesByCompanyRolePermissionResponse(CompanyRolePermissionResponseTransfer $responseTransfer)
    {
        if ($responseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_SUCCESSFUL_PERMISSION_SAVED);

            return;
        }

        $this->addErrorMessage(static::MESSAGE_ERROR_PERMISSION_SAVE_FAILED);
    }

    /**
     * @param int $idCompanyRole
     * @param \ArrayObject|\Generated\Shared\Transfer\PermissionTransfer[] $permissions
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    protected function saveCompanyRolePermissions(int $idCompanyRole, $permissions): void
    {
        $companyRoleTransfer = new CompanyRoleTransfer();
        $companyRoleTransfer->setIdCompanyRole($idCompanyRole);

        $companyRoleTransfer = $this->getFactory()
            ->getCompanyRoleClient()
            ->getCompanyRoleById($companyRoleTransfer);

        if (!$this->isCurrentCustomerRelatedToCompany($companyRoleTransfer->getFkCompany())) {
            throw new NotFoundHttpException();
        }

        $permissionCollectionTransfer = new PermissionCollectionTransfer();
        $permissionCollectionTransfer->setPermissions($permissions);
        $companyRoleTransfer->setPermissionCollection($permissionCollectionTransfer);

        $this->getFactory()
            ->getCompanyRoleClient()
            ->updateCompanyRole($companyRoleTransfer);
    }

    /**
     * @param int $idCompanyRole
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PermissionTransfer[]
     */
    protected function getCompanyRolePermissions(int $idCompanyRole)
    {
        $companyRoleTransfer = new CompanyRoleTransfer();
        $companyRoleTransfer->setIdCompanyRole($idCompanyRole);

        $permissionCollection = $this->getFactory()
            ->getCompanyRoleClient()
            ->findCompanyRolePermissions($companyRoleTransfer);

        return $permissionCollection->getPermissions();
    }
}
