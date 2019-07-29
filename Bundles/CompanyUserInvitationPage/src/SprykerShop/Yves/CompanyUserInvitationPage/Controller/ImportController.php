<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Controller;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationGetCollectionRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportResponseTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Shared\CompanyUserInvitation\CompanyUserInvitationConfig;
use SprykerShop\Yves\CompanyUserInvitationPage\Form\CompanyUserInvitationForm;
use SprykerShop\Yves\CompanyUserInvitationPage\Plugin\Provider\CompanyUserInvitationPageControllerProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method \SprykerShop\Yves\CompanyUserInvitationPage\CompanyUserInvitationPageFactory getFactory()
 */
class ImportController extends AbstractController
{
    protected const DEFAULT_COMPANY_USER_INVITATION_LIST_PAGE = 1;
    protected const DEFAULT_COMPANY_USER_INVITATION_LIST_LIMIT = 10;
    protected const DEFAULT_COMPANY_USER_INVITATION_LIST_PARAM_PAGE = 'page';
    protected const DEFAULT_COMPANY_USER_INVITATION_LIST_SORT_FIELD = 'email';
    protected const DEFAULT_COMPANY_USER_INVITATION_LIST_SORT_DIRECTION = 'ASC';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $response = $this->executeIndexAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@CompanyUserInvitationPage/views/invitation/invitation.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(Request $request)
    {
        $companyUserInvitationForm = $this->getFactory()
            ->createCompanyUserInvitationPageFormFactory()
            ->getCompanyUserInvitationForm()
            ->handleRequest($request);

        if ($companyUserInvitationForm->isSubmitted() && $companyUserInvitationForm->isValid()) {
            $companyUserInvitationImportResponseTransfer = $this->importCompanyUserInvitations($companyUserInvitationForm);
            if (!$companyUserInvitationImportResponseTransfer->getIsSuccess() && !$companyUserInvitationImportResponseTransfer->getErrors()) {
                return $this->redirectToRouteWithErrorMessage(
                    CompanyUserInvitationPageControllerProvider::ROUTE_OVERVIEW,
                    'company.user.invitation.import.error.message'
                );
            }
            if ($companyUserInvitationImportResponseTransfer->getErrors()) {
                $this->getFactory()
                    ->createImportErrorsHandler()
                    ->storeCompanyUserInvitationImportErrors($companyUserInvitationImportResponseTransfer);
                $importedWithErrors = true;
            }
        }

        $companyUserInvitationGetCollectionRequest = (new CompanyUserInvitationGetCollectionRequestTransfer())
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setCriteriaFilter($this->getCriteriaFilterTransfer($request));

        $companyUserInvitationCollection = $this->getFactory()
            ->getCompanyUserInvitationClient()
            ->getCompanyUserInvitationCollection($companyUserInvitationGetCollectionRequest);

        return [
            'form' => $companyUserInvitationForm->createView(),
            'importedWithErrors' => isset($importedWithErrors) ?? false,
            'invitations' => $companyUserInvitationCollection->getCompanyUserInvitations(),
            'pagination' => $companyUserInvitationCollection->getPagination(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getErrorsAction(): Response
    {
        $importErrors = $this->getFactory()->createImportErrorsHandler()->retrieveCompanyUserInvitationImportErrors();

        $streamedResponse = new StreamedResponse();
        $streamedResponse->setCallback(function () use ($importErrors) {
            $handler = fopen('php://output', 'w+');
            fputcsv($handler, ['Import Errors']);
            foreach ($importErrors as $importError) {
                fputcsv($handler, $importError);
            }
            fclose($handler);
        });

        $streamedResponse->setStatusCode(Response::HTTP_OK);
        $streamedResponse->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $streamedResponse->headers->set('Content-Disposition', 'attachment; filename="ImportErrors.csv"');

        return $streamedResponse->send();
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $companyUserInvitationForm
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportResponseTransfer
     */
    protected function importCompanyUserInvitations(
        FormInterface $companyUserInvitationForm
    ): CompanyUserInvitationImportResponseTransfer {
        $importFilePath = $this->getImportFilePath($companyUserInvitationForm);
        $companyUserInvitationImportRequestTransfer = new CompanyUserInvitationImportRequestTransfer();
        $companyUserInvitationImportRequestTransfer
            ->setCompanyUserInvitationCollection($this->getCompanyUserInvitationCollection($importFilePath))
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser());

        return $this->getFactory()
            ->getCompanyUserInvitationClient()
            ->importCompanyUserInvitations($companyUserInvitationImportRequestTransfer);
    }

    /**
     * @param string $importFilePath
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer
     */
    protected function getCompanyUserInvitationCollection(string $importFilePath): CompanyUserInvitationCollectionTransfer
    {
        $invitationsArray = $this->getFactory()->createCsvInvitationReader()->getData($importFilePath);

        return $this->getFactory()->createInvitationMapper()->mapInvitations($invitationsArray);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $companyUserInvitationForm
     *
     * @return string
     */
    protected function getImportFilePath(FormInterface $companyUserInvitationForm): string
    {
        /** @var \SplFileInfo $uploadedFile */
        $uploadedFile = $companyUserInvitationForm->getData()[CompanyUserInvitationForm::FIELD_INVITATIONS_LIST];

        return $uploadedFile->getPathname();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer
     */
    protected function getCriteriaFilterTransfer(Request $request): CompanyUserInvitationCriteriaFilterTransfer
    {
        $filterTransfer = (new FilterTransfer())
            ->setOrderBy(static::DEFAULT_COMPANY_USER_INVITATION_LIST_SORT_FIELD)
            ->setOrderDirection(static::DEFAULT_COMPANY_USER_INVITATION_LIST_SORT_DIRECTION);

        $companyUserInvitationCriteriaFilterTransfer = (new CompanyUserInvitationCriteriaFilterTransfer())
            ->setFkCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setFkCompany($this->companyUserTransfer->getFkCompany())
            ->setCompanyUserInvitationStatusKeyNotIn([CompanyUserInvitationConfig::INVITATION_STATUS_DELETED])
            ->setFilter($filterTransfer);

        $page = $request->query->getInt(
            static::DEFAULT_COMPANY_USER_INVITATION_LIST_PARAM_PAGE,
            static::DEFAULT_COMPANY_USER_INVITATION_LIST_PAGE
        );

        $paginationTransfer = (new PaginationTransfer())
            ->setPage($page)
            ->setMaxPerPage(static::DEFAULT_COMPANY_USER_INVITATION_LIST_LIMIT);

        $companyUserInvitationCriteriaFilterTransfer->setPagination($paginationTransfer);

        return $companyUserInvitationCriteriaFilterTransfer;
    }
}
