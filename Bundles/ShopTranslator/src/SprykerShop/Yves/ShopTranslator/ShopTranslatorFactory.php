<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopTranslator;

use Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ShopTranslator\Model\TwigTranslator;

class ShopTranslatorFactory extends AbstractFactory
{

    /**
     * @param string $locale
     *
     * @return TwigTranslator
     */
    public function createTwigTranslator($locale)
    {
        return new TwigTranslator($this->getGlossaryStorageClient(), $locale);
    }

    /**
     * @return GlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient()
    {
        return $this->getProvidedDependency(ShopTranslatorDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }
}
