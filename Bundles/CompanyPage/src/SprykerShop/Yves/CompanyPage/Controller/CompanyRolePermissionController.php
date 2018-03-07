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

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class CompanyRolePermissionController extends AbstractCompanyController
{
    protected const MESSAGE_ERROR_PERMISSION_NOT_FOUND = 'Permission was not found';
    protected const MESSAGE_ERROR_PERMISSION_SAVE_FAILED = 'Permission configuration has not been updated';
    protected const MESSAGE_SUCCESSFUL_PERMISSION_SAVED = 'Permission configuration has been updated';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View
     */
    public function manageAction(Request $request)
    {
        return $this->view([
            'idCompanyRole' => $request->query->getInt('id'),
            'permissions' => $this->getPermissionsList($request),
        ]);
    }

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
            CompanyPageControllerProvider::ROUTE_COMPANY_ROLE_PERMISSION_MANAGE,
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
            CompanyPageControllerProvider::ROUTE_COMPANY_ROLE_PERMISSION_MANAGE,
            ['id' => $idCompanyRole]
        );
    }

    /**
     * @param Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View|RedirectResponse
     */
    public function configureAction(Request $request)
    {
        $idCompanyRole = $request->query->getInt('id-company-role');
        $idPermission = $request->query->getInt('id-permission');

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

        return $this->view([
            'form' => $form->createView()
        ]);
    }

    /**
     * @param CompanyRolePermissionResponseTransfer $responseTransfer
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
     * @return void
     */
    protected function saveCompanyRolePermissions(int $idCompanyRole, $permissions): void
    {
        $companyRoleTransfer = new CompanyRoleTransfer();
        $companyRoleTransfer->setIdCompanyRole($idCompanyRole);

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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function getPermissionsList(Request $request): array
    {
        $idCompanyRole = $request->query->getInt('id');
        $allPermissions = $this->getFactory()
            ->getPermissionClient()
            ->findAll()
            ->getPermissions();

        $companyRoleTransfer = new CompanyRoleTransfer();
        $companyRoleTransfer->setIdCompanyRole($idCompanyRole);

        $companyPermissions = $this->getFactory()
            ->getCompanyRoleClient()
            ->findCompanyRolePermissions($companyRoleTransfer)
            ->getPermissions();

        $permissions = [];
        foreach ($allPermissions as $permission) {
            $permissionAsArray = $permission->toArray(false, true);
            $permissionAsArray['idCompanyRole'] = null;
            foreach ($companyPermissions as $rolePermission) {
                if ($rolePermission->getKey() === $permission->getKey()) {
                    $permissionAsArray['idCompanyRole'] = $idCompanyRole;
                    break;
                }
            }
            $permissions[] = $permissionAsArray;
        }

        return $permissions;
    }
}
