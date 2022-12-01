<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Function;

use SprykerShop\Yves\ShopUi\Dependency\Service\ShopUiToUtilNumberServiceInterface;
use Twig\TwigFunction;

class NumberFormatterTwigFunctionFactory implements NumberFormatterTwigFunctionFactoryInterface
{
    /**
     * @var string
     */
    protected const GET_NUMBER_FORMAT_CONFIG_FUNCTION_NAME = 'getNumberFormatConfig';

    /**
     * @var \SprykerShop\Yves\ShopUi\Dependency\Service\ShopUiToUtilNumberServiceInterface
     */
    protected ShopUiToUtilNumberServiceInterface $utilNumberService;

    /**
     * @param \SprykerShop\Yves\ShopUi\Dependency\Service\ShopUiToUtilNumberServiceInterface $utilNumberService
     */
    public function __construct(ShopUiToUtilNumberServiceInterface $utilNumberService)
    {
        $this->utilNumberService = $utilNumberService;
    }

    /**
     * @return \Twig\TwigFunction
     */
    public function createGetNumberFormatConfigFunction(): TwigFunction
    {
        return new TwigFunction(
            static::GET_NUMBER_FORMAT_CONFIG_FUNCTION_NAME,
            function (?string $locale = null) {
                return $this->utilNumberService->getNumberFormatConfig($locale);
            },
        );
    }
}
