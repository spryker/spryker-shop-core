<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentWidget\Controller;

use Generated\Shared\Transfer\CustomerQueryTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * @method \SprykerShop\Yves\AgentWidget\AgentWidgetFactory getFactory()
 */
class CustomerAutocompleteController extends AbstractController
{
    protected const VIEW_PATH = '@AgentWidget/views/customer-autocomplete/customer-autocomplete.twig';

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

        return $this->renderView(static::VIEW_PATH, $customers);
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
