<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountPromotionWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\DiscountPromotionWidget\Dependency\Client\DiscountPromotionWidgetToProductStorageClientInterface;
use SprykerShop\Yves\DiscountPromotionWidget\Dependency\Service\DiscountPromotionWidgetToDiscountServiceInterface;
use SprykerShop\Yves\DiscountPromotionWidget\Expander\CartFormWidgetParameterExpander;
use SprykerShop\Yves\DiscountPromotionWidget\Expander\CartFormWidgetParameterExpanderInterface;
use SprykerShop\Yves\DiscountPromotionWidget\Expander\DiscountPromotionProductPriceExpander;
use SprykerShop\Yves\DiscountPromotionWidget\Expander\DiscountPromotionProductPriceExpanderInterface;
use SprykerShop\Yves\DiscountPromotionWidget\Reader\DiscountPromotionDiscountReader;
use SprykerShop\Yves\DiscountPromotionWidget\Reader\DiscountPromotionDiscountReaderInterface;
use SprykerShop\Yves\DiscountPromotionWidget\Reader\DiscountPromotionProductReader;
use SprykerShop\Yves\DiscountPromotionWidget\Reader\DiscountPromotionProductReaderInterface;

class DiscountPromotionWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\DiscountPromotionWidget\Dependency\Client\DiscountPromotionWidgetToProductStorageClientInterface
     */
    public function getProductStorageClient(): DiscountPromotionWidgetToProductStorageClientInterface
    {
        return $this->getProvidedDependency(DiscountPromotionWidgetDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\DiscountPromotionWidget\Dependency\Service\DiscountPromotionWidgetToDiscountServiceInterface
     */
    public function getDiscountService(): DiscountPromotionWidgetToDiscountServiceInterface
    {
        return $this->getProvidedDependency(DiscountPromotionWidgetDependencyProvider::SERVICE_DISCOUNT);
    }

    /**
     * @return \SprykerShop\Yves\DiscountPromotionWidget\Expander\CartFormWidgetParameterExpanderInterface
     */
    public function createCartFormWidgetParameterExpander(): CartFormWidgetParameterExpanderInterface
    {
        return new CartFormWidgetParameterExpander();
    }

    /**
     * @return \SprykerShop\Yves\DiscountPromotionWidget\Reader\DiscountPromotionProductReaderInterface
     */
    public function createDiscountPromotionProductReader(): DiscountPromotionProductReaderInterface
    {
        return new DiscountPromotionProductReader(
            $this->getProductStorageClient(),
            $this->createDiscountPromotionProductPriceExpander(),
        );
    }

    /**
     * @return \SprykerShop\Yves\DiscountPromotionWidget\Reader\DiscountPromotionDiscountReaderInterface
     */
    public function createDiscountPromotionDiscountReader(): DiscountPromotionDiscountReaderInterface
    {
        return new DiscountPromotionDiscountReader();
    }

    /**
     * @return \SprykerShop\Yves\DiscountPromotionWidget\Expander\DiscountPromotionProductPriceExpanderInterface
     */
    public function createDiscountPromotionProductPriceExpander(): DiscountPromotionProductPriceExpanderInterface
    {
        return new DiscountPromotionProductPriceExpander($this->getDiscountService());
    }
}
