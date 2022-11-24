<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Extender;

use SprykerShop\Yves\ShopUi\Filter\NumberFormatterTwigFilterFactoryInterface;
use Twig\Environment;

class NumberFormatterTwigFilterExtender implements NumberFormatterTwigFilterExtenderInterface
{
    /**
     * @var \SprykerShop\Yves\ShopUi\Filter\NumberFormatterTwigFilterFactoryInterface
     */
    protected NumberFormatterTwigFilterFactoryInterface $numberFormatterTwigFilterFactory;

    /**
     * @param \SprykerShop\Yves\ShopUi\Filter\NumberFormatterTwigFilterFactoryInterface $numberFormatterTwigFilterFactory
     */
    public function __construct(NumberFormatterTwigFilterFactoryInterface $numberFormatterTwigFilterFactory)
    {
        $this->numberFormatterTwigFilterFactory = $numberFormatterTwigFilterFactory;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig): Environment
    {
        $twig->addFilter($this->numberFormatterTwigFilterFactory->createFormatIntFilter());
        $twig->addFilter($this->numberFormatterTwigFilterFactory->createFormatFloatFilter());

        return $twig;
    }
}
