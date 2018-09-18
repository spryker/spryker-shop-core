<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\PriceWidget\PriceWidgetFactory getFactory()
 */
class PriceModeSwitcherWidget extends AbstractWidget
{
    public function __construct()
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
        return 'PriceModeSwitcherWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@PriceWidget/views/price-switch/price-switch.twig';
    }
}
