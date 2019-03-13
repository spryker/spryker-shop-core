<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestWidget\Controller;

use Generated\Shared\Transfer\CompanyUserQueryTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * @method \SprykerShop\Yves\AgentQuoteRequestWidget\AgentQuoteRequestWidgetFactory getFactory()
 */
class AgentCompanyUserAutocompleteController extends AbstractController
{
    protected const VIEW_PATH = '@AgentQuoteRequestWidget/views/company-user-autocomplete/company-user-autocomplete.twig';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request): Response
    {
        $queryParams = $request->query->all();

        /** @var \Symfony\Component\Validator\ConstraintViolationList $constraintViolationList */
        $constraintViolationList = $this->getFactory()
            ->createCompanyUserAutocompleteValidator()
            ->validate($queryParams);

        $isParamsValid = $constraintViolationList->count() === 0;

        if (!$isParamsValid) {
            return $this->errorResponse($constraintViolationList);
        }

        $companyUsers = $this->getFactory()
            ->getAgentQuoteRequestClient()
            ->findCompanyUsersByQuery((new CompanyUserQueryTransfer())->fromArray($queryParams, true))
            ->getCompanyUsers()
            ->getArrayCopy();

        return $this->renderView(static::VIEW_PATH, ['companyUsers' => $companyUsers]);
    }

    /**
     * @param \Symfony\Component\Validator\ConstraintViolationList $constraintViolationList
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function errorResponse(ConstraintViolationList $constraintViolationList): Response
    {
        return $this->renderView(static::VIEW_PATH, ['errors' => (string)$constraintViolationList]);
    }
}
