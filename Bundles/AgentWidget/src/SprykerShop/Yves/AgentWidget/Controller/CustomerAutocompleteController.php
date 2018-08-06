<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentWidget\Controller;

use Generated\Shared\Transfer\CustomerQueryTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @method \SprykerShop\Yves\AgentWidget\AgentWidgetFactory getFactory()
 */
class CustomerAutocompleteController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request): Response
    {
        $queryParams = $request->query->all();

        $constraintViolationList = $this->getFactory()
            ->createCustomerAutocompleteValidator()
            ->validate($queryParams);

        $isParamsValid = $constraintViolationList->count() === 0;

        if (!$isParamsValid) {
            return $this->errorJsonResponse($constraintViolationList);
        }

        $customerQueryTransfer = new CustomerQueryTransfer();
        $customerQueryTransfer->fromArray($queryParams, true);

        $customers = $this->getFactory()
            ->getAgentClient()
            ->findCustomersByQuery($customerQueryTransfer)
            ->toArray();

        return $this->jsonResponse($customers);
    }

    /**
     * @param \Symfony\Component\Validator\ConstraintViolationListInterface $constraintViolationList
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function errorJsonResponse(ConstraintViolationListInterface $constraintViolationList): Response
    {
        return $this
            ->jsonResponse(['errors' => (string)$constraintViolationList])
            ->setStatusCode(422);
    }
}
