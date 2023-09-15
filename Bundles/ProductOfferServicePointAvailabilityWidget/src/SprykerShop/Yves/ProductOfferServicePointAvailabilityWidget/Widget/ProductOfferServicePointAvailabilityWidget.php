<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\ProductOfferServicePointAvailabilityWidgetFactory getFactory()
 */
class ProductOfferServicePointAvailabilityWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const NAME = 'ProductOfferServicePointAvailabilityWidget';

    /**
     * @var string
     */
    protected const PARAMETER_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITIES = 'productOfferServicePointAvailabilities';

    /**
     * @param list<\Generated\Shared\Transfer\ServicePointSearchTransfer> $servicePointSearchTransfers
     * @param list<string> $groupKeys
     * @param string|null $serviceTypeUuid
     * @param string|null $shipmentTypeUuid
     */
    public function __construct(
        array $servicePointSearchTransfers,
        array $groupKeys,
        ?string $serviceTypeUuid = null,
        ?string $shipmentTypeUuid = null
    ) {
        $this->addProductOfferServicePointAvailabilitiesParameter(
            $servicePointSearchTransfers,
            $groupKeys,
            $serviceTypeUuid,
            $shipmentTypeUuid,
        );
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
        return '';
    }

    /**
     * @param list<\Generated\Shared\Transfer\ServicePointSearchTransfer> $servicePointSearchTransfers
     * @param list<string> $groupKeys
     * @param string|null $serviceTypeUuid
     * @param string|null $shipmentTypeUuid
     *
     * @return void
     */
    protected function addProductOfferServicePointAvailabilitiesParameter(
        array $servicePointSearchTransfers,
        array $groupKeys,
        ?string $serviceTypeUuid = null,
        ?string $shipmentTypeUuid = null
    ): void {
        $this->addParameter(
            static::PARAMETER_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITIES,
            $this->getFactory()->createProductOfferServicePointAvailabilityReader()->getProductOfferServicePointAvailabilities(
                $servicePointSearchTransfers,
                $groupKeys,
                $serviceTypeUuid,
                $shipmentTypeUuid,
            ),
        );
    }
}
