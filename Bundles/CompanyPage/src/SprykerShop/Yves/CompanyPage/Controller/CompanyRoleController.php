<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyRoleResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use SprykerShop\Yves\CompanyPage\Form\CompanyRoleForm;
use SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CompanyRoleController extends AbstractCompanyController
{
    /**
     * @var string
     */
    public const COMPANY_ROLE_SORT_FIELD = 'id_company_role';

    /**
     * @var string
     */
    protected const SUCCESS_MESSAGE_COMPANY_ROLE_DELETE = 'company.account.company_role.delete.successful';

    /**
     * @var string
     */
    protected const SUCCESS_MESSAGE_COMPANY_ROLE_CREATE = 'company.account.company_role.create.successful';

    /**
     * @var string
     */
    protected const SUCCESS_MESSAGE_COMPANY_ROLE_UPDATE = 'company.account.company_role.update.successful';

    /**
     * @var string
     */
    protected const PARAMETER_ID_COMPANY_ROLE = 'id';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_DEFAULT_COMPANY_ROLE_DELETE = 'company.account.company_role.delete.error.default_role';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_COMPANY_ROLE_DELETE_ERROR = 'company.account.company_role.delete.error.cannot_remove';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $viewData = $this->executeIndexAction($request);

        return $this->view($viewData, [], '@CompanyPage/views/role/role.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeIndexAction(Request $request): array
    {
        $collectionTransfer = $this->createCriteriaFilterTransfer($request);
        $collectionTransfer = $this->getFactory()
            ->getCompanyRoleClient()
            ->getCompanyRoleCollection($collectionTransfer);

        return [
            'companyRoleCollection' => $collectionTransfer->getRoles(),
            'pagination' => $collectionTransfer->getPagination(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|array
     */
    public function detailsAction(Request $request)
    {
        $viewData = $this->executeDetailsAction($request);

        return $this->view($viewData, [], '@CompanyPage/views/role-detail/role-detail.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array
     */
    protected function executeDetailsAction(Request $request): array
    {
        $idCompanyRole = $request->query->getInt('id');
        $companyRoleTransfer = new CompanyRoleTransfer();
        $companyRoleTransfer->setIdCompanyRole($idCompanyRole);
        $companyRoleTransfer = $this->getFactory()
            ->getCompanyRoleClient()
            ->getCompanyRoleById($companyRoleTransfer);

        if (!$this->isCurrentCustomerRelatedToCompany($companyRoleTransfer->getFkCompany())) {
            throw new NotFoundHttpException();
        }

        $companyRolePermissions = $this->getFactory()
            ->getCompanyRoleClient()
            ->findCompanyRolePermissions($companyRoleTransfer);

        $companyUserCollection = $this->prepareCompanyUsers($companyRoleTransfer->getCompanyUserCollection());

        return [
            'companyRole' => $companyRoleTransfer,
            'permissions' => $companyRolePermissions->getPermissions(),
            'companyUserCollection' => $companyUserCollection->getCompanyUsers(),
            'currentCompanyUser' => $this->findCurrentCompanyUserTransfer(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request): RedirectResponse
    {
        $companyRoleDeleteForm = $this->getFactory()
            ->createCompanyPageFormFactory()
            ->getCompanyRoleDeleteForm(new CompanyRoleTransfer())
            ->handleRequest($request);

        if (!$companyRoleDeleteForm->isSubmitted() || !$companyRoleDeleteForm->isValid()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_COMPANY_ROLE_DELETE_ERROR);

            return $this->redirectResponseInternal(CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ROLE);
        }

        $companyRoleTransfer = $companyRoleDeleteForm->getData();

        $companyRoleTransfer = $this->getFactory()
            ->getCompanyRoleClient()
            ->getCompanyRoleById($companyRoleTransfer);

        if (!$this->isCurrentCustomerRelatedToCompany($companyRoleTransfer->getFkCompany())) {
            throw new NotFoundHttpException();
        }

        $companyRoleResponseTransfer = $this->getFactory()
            ->getCompanyRoleClient()
            ->deleteCompanyRole($companyRoleTransfer);

        if ($companyRoleResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::SUCCESS_MESSAGE_COMPANY_ROLE_DELETE);
        }

        $this->processResponseMessages($companyRoleResponseTransfer);

        return $this->redirectResponseInternal(CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ROLE);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmDeleteAction(Request $request)
    {
        $idCompanyRole = $request->query->getInt(static::PARAMETER_ID_COMPANY_ROLE);

        $companyRoleTransfer = (new CompanyRoleTransfer())
            ->setIdCompanyRole($idCompanyRole);

        $companyRoleTransfer = $this->getFactory()
            ->getCompanyRoleClient()
            ->getCompanyRoleById($companyRoleTransfer);

        if (!$this->isCurrentCustomerRelatedToCompany($companyRoleTransfer->getFkCompany())) {
            throw new NotFoundHttpException();
        }

        if ($companyRoleTransfer->getIsDefault()) {
            $this->addErrorMessage(static::ERROR_MESSAGE_DEFAULT_COMPANY_ROLE_DELETE);

            return $this->redirectResponseInternal(CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ROLE);
        }

        return $this->executeConfirmDeleteAction($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    protected function executeConfirmDeleteAction(Request $request)
    {
        $idCompanyRole = $request->query->getInt(static::PARAMETER_ID_COMPANY_ROLE);

        $companyRoleTransfer = (new CompanyRoleTransfer())
            ->setIdCompanyRole($idCompanyRole);

        $companyRoleTransfer = $this->getFactory()
            ->getCompanyRoleClient()
            ->getCompanyRoleById($companyRoleTransfer);

        if (!$this->isCurrentCustomerRelatedToCompany($companyRoleTransfer->getFkCompany())) {
            throw new NotFoundHttpException();
        }

        $companyRoleDeleteForm = $this->getFactory()
            ->createCompanyPageFormFactory()
            ->getCompanyRoleDeleteForm($companyRoleTransfer);

        $viewData = [
            'idCompanyRole' => $idCompanyRole,
            'role' => $companyRoleTransfer,
            'companyRoleDeleteForm' => $companyRoleDeleteForm->createView(),
        ];

        return $this->view($viewData, [], '@CompanyPage/views/role-delete/role-delete.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $response = $this->executeCreateAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@CompanyPage/views/role-create/role-create.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function executeCreateAction(Request $request)
    {
        $dataProvider = $this->getFactory()
            ->createCompanyPageFormFactory()
            ->createCompanyRoleDataProvider();

        $companyRoleForm = $this
            ->getFactory()
            ->createCompanyPageFormFactory()
            ->getCompanyRoleForm()
            ->handleRequest($request);

        if ($companyRoleForm->isSubmitted() === false) {
            $idCompany = $this->findCurrentCompanyUserTransfer()->getFkCompany();
            $companyRoleForm->setData($dataProvider->getData($idCompany));
        }

        if ($companyRoleForm->isSubmitted() && $companyRoleForm->isValid()) {
            $companyRoleResponseTransfer = $this->createCompanyRole($companyRoleForm->getData());
            if ($companyRoleResponseTransfer->getIsSuccessful()) {
                $this->addSuccessMessage(static::SUCCESS_MESSAGE_COMPANY_ROLE_CREATE);

                return $this->redirectResponseInternal(CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ROLE);
            }

            $this->processResponseMessages($companyRoleResponseTransfer);
        }

        return [
            'companyRoleForm' => $companyRoleForm->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function updateAction(Request $request)
    {
        $response = $this->executeUpdateAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@CompanyPage/views/role-update/role-update.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function executeUpdateAction(Request $request)
    {
        $dataProvider = $this->getFactory()
            ->createCompanyPageFormFactory()
            ->createCompanyRoleDataProvider();

        $companyRoleForm = $this
            ->getFactory()
            ->createCompanyPageFormFactory()
            ->getCompanyRoleForm()
            ->handleRequest($request);

        $idCompanyRole = $request->query->getInt(static::PARAMETER_ID_COMPANY_ROLE);

        if ($companyRoleForm->isSubmitted() === false) {
            $idCompany = $this->findCurrentCompanyUserTransfer()->getFkCompany();
            $data = $dataProvider->getData($idCompany, $idCompanyRole);

            if (!$this->isCurrentCustomerRelatedToCompany($data[CompanyRoleForm::FIELD_FK_COMPANY])) {
                throw new NotFoundHttpException();
            }
            $companyRoleForm->setData($data);
        }

        if ($companyRoleForm->isSubmitted() && $companyRoleForm->isValid()) {
            $this->updateCompanyRole($companyRoleForm->getData());
            $this->addSuccessMessage(static::SUCCESS_MESSAGE_COMPANY_ROLE_UPDATE);

            return $this->redirectResponseInternal(CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_ROLE);
        }

        return [
            'companyRoleForm' => $companyRoleForm->createView(),
            'idCompanyRole' => $idCompanyRole,
            'permissions' => $this->getSelectablePermissionsList($idCompanyRole)->getPermissions(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer
     */
    protected function createCriteriaFilterTransfer(Request $request): CompanyRoleCriteriaFilterTransfer
    {
        $criteriaFilterTransfer = new CompanyRoleCriteriaFilterTransfer();

        $criteriaFilterTransfer->setIdCompany($this->findCurrentCompanyUserTransfer()->getFkCompany());
        $criteriaFilterTransfer->setPagination($this->createPaginationTransfer($request));
        $criteriaFilterTransfer->setFilter($this->createFilterTransfer(static::COMPANY_ROLE_SORT_FIELD));

        return $criteriaFilterTransfer;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    protected function createCompanyRole(array $data): CompanyRoleResponseTransfer
    {
        $companyRoleTransfer = new CompanyRoleTransfer();
        $companyRoleTransfer->fromArray($data, true);

        return $this->getFactory()
            ->getCompanyRoleClient()
            ->createCompanyRole($companyRoleTransfer);
    }

    /**
     * @param array $data
     *
     * @return void
     */
    protected function updateCompanyRole(array $data): void
    {
        $companyRoleTransfer = new CompanyRoleTransfer();
        $companyRoleTransfer->fromArray($data, true);

        $companyRolePermissionCollection = $this->getFactory()
            ->getCompanyRoleClient()
            ->getCompanyRoleById($companyRoleTransfer)
            ->getPermissionCollection();

        $companyRoleTransfer->setPermissionCollection($companyRolePermissionCollection);

        $this->getFactory()
            ->getCompanyRoleClient()
            ->updateCompanyRole($companyRoleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserCollectionTransfer $companyUserCollection
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    protected function prepareCompanyUsers(CompanyUserCollectionTransfer $companyUserCollection)
    {
        foreach ($companyUserCollection->getCompanyUsers() as $companyUser) {
            if ($companyUser->getFkCompanyBusinessUnit()) {
                $companyBusinessUnitTransfer = $this->getFactory()
                    ->getCompanyBusinessUnitClient()
                    ->getCompanyBusinessUnitById(
                        (new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit(
                            $companyUser->getFkCompanyBusinessUnit()
                        )
                    );
                $companyUser->setCompanyBusinessUnit($companyBusinessUnitTransfer);
            }
        }

        return $companyUserCollection;
    }

    /**
     * @param int $idCompanyRole
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    protected function getSelectablePermissionsList(int $idCompanyRole): PermissionCollectionTransfer
    {
        $companyRoleTransfer = (new CompanyRoleTransfer())
            ->setIdCompanyRole($idCompanyRole);

        return $this->getFactory()
            ->getCompanyRoleClient()
            ->findNonInfrastructuralCompanyRolePermissionsByIdCompanyRole($companyRoleTransfer);
    }
}
