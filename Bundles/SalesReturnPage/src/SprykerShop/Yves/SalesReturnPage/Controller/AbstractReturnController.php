<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Controller;

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
}
