<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentPage\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Spryker\Yves\Kernel\View\View;

/**
 * @method \SprykerShop\Yves\PaymentPage\PaymentPageFactory getFactory()
 */
class PaymentCancelController extends AbstractController
{
    /**
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(): View
    {
        $this->getFactory()->getCustomerClient()->markCustomerAsDirty();
        $this->getFactory()->getCartClient()->clearQuote();

        return $this->view([], [], '@PaymentPage/views/payment-cancel/index.twig');
    }
}
