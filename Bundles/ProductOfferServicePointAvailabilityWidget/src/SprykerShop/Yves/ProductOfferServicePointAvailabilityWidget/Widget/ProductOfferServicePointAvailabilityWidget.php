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
     * @var string
     */
    protected const PARAMETER_PRODUCT_OFFER_AVAILABILITY_DATA_PER_SERVICE_POINT = 'productOfferAvailabilityDataPerServicePoint';

    /**
     * @param list<\Generated\Shared\Transfer\ServicePointSearchTransfer> $servicePointSearchTransfers
     * @param list<string> $groupKeys
     * @param string|null $serviceTypeUuid
     * @param string|null $shipmentTypeUuid
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     */
    public function __construct(
        array $servicePointSearchTransfers,
        array $groupKeys,
        ?string $serviceTypeUuid = null,
        ?string $shipmentTypeUuid = null,
        array $itemTransfers = []
    ) {
        $productOfferAvailabilitiesGroup = $this->getFactory()->createProductOfferServicePointAvailabilityReader()->getProductOfferServicePointAvailabilities(
            $servicePointSearchTransfers,
            $groupKeys,
            $serviceTypeUuid,
            $shipmentTypeUuid,
            $itemTransfers,
        );

        $productOfferServicePointAvailabilityMessages = $productOfferAvailabilitiesGroup[0];
        $productOfferAvailabilityDataPerServicePoint = $productOfferAvailabilitiesGroup[1] ?? [];

        $this->addProductOfferServicePointAvailabilitiesParameter($productOfferServicePointAvailabilityMessages);
        $this->addProductOfferAvailabilityDataPerServicePoint($productOfferAvailabilityDataPerServicePoint);
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
     * @param array<string, array<int, array<string, mixed>>|string> $productOfferServicePointAvailabilityMessages
     *
     * @return void
     */
    protected function addProductOfferServicePointAvailabilitiesParameter(
        array $productOfferServicePointAvailabilityMessages
    ): void {
        $this->addParameter(static::PARAMETER_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITIES, $productOfferServicePointAvailabilityMessages);
    }

    /**
     * @param array<string, array<int, array<string, mixed>>|string> $productOfferAvailabilityDataPerServicePoint
     *
     * @return void
     */
    protected function addProductOfferAvailabilityDataPerServicePoint(array $productOfferAvailabilityDataPerServicePoint): void
    {
        $this->addParameter(static::PARAMETER_PRODUCT_OFFER_AVAILABILITY_DATA_PER_SERVICE_POINT, $productOfferAvailabilityDataPerServicePoint);
    }
}
