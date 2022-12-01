<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ShopUi\Plugin;

use Codeception\Test\Unit;
use Spryker\Service\Container\Container;
use SprykerShop\Yves\ShopUi\Plugin\Twig\NumberFormatterTwigPlugin;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * @group SprykerShop
 * @group Yves
 * @group ShopUi
 * @group Plugin
 * @group NumberFormatterTwigPluginTest
 */
class NumberFormatterTwigPluginTest extends Unit
{
    /**
     * @uses \SprykerShop\Yves\ShopUi\Filter\NumberFormatterTwigFilterFactory::FORMAT_INT_FILTER_NAME
     *
     * @var string
     */
    protected const FORMAT_INT_FILTER_NAME = 'formatInt';

    /**
     * @uses \SprykerShop\Yves\ShopUi\Filter\NumberFormatterTwigFilterFactory::FORMAT_FLOAT_FILTER_NAME
     *
     * @var string
     */
    protected const FORMAT_FLOAT_FILTER_NAME = 'formatFloat';

    /**
     * @uses \SprykerShop\Yves\ShopUi\TwigFunction\NumberFormatterTwigFunctionFactory::GET_NUMBER_FORMAT_CONFIG_FUNCTION_NAME
     *
     * @var string
     */
    protected const GET_NUMBER_FORMAT_CONFIG_FUNCTION_NAME = 'getNumberFormatConfig';

    /**
     * @return void
     */
    public function testExtendShouldAddNumberFormatterFunctions(): void
    {
        // Arrange
        $numberFormatterTwigPlugin = new NumberFormatterTwigPlugin();

        // Act
        $twig = $numberFormatterTwigPlugin->extend(new Environment(new FilesystemLoader()), new Container());

        // Assert
        $this->assertNotNull($twig->getFilter(static::FORMAT_INT_FILTER_NAME));
        $this->assertNotNull($twig->getFilter(static::FORMAT_INT_FILTER_NAME));
        $this->assertNotNull($twig->getFunction(static::GET_NUMBER_FORMAT_CONFIG_FUNCTION_NAME));
    }
}
