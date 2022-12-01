<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Extender;

use SprykerShop\Yves\ShopUi\Filter\NumberFormatterTwigFilterFactoryInterface;
use SprykerShop\Yves\ShopUi\Function\NumberFormatterTwigFunctionFactoryInterface;
use Twig\Environment;

class NumberFormatterTwigExtender implements NumberFormatterTwigExtenderInterface
{
    /**
     * @var \SprykerShop\Yves\ShopUi\Filter\NumberFormatterTwigFilterFactoryInterface
     */
    protected NumberFormatterTwigFilterFactoryInterface $numberFormatterTwigFilterFactory;

    /**
     * @var \SprykerShop\Yves\ShopUi\Function\NumberFormatterTwigFunctionFactoryInterface
     */
    protected NumberFormatterTwigFunctionFactoryInterface $numberFormatterTwigFunctionFactory;

    /**
     * @param \SprykerShop\Yves\ShopUi\Filter\NumberFormatterTwigFilterFactoryInterface $numberFormatterTwigFilterFactory
     * @param \SprykerShop\Yves\ShopUi\Function\NumberFormatterTwigFunctionFactoryInterface $numberFormatterTwigFunctionFactory
     */
    public function __construct(
        NumberFormatterTwigFilterFactoryInterface $numberFormatterTwigFilterFactory,
        NumberFormatterTwigFunctionFactoryInterface $numberFormatterTwigFunctionFactory
    ) {
        $this->numberFormatterTwigFilterFactory = $numberFormatterTwigFilterFactory;
        $this->numberFormatterTwigFunctionFactory = $numberFormatterTwigFunctionFactory;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig): Environment
    {
        $twig = $this->addTwigFilters($twig);
        $twig = $this->addTwigFunctions($twig);

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function addTwigFilters(Environment $twig): Environment
    {
        $twig->addFilter($this->numberFormatterTwigFilterFactory->createFormatIntFilter());
        $twig->addFilter($this->numberFormatterTwigFilterFactory->createFormatFloatFilter());

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function addTwigFunctions(Environment $twig): Environment
    {
        $twig->addFunction($this->numberFormatterTwigFunctionFactory->createGetNumberFormatConfigFunction());

        return $twig;
    }
}
