<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantOpeningHoursWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\MerchantOpeningHoursWidget\MerchantOpeningHoursWidgetFactory getFactory()
 */
class MerchantOpeningHoursWidget extends AbstractWidget
{
    protected const PARAMETER_MERCHANT_OPENING_HOURS = 'merchantOpeningHours';

    /**
     * @param int $idMerchant
     */
    public function __construct(int $idMerchant)
    {
        $this->addMerchantOpeningHoursParameter($idMerchant);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'MerchantOpeningHoursWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MerchantOpeningHoursWidget/views/merchant-opening-hours-widget/merchant-opening-hours-widget.twig';
    }

    /**
     * @param int $idMerchant
     *
     * @return void
     */
    protected function addMerchantOpeningHoursParameter(int $idMerchant)
    {
        $this->addParameter(
            static::PARAMETER_MERCHANT_OPENING_HOURS,
            $this->getFactory()->getMerchantOpeningHoursStoregeClient()->findMerchantOpeningHoursByIdMerchant($idMerchant)
        );
    }
}
