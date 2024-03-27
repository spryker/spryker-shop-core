<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Controller;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Generated\Shared\Transfer\MerchantStorageTransfer;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\MerchantRelationRequestPage\MerchantRelationRequestPageConfig getConfig()
 * @method \SprykerShop\Yves\MerchantRelationRequestPage\MerchantRelationRequestPageFactory getFactory()
 */
class MerchantRelationRequestViewController extends MerchantRelationRequestAbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request): View
    {
        return $this->view(
            $this->executeIndexAction($request),
            [],
            '@MerchantRelationRequestPage/views/merchant-relation-request-view/merchant-relation-request-view.twig',
        );
    }

    /**
     * @param string $uuid
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function detailsAction(string $uuid): View
    {
        return $this->view(
            $this->executeDetailsAction($uuid),
            [],
            '@MerchantRelationRequestPage/views/merchant-relation-request-details/merchant-relation-request-details.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    protected function executeIndexAction(Request $request): array
    {
        $merchantRelationRequestSearchForm = $this->getFactory()->getMerchantRelationRequestSearchForm();
        $merchantRelationRequestCollectionTransfer = $this->getFactory()
            ->createMerchantRelationRequestSearchHandler()
            ->handleSearchFormSubmit($request, $merchantRelationRequestSearchForm);

        return [
            'merchantRelationRequests' => $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests(),
            'pagination' => $merchantRelationRequestCollectionTransfer->getPagination(),
            'merchantRelationRequestSearchForm' => $merchantRelationRequestSearchForm->createView(),
        ];
    }

    /**
     * @param string $uuid
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array<string, mixed>
     */
    protected function executeDetailsAction(string $uuid): array
    {
        $companyUserTransfer = $this->getFactory()->createCompanyUserReader()->getCurrentCompanyUser();
        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addUuid($uuid)
            ->addIdCompany($companyUserTransfer->getFkCompanyOrFail())
            ->setWithAssigneeCompanyBusinessUnitRelations(true)
            ->setWithMerchantRelationshipRelations(true);

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        $merchantRelationRequestResponseTransfer = $this->getFactory()
            ->getMerchantRelationRequestClient()
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        if (!$merchantRelationRequestResponseTransfer->getMerchantRelationRequests()->count()) {
            throw new NotFoundHttpException();
        }

        $merchantRelationRequestTransfer = $merchantRelationRequestResponseTransfer->getMerchantRelationRequests()
            ->getIterator()
            ->current();

        $merchantStorageTransfer = $this->getFactory()
            ->createMerchantStorageReader()
            ->findMerchantByMerchantRelationRequest($merchantRelationRequestTransfer);

        if (!$merchantStorageTransfer) {
            throw new NotFoundHttpException();
        }

        return [
            'merchantRelationRequest' => $merchantRelationRequestTransfer,
            'isRequestCancellable' => $this->isRequestCancellable($merchantRelationRequestTransfer, $companyUserTransfer),
            'merchant' => $merchantStorageTransfer,
            'merchantUrl' => $this->findMerchantUrlForCurrentLocale($merchantStorageTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     *
     * @return string|null
     */
    protected function findMerchantUrlForCurrentLocale(MerchantStorageTransfer $merchantStorageTransfer): ?string
    {
        $currentLocaleName = $this->getLocale();
        foreach ($merchantStorageTransfer->getUrlCollection() as $urlTransfer) {
            if ($urlTransfer->getLocaleNameOrFail() === $currentLocaleName) {
                return $urlTransfer->getUrlOrFail();
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return bool
     */
    protected function isRequestCancellable(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): bool {
        if (!in_array($merchantRelationRequestTransfer->getStatusOrFail(), $this->getFactory()->getConfig()->getCancelableRequestStatuses(), true)) {
            return false;
        }

        return $merchantRelationRequestTransfer->getCompanyUserOrFail()->getIdCompanyUserOrFail() === $companyUserTransfer->getIdCompanyUserOrFail();
    }
}
