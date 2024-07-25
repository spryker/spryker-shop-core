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
use SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class CompanyRolePermissionController extends AbstractCompanyController
{
    /**
     * @var string
     */
    protected const MESSAGE_ERROR_PERMISSION_NOT_FOUND = 'company_page.company_role_permission.permission_not_found_error';

    /**
     * @var string
     */
    protected const MESSAGE_ERROR_PERMISSION_SAVE_FAILED = 'company_page.company_role_permission.permission_save_error';

    /**
     * @var string
     */
    protected const MESSAGE_SUCCESSFUL_PERMISSION_SAVED = 'company_page.company_role_permission.permission_save_success';

    /**
     * @var string
     */
    protected const PARAMETER_ID_COMPANY_ROLE = 'id';

    /**
     * @var string
     */
    protected const PERMISSION_ID = 'idPermission';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function assignAction(Request $request): RedirectResponse
    {
        $companyRolePermissionAssignForm = $this->getFactory()
            ->createCompanyPageFormFactory()
            ->getCompanyRolePermissionAssignForm()
            ->handleRequest($request);

        if (!$companyRolePermissionAssignForm->isSubmitted() || !$companyRolePermissionAssignForm->isValid()) {
            return $this->redirectWithSaveFailedError();
        }

        $idCompanyRole = $request->query->getInt('id-company-role');
        $idPermission = $request->query->getInt('id-permission');

        $allowedPermissions = $this->getSelectablePermissionsList($idCompanyRole)->getPermissions();
        if (!$this->isPermissionInAllowedPermissions($allowedPermissions, $idPermission)) {
            return $this->redirectWithSaveFailedError();
        }

        $newPermission = new PermissionTransfer();
        $newPermission->setIdPermission($idPermission);

        $companyRolePermissions = $this->getCompanyRolePermissions($idCompanyRole);
        $companyRolePermissions[] = $newPermission;

        $this->saveCompanyRolePermissions($idCompanyRole, $companyRolePermissions);

        return $this->redirectResponseInternal(
            CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ROLE_UPDATE,
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
        $companyRolePermissionUnassignForm = $this->getFactory()
            ->createCompanyPageFormFactory()
            ->getCompanyRolePermissionUnassignForm()
            ->handleRequest($request);
        if (!$companyRolePermissionUnassignForm->isSubmitted() || !$companyRolePermissionUnassignForm->isValid()) {
            $this->addErrorMessage(static::MESSAGE_ERROR_PERMISSION_SAVE_FAILED);

            return $this->redirectResponseInternal(CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ROLE);
        }

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
            CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ROLE_UPDATE,
            ['id' => $idCompanyRole],
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse|array
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
    protected function generateMessagesByCompanyRolePermissionResponse(
        CompanyRolePermissionResponseTransfer $responseTransfer
    ): void {
        if ($responseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_SUCCESSFUL_PERMISSION_SAVED);

            return;
        }

        $this->addErrorMessage(static::MESSAGE_ERROR_PERMISSION_SAVE_FAILED);
    }

    /**
     * @param int $idCompanyRole
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PermissionTransfer> $permissions
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
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PermissionTransfer>
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
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PermissionTransfer> $allowedPermissions
     * @param int $idPermission
     *
     * @return bool
     */
    protected function isPermissionInAllowedPermissions(
        ArrayObject $allowedPermissions,
        int $idPermission
    ): bool {
        foreach ($allowedPermissions as $allowedPermission) {
            if ($allowedPermission[static::PERMISSION_ID] === $idPermission) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectWithSaveFailedError(): RedirectResponse
    {
        $this->addErrorMessage(static::MESSAGE_ERROR_PERMISSION_SAVE_FAILED);

        return $this->redirectResponseInternal(CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ROLE);
    }
}
