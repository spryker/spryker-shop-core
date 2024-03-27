<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationshipPage\Controller;

use Generated\Shared\Transfer\MerchantRelationshipConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Generated\Shared\Transfer\MerchantStorageTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\MerchantRelationshipPage\MerchantRelationshipPageFactory getFactory()
 */
class DetailController extends AbstractController
{
    /**
     * @var string
     */
    protected const REQUEST_PARAM_ID_MERCHANT_RELATIONSHIP = 'id-merchant-relationship';

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
            '@MerchantRelationshipPage/views/detail/detail.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array<string, mixed>
     */
    public function executeIndexAction(Request $request): array
    {
        $merchantRelationshipConditionsTransfer = (new MerchantRelationshipConditionsTransfer())
            ->addIdCompany($this->getFactory()->createCompanyUserReader()->getCurrentCompanyUser()->getFkCompanyOrFail())
            ->addIdMerchantRelationship($request->query->getInt(static::REQUEST_PARAM_ID_MERCHANT_RELATIONSHIP))
            ->setIsActiveMerchant(true);

        $merchantRelationshipCriteriaTransfer = (new MerchantRelationshipCriteriaTransfer())
            ->setMerchantRelationshipConditions($merchantRelationshipConditionsTransfer);

        $merchantRelationshipTransfer = $this->getFactory()->getMerchantRelationshipClient()
            ->getMerchantRelationshipCollection($merchantRelationshipCriteriaTransfer)
            ->getMerchantRelationships()
            ->getIterator()
            ->current();

        if (!$merchantRelationshipTransfer) {
            throw new NotFoundHttpException();
        }

        $merchantStorageTransfer = $this->getFactory()
            ->createMerchantStorageReader()
            ->findMerchantByIdMerchant($merchantRelationshipTransfer->getFkMerchantOrFail());

        if (!$merchantStorageTransfer) {
            throw new NotFoundHttpException();
        }

        return [
            'merchantRelationship' => $merchantRelationshipTransfer,
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
}
