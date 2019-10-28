<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlot\Plugin\Twig;

use Generated\Shared\Transfer\CmsSlotContextTransfer;
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
     * @param \Generated\Shared\Transfer\CmsSlotContextTransfer $cmsSlotContextTransfer
     *
     * @return string
     */
    public function getSlotContent(CmsSlotContextTransfer $cmsSlotContextTransfer): string
    {
        $cmsSlotContent = '';

        $cmsSlotStorageTransfer = $this->getFactory()->getCmsSlotStorageClient()->findCmsSlotByKey(
            $cmsSlotContextTransfer->getCmsSlotKey()
        );

        if (!$cmsSlotStorageTransfer) {
            return $cmsSlotContent;
        }

        try {
            $cmsSlotContentResponseTransfer = $this->getFactory()
                ->createCmsSlotDataProvider()
                ->getSlotContent($cmsSlotContextTransfer);
            $cmsSlotContent = $cmsSlotContentResponseTransfer->getContent();
        } catch (RuntimeException | MissingRequiredParameterException $exception) {
            if ($this->getConfig()->isDebugModeEnabled()) {
                ErrorLogger::getInstance()->log($exception);
            }
        }

        return $cmsSlotContent;
    }
}
