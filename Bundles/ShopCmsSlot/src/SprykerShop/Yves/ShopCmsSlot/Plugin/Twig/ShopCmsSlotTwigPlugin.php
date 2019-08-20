<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlot\Plugin\Twig;

use RuntimeException;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use SprykerShop\Yves\ShopApplication\Plugin\AbstractTwigExtensionPlugin;
use SprykerShop\Yves\ShopCmsSlot\Exception\MissingRequiredParameterException;

/**
 * @method \SprykerShop\Yves\ShopCmsSlot\ShopCmsSlotConfig getConfig()
 * @method \SprykerShop\Yves\ShopCmsSlot\ShopCmsSlotFactory getFactory()
 */
class ShopCmsSlotTwigPlugin extends AbstractTwigExtensionPlugin
{
    /**
     * @return \Twig\TokenParser\TokenParserInterface[]
     */
    public function getTokenParsers(): array
    {
        return [
            $this->getFactory()->createShopCmsSlotTokenParser(),
        ];
    }

    /**
     * @param string $cmsSlotKey
     * @param array $provided
     * @param string[] $required
     * @param string[] $autoFilled
     *
     * @return string
     */
    public function getSlotContent(string $cmsSlotKey, array $provided, array $required, array $autoFilled): string
    {
        $cmsSlotContent = '';

        try {
            $cmsSlotDataTransfer = $this->getFactory()
                ->createCmsSlotDataProvider()
                ->getSlotContent($cmsSlotKey, $provided, $required, $autoFilled);
            $cmsSlotContent = $cmsSlotDataTransfer->getContent();
        } catch (RuntimeException | MissingRequiredParameterException $e) {
            if ($this->getConfig()->isDebugModeEnabled()) {
                ErrorLogger::getInstance()->log($e);
            }
        }

        return $cmsSlotContent;
    }
}
