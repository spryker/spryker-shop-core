<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentWidget\Controller;

use Generated\Shared\Transfer\CustomerQueryTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @method \SprykerShop\Yves\AgentWidget\AgentWidgetFactory getFactory()
 */
class CustomerAutocompleteController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $queryParams = $request->query->all();

        $constraintViolationList = $this->getFactory()
            ->createCustomerAutocompleteValidator()
            ->validate($queryParams);

        $isParamsValid = $constraintViolationList->count() === 0;

        if (!$isParamsValid) {
            return $this->errorResponse($constraintViolationList);
        }

        $customerQueryTransfer = new CustomerQueryTransfer();
        $customerQueryTransfer->fromArray($queryParams, true);

        $customers = $this->getFactory()
            ->getAgentClient()
            ->findCustomersByQuery($customerQueryTransfer)
            ->toArray();

        return $customers;
    }

    /**
     * @param \Symfony\Component\Validator\ConstraintViolationListInterface $constraintViolationList
     *
     * @return array
     */
    protected function errorResponse(ConstraintViolationListInterface $constraintViolationList): array
    {
        return ['errors' => (string)$constraintViolationList];
    }
}
