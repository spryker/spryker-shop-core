<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Controller;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\SalesReturnPage\SalesReturnPageFactory getFactory()
 */
class ReturnListController extends AbstractReturnController
{
    protected const RETURN_LIST_LIMIT = 10;
    protected const RETURN_LIST_SORT_FIELD = 'created_at';
    protected const RETURN_LIST_SORT_DIRECTION = 'DESC';

    protected const PARAM_PAGE = 'page';
    protected const DEFAULT_PAGE = 1;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function listAction(Request $request): View
    {
        $response = $this->executelistAction($request);

        return $this->view(
            $response,
            [],
            '@SalesReturnPage/views/return-list/return-list.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeListAction(Request $request): array
    {
        $returnCollectionTransfer = $this->getFactory()
            ->getSalesReturnClient()
            ->getReturns($this->createReturnFilterTransfer($request));

        return [
            'pagination' => $returnCollectionTransfer->getPagination(),
            'returns' => $returnCollectionTransfer->getReturns(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ReturnFilterTransfer
     */
    protected function createReturnFilterTransfer(Request $request): ReturnFilterTransfer
    {
        return (new ReturnFilterTransfer())
            ->setCustomerReference($this->getFactory()->getCustomerClient()->getCustomer()->getCustomerReference())
            ->setFilter($this->createFilterTransfer($request));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    protected function createFilterTransfer(Request $request): FilterTransfer
    {
        $offset = ($request->query->getInt(static::PARAM_PAGE, static::DEFAULT_PAGE) - 1) * static::RETURN_LIST_LIMIT;

        return (new FilterTransfer())
            ->setOrderBy(static::RETURN_LIST_SORT_FIELD)
            ->setOrderDirection(static::RETURN_LIST_SORT_DIRECTION)
            ->setOffset($offset)
            ->setLimit(static::RETURN_LIST_LIMIT);
    }
}
