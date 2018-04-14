<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Controller;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Shared\CompanyUserInvitation\CompanyUserInvitationConstants;
use SprykerShop\Shared\CompanyUserInvitationPage\CompanyUserInvitationPageConstants;
use SprykerShop\Yves\CompanyUserInvitationPage\Form\CompanyUserInvitationForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method \SprykerShop\Yves\CompanyUserInvitationPage\CompanyUserInvitationPageFactory getFactory()
 */
class ImportController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request)
    {
        $companyUserInvitationForm = $this->getFactory()
            ->createCompanyUserInvitationPageFormFactory()
            ->getCompanyUserInvitationForm()
            ->handleRequest($request);

        if ($companyUserInvitationForm->isSubmitted() && $companyUserInvitationForm->isValid()) {
            $companyUserInvitationImportResultTransfer = $this->importCompanyUserInvitations($companyUserInvitationForm);
            if ($companyUserInvitationImportResultTransfer->getErrors()) {
                $this->getFactory()
                    ->createImportErrorsHandler()
                    ->storeCompanyUserInvitationImportErrors($companyUserInvitationImportResultTransfer);
                $importedWithErrors = true;
            }
        }

        $companyUserInvitationCollection = $this->getFactory()
            ->getCompanyUserInvitationClient()
            ->getCompanyUserInvitationCollection(
                $this->getCriteriaFilterTransfer($request)
            );

        return $this->view([
            'form' => $companyUserInvitationForm->createView(),
            'importedWithErrors' => isset($importedWithErrors) ?? false,
            'invitations' => $companyUserInvitationCollection->getInvitations(),
            'pagination' => $companyUserInvitationCollection->getPagination(),
        ], [], '@CompanyUserInvitationPage/views/invitation/invitation.twig');
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
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer
     */
    protected function importCompanyUserInvitations(
        FormInterface $companyUserInvitationForm
    ): CompanyUserInvitationImportResultTransfer {
        $importFilePath = $this->getImportFilePath($companyUserInvitationForm);
        $invitationCollectionTransfer = $this->getCompanyUserInvitationCollection($importFilePath);

        return $this->getFactory()
            ->getCompanyUserInvitationClient()
            ->importCompanyUserInvitations($invitationCollectionTransfer);
    }

    /**
     * @param string $importFilePath
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer
     */
    protected function getCompanyUserInvitationCollection(string $importFilePath): CompanyUserInvitationCollectionTransfer
    {
        $invitationsArray = $this->getFactory()->createCsvInvitationReader($importFilePath)->getInvitations();

        return $this->getFactory()->createInvitationMapper()->mapInvitations($invitationsArray);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $companyUserInvitationForm
     *
     * @return string
     */
    protected function getImportFilePath(FormInterface $companyUserInvitationForm): string
    {
        return $companyUserInvitationForm
            ->getData()[CompanyUserInvitationForm::FIELD_INVITATIONS_LIST]
            ->getPathname();
    }

    /**
     * @return int
     */
    protected function getIdCompanyUser(): int
    {
        $customer = $this->getFactory()->getCustomerClient()->getCustomer();
        $customer->requireCompanyUserTransfer();
        $customer->getCompanyUserTransfer()->requireIdCompanyUser();

        return $customer->getCompanyUserTransfer()->getIdCompanyUser();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer
     */
    protected function getCriteriaFilterTransfer(Request $request): CompanyUserInvitationCriteriaFilterTransfer
    {
        $filterTransfer = (new FilterTransfer())
            ->setOrderBy(CompanyUserInvitationPageConstants::DEFAULT_COMPANY_USER_INVITATION_LIST_SORT_FIELD)
            ->setOrderDirection(CompanyUserInvitationPageConstants::DEFAULT_COMPANY_USER_INVITATION_LIST_SORT_DIRECTION);

        $companyUserInvitationCriteriaFilterTransfer = (new CompanyUserInvitationCriteriaFilterTransfer())
            ->setFkCompanyUser($this->getIdCompanyUser())
            ->setCompanyUserInvitationStatusKeyNotIn([CompanyUserInvitationConstants::INVITATION_STATUS_DELETED])
            ->setFilter($filterTransfer);

        $page = $request->query->getInt(
            CompanyUserInvitationPageConstants::DEFAULT_COMPANY_USER_INVITATION_LIST_PARAM_PAGE,
            CompanyUserInvitationPageConstants::DEFAULT_COMPANY_USER_INVITATION_LIST_PAGE
        );

        $paginationTransfer = (new PaginationTransfer())
            ->setPage($page)
            ->setMaxPerPage(CompanyUserInvitationPageConstants::DEFAULT_COMPANY_USER_INVITATION_LIST_LIMIT);

        $companyUserInvitationCriteriaFilterTransfer->setPagination($paginationTransfer);

        return $companyUserInvitationCriteriaFilterTransfer;
    }
}
