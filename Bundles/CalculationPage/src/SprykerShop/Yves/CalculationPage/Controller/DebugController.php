<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CalculationPage\Controller;

use Spryker\Shared\Config\Environment;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Client\Calculation\CalculationClientInterface getClient()
 * @method \SprykerShop\Yves\CalculationPage\CalculationPageFactory getFactory()
 */
class DebugController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array
     */
    public function cartAction(Request $request)
    {
        if (!Environment::isDevelopment()) {
            throw new NotFoundHttpException();
        }

        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $quoteTransfer = $this->getFactory()->getCalculationClient()->recalculate($quoteTransfer);

        return [
            'quote' => $quoteTransfer,
        ];
    }
}
