<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopTranslator;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ShopTranslator\Dependency\Client\ShopTranslatorToGlossaryStorageClientInterface;
use SprykerShop\Yves\ShopTranslator\Model\TwigTranslator;

/**
 * @deprecated Use `spryker/translator` instead.
 */
class ShopTranslatorFactory extends AbstractFactory
{
    /**
     * @param string $locale
     *
     * @return \SprykerShop\Yves\ShopTranslator\Model\TwigTranslator
     */
    public function createTwigTranslator($locale)
    {
        return new TwigTranslator($this->getGlossaryStorageClient(), $locale);
    }

    /**
     * @return \SprykerShop\Yves\ShopTranslator\Dependency\Client\ShopTranslatorToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): ShopTranslatorToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(ShopTranslatorDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }
}
