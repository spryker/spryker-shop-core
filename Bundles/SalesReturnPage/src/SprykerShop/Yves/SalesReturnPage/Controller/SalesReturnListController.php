<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Controller;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\SalesReturnPage\SalesReturnPageFactory getFactory()
 */
class SalesReturnListController extends AbstractController
{
    protected const RETURN_LIST_LIMIT = 1;
    protected const RETURN_LIST_SORT_FIELD = 'created_at';
    protected const RETURN_LIST_SORT_DIRECTION = 'DESC';

    protected const PARAM_PAGE = 'page';
    protected const DEFAULT_PAGE = 1;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $viewData = $this->executeIndexAction($request);

        return $this->view(
            $viewData,
            [],
            '@SalesReturnPage/views/sales-return-list/sales-return-list.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeIndexAction(Request $request): array
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
            ->setCustomerReference($this->getLoggedInCustomerTransfer()->getCustomerReference())
            ->setFilter($this->createFilterTransfer($request));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    protected function createFilterTransfer(Request $request): FilterTransfer
    {
        $offset = ($request->query->getInt(self::PARAM_PAGE, self::DEFAULT_PAGE) - 1) * self::RETURN_LIST_LIMIT;

        return (new FilterTransfer())
            ->setOrderBy(self::RETURN_LIST_SORT_FIELD)
            ->setOrderDirection(self::RETURN_LIST_SORT_DIRECTION)
            ->setOffset($offset)
            ->setLimit(self::RETURN_LIST_LIMIT);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function getLoggedInCustomerTransfer()
    {
        if ($this->getFactory()->getCustomerClient()->isLoggedIn()) {
            return $this->getFactory()->getCustomerClient()->getCustomer();
        }

        return null;
    }
}
