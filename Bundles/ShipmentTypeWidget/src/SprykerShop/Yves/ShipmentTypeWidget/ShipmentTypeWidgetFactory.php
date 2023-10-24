<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentTypeWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ShipmentTypeWidget\Checker\AddressFormChecker;
use SprykerShop\Yves\ShipmentTypeWidget\Checker\AddressFormCheckerInterface;
use SprykerShop\Yves\ShipmentTypeWidget\Cleaner\QuoteCleaner;
use SprykerShop\Yves\ShipmentTypeWidget\Cleaner\QuoteCleanerInterface;
use SprykerShop\Yves\ShipmentTypeWidget\Dependency\Client\ShipmentTypeWidgetToShipmentTypeStorageClientInterface;
use SprykerShop\Yves\ShipmentTypeWidget\Expander\QuoteExpander;
use SprykerShop\Yves\ShipmentTypeWidget\Expander\QuoteExpanderInterface;
use SprykerShop\Yves\ShipmentTypeWidget\Expander\ShipmentTypeFormOptionExpander;
use SprykerShop\Yves\ShipmentTypeWidget\Expander\ShipmentTypeFormOptionExpanderInterface;
use SprykerShop\Yves\ShipmentTypeWidget\Form\ShipmentTypeAddressStepForm;
use SprykerShop\Yves\ShipmentTypeWidget\Hydrator\ShipmentTypeFormPreSetDataHydrator;
use SprykerShop\Yves\ShipmentTypeWidget\Hydrator\ShipmentTypeFormPreSetDataHydratorInterface;
use SprykerShop\Yves\ShipmentTypeWidget\Hydrator\ShipmentTypeFormSubmitDataHydrator;
use SprykerShop\Yves\ShipmentTypeWidget\Hydrator\ShipmentTypeFormSubmitDataHydratorInterface;
use SprykerShop\Yves\ShipmentTypeWidget\Reader\ShipmentTypeReader;
use SprykerShop\Yves\ShipmentTypeWidget\Reader\ShipmentTypeReaderInterface;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @method \SprykerShop\Yves\ShipmentTypeWidget\ShipmentTypeWidgetConfig getConfig()
 */
class ShipmentTypeWidgetFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createShipmentTypeAddressStepForm(): FormTypeInterface
    {
        return new ShipmentTypeAddressStepForm();
    }

    /**
     * @return \SprykerShop\Yves\ShipmentTypeWidget\Hydrator\ShipmentTypeFormPreSetDataHydratorInterface
     */
    public function createShipmentTypeFormPreSetDataHydrator(): ShipmentTypeFormPreSetDataHydratorInterface
    {
        return new ShipmentTypeFormPreSetDataHydrator(
            $this->createAddressFormChecker(),
        );
    }

    /**
     * @return \SprykerShop\Yves\ShipmentTypeWidget\Hydrator\ShipmentTypeFormSubmitDataHydratorInterface
     */
    public function createShipmentTypeFormSubmitDataHydrator(): ShipmentTypeFormSubmitDataHydratorInterface
    {
        return new ShipmentTypeFormSubmitDataHydrator(
            $this->createAddressFormChecker(),
        );
    }

    /**
     * @return \SprykerShop\Yves\ShipmentTypeWidget\Expander\ShipmentTypeFormOptionExpanderInterface
     */
    public function createShipmentTypeFormOptionExpander(): ShipmentTypeFormOptionExpanderInterface
    {
        return new ShipmentTypeFormOptionExpander(
            $this->createShipmentTypeReader(),
        );
    }

    /**
     * @return \SprykerShop\Yves\ShipmentTypeWidget\Checker\AddressFormCheckerInterface
     */
    public function createAddressFormChecker(): AddressFormCheckerInterface
    {
        return new AddressFormChecker(
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerShop\Yves\ShipmentTypeWidget\Reader\ShipmentTypeReaderInterface
     */
    public function createShipmentTypeReader(): ShipmentTypeReaderInterface
    {
        return new ShipmentTypeReader(
            $this->getShipmentTypeStorageClient(),
        );
    }

    /**
     * @return \SprykerShop\Yves\ShipmentTypeWidget\Expander\QuoteExpanderInterface
     */
    public function createQuoteExpander(): QuoteExpanderInterface
    {
        return new QuoteExpander();
    }

    /**
     * @return \SprykerShop\Yves\ShipmentTypeWidget\Cleaner\QuoteCleanerInterface
     */
    public function createQuoteCleaner(): QuoteCleanerInterface
    {
        return new QuoteCleaner();
    }

    /**
     * @return \SprykerShop\Yves\ShipmentTypeWidget\Dependency\Client\ShipmentTypeWidgetToShipmentTypeStorageClientInterface
     */
    public function getShipmentTypeStorageClient(): ShipmentTypeWidgetToShipmentTypeStorageClientInterface
    {
        return $this->getProvidedDependency(ShipmentTypeWidgetDependencyProvider::CLIENT_SHIPMENT_TYPE_STORAGE);
    }
}
