<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\TwigFunction;

use Twig\TwigFunction;

interface NumberFormatterTwigFunctionFactoryInterface
{
    /**
     * @return \Twig\TwigFunction
     */
    public function createGetNumberFormatConfigFunction(): TwigFunction;
}
