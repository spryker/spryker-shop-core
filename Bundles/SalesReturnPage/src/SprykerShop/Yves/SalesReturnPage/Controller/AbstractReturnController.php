<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Controller;

use Generated\Shared\Transfer\ReturnFilterTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\SalesReturnPage\SalesReturnPageFactory getFactory()
 */
abstract class AbstractReturnController extends AbstractController
{
    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        if (!$this->getFactory()->getCustomerClient()->getCustomer()) {
            throw new NotFoundHttpException(
                'Only logged in customers are allowed to access this page'
            );
        }
    }

    /**
     * @param string $returnReference
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer
     */
    protected function getReturnByReference(string $returnReference): ReturnTransfer
    {
        $returnFilterTransfer = (new ReturnFilterTransfer())
            ->setReturnReference($returnReference)
            ->setCustomerReference(
                $this->getFactory()->getCustomerClient()->getCustomer()->getCustomerReference()
            );

        $returnTransfers = $this->getFactory()
            ->getSalesReturnClient()
            ->getReturns($returnFilterTransfer)
            ->getReturns();

        if (!$returnTransfers->offsetExists(0)) {
            throw new NotFoundHttpException(sprintf(
                "Return with provided reference %s doesn't exist",
                $returnReference
            ));
        }

        return $returnTransfers->offsetGet(0);
    }
}
