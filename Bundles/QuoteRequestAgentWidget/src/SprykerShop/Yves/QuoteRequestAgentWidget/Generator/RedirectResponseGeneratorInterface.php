<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentWidget\Generator;

use Symfony\Component\HttpFoundation\RedirectResponse;

interface RedirectResponseGeneratorInterface
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function generateCheckoutShipmentRedirect(): RedirectResponse;
}
