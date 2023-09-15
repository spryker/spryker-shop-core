<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Builder\ServicePointAvailabilityMessageBuilder;
use SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Builder\ServicePointAvailabilityMessageBuilderInterface;
use SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Dependency\Client\ProductOfferServicePointAvailabilityWidgetToCartClientInterface;
use SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Dependency\Client\ProductOfferServicePointAvailabilityWidgetToProductOfferServicePointAvailabilityCalculatorClientInterface;
use SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Reader\ProductOfferServicePointAvailabilityReader;
use SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Reader\ProductOfferServicePointAvailabilityReaderInterface;
use SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Reader\QuoteItemReader;
use SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Reader\QuoteItemReaderInterface;

class ProductOfferServicePointAvailabilityWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Reader\ProductOfferServicePointAvailabilityReaderInterface
     */
    public function createProductOfferServicePointAvailabilityReader(): ProductOfferServicePointAvailabilityReaderInterface
    {
        return new ProductOfferServicePointAvailabilityReader(
            $this->createQuoteItemReader(),
            $this->createServicePointAvailabilityMessageBuilder(),
            $this->getProductOfferServicePointAvailabilityCalculatorClient(),
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Reader\QuoteItemReaderInterface
     */
    public function createQuoteItemReader(): QuoteItemReaderInterface
    {
        return new QuoteItemReader(
            $this->getCartClient(),
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Builder\ServicePointAvailabilityMessageBuilderInterface
     */
    public function createServicePointAvailabilityMessageBuilder(): ServicePointAvailabilityMessageBuilderInterface
    {
        return new ServicePointAvailabilityMessageBuilder();
    }

    /**
     * @return \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Dependency\Client\ProductOfferServicePointAvailabilityWidgetToProductOfferServicePointAvailabilityCalculatorClientInterface
     */
    public function getProductOfferServicePointAvailabilityCalculatorClient(): ProductOfferServicePointAvailabilityWidgetToProductOfferServicePointAvailabilityCalculatorClientInterface
    {
        return $this->getProvidedDependency(ProductOfferServicePointAvailabilityWidgetDependencyProvider::CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_CALCULATOR);
    }

    /**
     * @return \SprykerShop\Yves\ProductOfferServicePointAvailabilityWidget\Dependency\Client\ProductOfferServicePointAvailabilityWidgetToCartClientInterface
     */
    public function getCartClient(): ProductOfferServicePointAvailabilityWidgetToCartClientInterface
    {
        return $this->getProvidedDependency(ProductOfferServicePointAvailabilityWidgetDependencyProvider::CLIENT_CART);
    }
}
