<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

class ProductOfferServicePointAvailabilityDisplayWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const NAME = 'ProductOfferServicePointAvailabilityDisplayWidget';

    /**
     * @var string
     */
    protected const PARAMETER_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_MESSAGE = 'productOfferServicePointAvailabilityMessage';

    /**
     * @param string $productOfferServicePointAvailabilityMessage
     */
    public function __construct(string $productOfferServicePointAvailabilityMessage)
    {
        $this->addProductOfferServicePointAvailabilityMessageParameter($productOfferServicePointAvailabilityMessage);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductOfferServicePointAvailabilityWidget/views/availability-display/availability-display.twig';
    }

    /**
     * @param string $productOfferServicePointAvailabilityMessage
     *
     * @return void
     */
    protected function addProductOfferServicePointAvailabilityMessageParameter(string $productOfferServicePointAvailabilityMessage): void
    {
        $this->addParameter(static::PARAMETER_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_MESSAGE, $productOfferServicePointAvailabilityMessage);
    }
}
