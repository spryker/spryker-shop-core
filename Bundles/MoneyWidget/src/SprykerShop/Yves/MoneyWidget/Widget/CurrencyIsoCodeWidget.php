<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MoneyWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\MoneyWidget\MoneyWidgetFactory getFactory()
 */
class CurrencyIsoCodeWidget extends AbstractWidget
{
    public function __construct()
    {
        $this->addParameter('currencyIsoCode', $this->getCurrencyIsoCode());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CurrencyIsoCodeWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MoneyWidget/views/currency-iso-code/currency-iso-code.twig';
    }

    /**
     * @return string|null
     */
    public function getCurrencyIsoCode(): ?string
    {
        return $this->getFactory()->getCurrencyPlugin()->getCurrent()->getCode();
    }
}
