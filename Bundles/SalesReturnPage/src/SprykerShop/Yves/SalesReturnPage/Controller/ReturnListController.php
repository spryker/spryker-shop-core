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
    protected const PARAM_PAGE = 'page';
    protected const PARAM_PAGE_DEFAULT = 1;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function listAction(Request $request): View
    {
        $response = $this->executeListAction($request);

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
        $page = $request->query->getInt(static::PARAM_PAGE, static::PARAM_PAGE_DEFAULT);
        $returnListPerPage = $this->getFactory()->getModuleConfig()->getReturnPerPage();
        $offset = ($page - 1) * $returnListPerPage;
        $filterTransfer = (new FilterTransfer())
            ->setOffset($offset)
            ->setLimit($returnListPerPage);

        $customerReference = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer()
            ->getCustomerReference();

        return (new ReturnFilterTransfer())
            ->setCustomerReference($customerReference)
            ->setFilter($filterTransfer);
    }
}
