<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserAgentWidget\Controller;

use Generated\Shared\Transfer\CompanyUserCriteriaTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * @method \SprykerShop\Yves\CompanyUserAgentWidget\CompanyUserAgentWidgetFactory getFactory()
 */
class CompanyUserAutocompleteController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request): Response
    {
        $response = $this->executeIndexAction($request);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function executeIndexAction(Request $request): Response
    {
        $queryParams = $request->query->all();

        /** @var \Symfony\Component\Validator\ConstraintViolationList $constraintViolationList */
        $constraintViolationList = $this->getFactory()
            ->createCompanyUserAutocompleteValidator()
            ->validate($queryParams);

        $isParamsValid = $constraintViolationList->count() === 0;

        if (!$isParamsValid) {
            return $this->getErrorResponse($constraintViolationList);
        }

        $companyUserTransfers = $this->getFactory()
            ->getCompanyUserAgentClient()
            ->getCompanyUserCollectionByCriteria((new CompanyUserCriteriaTransfer())->fromArray($queryParams, true))
            ->getCompanyUsers()
            ->getArrayCopy();

        return $this->renderView(
            '@CompanyUserAgentWidget/views/company-user-autocomplete/company-user-autocomplete.twig',
            [
                'companyUsers' => $companyUserTransfers,
            ],
        );
    }

    /**
     * @param \Symfony\Component\Validator\ConstraintViolationList $constraintViolationList
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getErrorResponse(ConstraintViolationList $constraintViolationList): Response
    {
        $errors = [];

        foreach ($constraintViolationList as $constraintViolation) {
            $errors[] = $constraintViolation->getMessage();
        }

        return $this->renderView(
            '@CompanyUserAgentWidget/views/company-user-autocomplete/company-user-autocomplete.twig',
            [
                'errors' => $errors,
            ],
        );
    }
}
