<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CalculationPage\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Client\Calculation\CalculationClientInterface getClient()
 * @method \SprykerShop\Yves\CalculationPage\CalculationPageFactory getFactory()
 */
class DebugController extends AbstractController
{
    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array
     */
    public function cartAction()
    {
        return [
            'quote' => $this->getFactory()->createQuoteReader()->getRecalculatedQuote(),
        ];
    }
}
