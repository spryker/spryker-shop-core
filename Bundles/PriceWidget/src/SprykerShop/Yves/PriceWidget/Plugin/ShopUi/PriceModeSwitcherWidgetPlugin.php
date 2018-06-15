<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceWidget\Plugin\ShopUi;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShopUi\Dependency\Plugin\PriceWidget\PriceModeSwitcherWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\PriceWidget\PriceWidgetFactory getFactory()
 */
class PriceModeSwitcherWidgetPlugin extends AbstractWidgetPlugin implements PriceModeSwitcherWidgetPluginInterface
{
    /**
     * @return void
     */
    public function initialize(): void
    {
        $this
            ->addParameter('priceModes', $this->getFactory()->getPriceClient()->getPriceModes())
            ->addParameter('currentPriceMode', $this->getFactory()->getPriceClient()->getCurrentPriceMode());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@PriceWidget/views/price-switch/price-switch.twig';
    }
}
